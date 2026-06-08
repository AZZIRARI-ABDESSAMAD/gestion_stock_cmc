<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Commande;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MagasinierController extends Controller
{
    /**
     * Dashboard: general statistics and recent pending orders.
     */
    public function dashboard()
    {
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $lowStockCount = Product::where('quantity', '<', 5)->count();
        $pendingCount = Commande::where('status', 'on cours')->count();

        $recentPending = Commande::with(['user.space', 'products'])
            ->where('status', 'on cours')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        return view('magasinier.dashboard', compact(
            'totalCategories',
            'totalProducts',
            'lowStockCount',
            'pendingCount',
            'recentPending'
        ));
    }

    /* ─── Category CRUD ─── */

    public function categoryIndex()
    {
        $categories = Category::withCount('products')
            ->orderBy('title')
            ->paginate(10);
        return view('magasinier.categories.index', compact('categories'));
    }

    public function categoryCreate()
    {
        return view('magasinier.categories.form', ['category' => new Category()]);
    }

    public function categoryStore(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($data);
        return redirect()->route('magasinier.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function categoryEdit(Category $category)
    {
        return view('magasinier.categories.form', compact('category'));
    }

    public function categoryUpdate(Request $request, Category $category)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($data);
        return redirect()->route('magasinier.categories.index')
            ->with('success', 'Catégorie modifiée avec succès.');
    }

    public function categoryDestroy(Category $category)
    {
        $category->delete();
        return redirect()->route('magasinier.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    /* ─── Product CRUD (Stock) ─── */

    public function stockIndex()
    {
        $products = Product::with('category')
            ->orderBy('title')
            ->paginate(15);
        return view('magasinier.stock', compact('products'));
    }

    public function stockCreate()
    {
        $categories = Category::orderBy('title')->get();
        return view('magasinier.stock_form', [
            'product' => new Product(),
            'categories' => $categories
        ]);
    }

    public function stockStore(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create($data);
        return redirect()->route('magasinier.stock.index')
            ->with('success', 'Produit ajouté avec succès.');
    }

    public function stockEdit(Product $product)
    {
        $categories = Category::orderBy('title')->get();
        return view('magasinier.stock_form', compact('product', 'categories'));
    }

    public function stockUpdate(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product->update($data);
        return redirect()->route('magasinier.stock.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function stockDestroy(Product $product)
    {
        $product->delete();
        return redirect()->route('magasinier.stock.index')
            ->with('success', 'Produit supprimé du stock.');
    }

    /* ─── Order Management ─── */

    /**
     * List all pending and active orders.
     */
    public function ordersIndex()
    {
        $pendingOrders = Commande::with(['user.space', 'products'])
            ->where('status', 'on cours')
            ->orderBy('created_at', 'asc')
            ->get();

        $activeOrders = Commande::with(['user.space', 'products'])
            ->whereIn('status', ['valide', 'expediee'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('magasinier.orders.index', compact('pendingOrders', 'activeOrders'));
    }

    /**
     * Show validation form for a single order.
     */
    public function orderShow(Commande $commande)
    {
        if (!$commande->isOnCours()) {
            return redirect()->route('magasinier.orders.index')
                ->with('error', "Cette commande a déjà été traitée.");
        }

        $commande->load(['user.space', 'products']);
        return view('magasinier.orders.show', compact('commande'));
    }

    /**
     * Process validation/rejection flow.
     */
    public function orderProcess(Request $request, Commande $commande)
    {
        if (!$commande->isOnCours()) {
            return redirect()->route('magasinier.orders.index')
                ->with('error', 'Cette commande a déjà été traitée.');
        }

        $action = $request->input('action'); // 'valider' or 'refuser'

        if ($action === 'refuser') {
            $commande->update(['status' => 'refuser']);
            return redirect()->route('magasinier.orders.index')
                ->with('success', "La commande {$commande->reference} a été refusée.");
        }

        // Validate quantities for 'valider' action
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->input('quantities') as $productId => $qtyValide) {
                $product = Product::findOrFail($productId);
                
                // Fetch the pivot to verify the ordered quantity
                $pivot = $commande->products()->where('product_id', $productId)->first()->pivot;
                $qtyOrdered = $pivot->quantite_commander;

                // Validate that the approved quantity is not higher than ordered or stock
                if ($qtyValide > $qtyOrdered) {
                    throw new \Exception("La quantité validée pour {$product->title} ({$qtyValide}) dépasse la quantité demandée ({$qtyOrdered}).");
                }

                if ($qtyValide > $product->quantity) {
                    throw new \Exception("La quantité validée pour {$product->title} ({$qtyValide}) dépasse le stock disponible ({$product->quantity}).");
                }

                // Deduct from product stock
                $product->decrement('quantity', $qtyValide);

                // Update the pivot table
                $commande->products()->updateExistingPivot($productId, [
                    'quantite_valide' => $qtyValide,
                ]);
            }

            // Update status to 'valide'
            $commande->update(['status' => 'valide']);

            DB::commit();
            return redirect()->route('magasinier.orders.index')
                ->with('success', "La commande {$commande->reference} a été validée et le stock mis à jour.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update validated orders status (expediee -> livre).
     */
    public function updateStatus(Request $request, Commande $commande)
    {
        $request->validate([
            'status' => 'required|in:expediee,livre',
        ]);

        if (!$commande->isValidated() && !$commande->isShipped()) {
            return redirect()->route('magasinier.orders.index')
                ->with('error', "Le statut de cette commande ne peut pas être modifié.");
        }

        $commande->update(['status' => $request->input('status')]);

        return redirect()->route('magasinier.orders.index')
            ->with('success', "Le statut de la commande {$commande->reference} a été mis à jour.");
    }

    /**
     * Global history of all processed (validated, refused, shipped, delivered) orders.
     */
    public function globalHistory()
    {
        $commandes = Commande::with(['user.space', 'products'])
            ->whereIn('status', ['valide', 'refuser', 'expediee', 'livre'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('magasinier.orders.history', compact('commandes'));
    }
}
