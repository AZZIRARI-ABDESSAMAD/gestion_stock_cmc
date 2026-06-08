<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MagasinierController extends Controller
{
    /**
     * Display the magasinier dashboard.
     */
    public function dashboard()
    {
        $totalProcessed = Demande::where('status', 'traité')->count();
        $pendingCount = Demande::where('status', 'en_attente')->count();
        $totalProducts = Product::count();
        $lowStockCount = Product::where('quantity', '<', 5)->count();

        $recentPending = Demande::where('status', 'en_attente')
            ->with(['user', 'products'])
            ->latest()
            ->take(5)
            ->get();

        return view('magasinier.dashboard', compact(
            'totalProcessed',
            'pendingCount',
            'totalProducts',
            'lowStockCount',
            'recentPending'
        ));
    }

    /**
     * Display the list of products in stock.
     */
    public function stockIndex()
    {
        $products = Product::orderBy('name')->paginate(15);
        return view('magasinier.stock', compact('products'));
    }

    /**
     * Show the form to create a new product.
     */
    public function stockCreate()
    {
        $product = new Product(); // For empty state form binding
        return view('magasinier.stock_form', compact('product'));
    }

    /**
     * Store a new product in stock.
     */
    public function stockStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'quantity' => 'required|integer|min:0',
        ], [
            'name.required' => 'Le nom du produit est requis.',
            'name.unique' => 'Ce produit existe déjà dans le stock.',
            'quantity.required' => 'La quantité est requise.',
            'quantity.min' => 'La quantité ne peut pas être négative.',
        ]);

        Product::create($request->only('name', 'quantity'));

        return redirect()->route('magasinier.stock.index')
            ->with('success', 'Produit ajouté au stock avec succès.');
    }

    /**
     * Show the form to edit a product.
     */
    public function stockEdit(Product $product)
    {
        return view('magasinier.stock_form', compact('product'));
    }

    /**
     * Update a product in stock.
     */
    public function stockUpdate(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'quantity' => 'required|integer|min:0',
        ], [
            'name.required' => 'Le nom du produit est requis.',
            'name.unique' => 'Ce produit existe déjà dans le stock.',
            'quantity.required' => 'La quantité est requise.',
            'quantity.min' => 'La quantité ne peut pas être négative.',
        ]);

        $product->update($request->only('name', 'quantity'));

        return redirect()->route('magasinier.stock.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Delete a product from stock.
     */
    public function stockDestroy(Product $product)
    {
        $product->delete();
        return redirect()->route('magasinier.stock.index')
            ->with('success', 'Produit supprimé du stock avec succès.');
    }

    /**
     * Display a listing of pending demands.
     */
    public function demandesIndex()
    {
        $demandes = Demande::where('status', 'en_attente')
            ->with(['user', 'products'])
            ->latest()
            ->paginate(10);

        return view('magasinier.pending', compact('demandes'));
    }

    /**
     * Show the form to approve a specific demand.
     */
    public function demandesApprove(Demande $demande)
    {
        if ($demande->status !== 'en_attente') {
            return redirect()->route('magasinier.demandes.index')
                ->with('error', 'Cette demande a déjà été traitée.');
        }

        $demande->load('products');
        return view('magasinier.approve', compact('demande'));
    }

    /**
     * Process/Approve a demand, updating product stock atomically.
     */
    public function demandesProcess(Request $request, Demande $demande)
    {
        if ($demande->status !== 'en_attente') {
            return redirect()->route('magasinier.demandes.index')
                ->with('error', 'Cette demande a déjà été traitée.');
        }

        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $demande) {
            foreach ($demande->products as $product) {
                $productId = $product->id;
                $requestedQty = $product->pivot->quantite_demandee;
                
                // Get the input approved quantity
                $inputQty = isset($request->quantities[$productId]) ? (int)$request->quantities[$productId] : 0;
                
                // Force lock the product row to avoid race conditions
                $dbProduct = Product::where('id', $productId)->lockForUpdate()->first();
                $availableStock = $dbProduct->quantity;
                
                // Auto-cap quantity to min(stock, requested, input)
                $approvedQty = min($inputQty, $requestedQty, $availableStock);
                
                // Deduct stock
                $dbProduct->quantity = $availableStock - $approvedQty;
                $dbProduct->save();

                // Update pivot table
                $demande->products()->updateExistingPivot($productId, [
                    'quantite_approuvee' => $approvedQty,
                ]);
            }

            // Update status of Demande
            $demande->status = 'traité';
            $demande->save();
        });

        return redirect()->route('magasinier.dashboard')
            ->with('success', 'La demande a été traitée et le stock a été mis à jour.');
    }

    /**
     * Display a listing of all global processed demands.
     */
    public function history()
    {
        $demandes = Demande::where('status', 'traité')
            ->with(['user', 'products'])
            ->latest()
            ->paginate(15);

        return view('magasinier.history', compact('demandes'));
    }
}
