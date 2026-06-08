<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChefEspaceController extends Controller
{
    /**
     * Dashboard: stats and recent orders.
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Statistics
        $totalOrders = $user->commandes()->count();
        $pendingCount = $user->commandes()->where('status', 'on cours')->count();
        $validatedCount = $user->commandes()->where('status', 'valide')->count();
        $refusedCount = $user->commandes()->where('status', 'refuser')->count();

        // Recent orders
        $recentOrders = $user->commandes()
            ->with('products')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('chef_espace.dashboard', compact(
            'totalOrders',
            'pendingCount',
            'validatedCount',
            'refusedCount',
            'recentOrders'
        ));
    }

    /**
     * Show order creation page.
     */
    public function createCommande()
    {
        $products = Product::with('category')
            ->orderBy('title')
            ->get();

        return view('chef_espace.create_commande', compact('products'));
    }

    /**
     * Store a new order in the database.
     */
    public function storeCommande(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantite_commander' => 'required|integer|min:1',
        ], [
            'products.required' => 'Veuillez ajouter au moins un produit à la commande.',
            'products.*.quantite_commander.min' => 'La quantité commandée doit être supérieure à 0.',
        ]);

        DB::beginTransaction();
        try {
            // Generate unique reference e.g. CMD-YYYYMMDD-XXXX
            $date = now()->format('Ymd');
            do {
                $random = strtoupper(Str::random(4));
                $reference = "CMD-{$date}-{$random}";
            } while (Commande::where('reference', $reference)->exists());

            // Create Commande
            $commande = Commande::create([
                'reference' => $reference,
                'status' => 'on cours',
                'user_id' => auth()->id(),
            ]);

            // Attach products with pivot attributes
            foreach ($request->input('products') as $item) {
                $commande->products()->attach($item['id'], [
                    'quantite_commander' => $item['quantite_commander'],
                    'quantite_valide' => null, // validation pending
                ]);
            }

            DB::commit();
            return redirect()->route('chef_espace.dashboard')
                ->with('success', "La commande {$reference} a été enregistrée avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', "Une erreur est survenue lors de l'enregistrement : " . $e->getMessage());
        }
    }

    /**
     * Order history index.
     */
    public function history()
    {
        $commandes = auth()->user()->commandes()
            ->with('products')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('chef_espace.history', compact('commandes'));
    }
}
