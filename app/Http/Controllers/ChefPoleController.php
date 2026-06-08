<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChefPoleController extends Controller
{
    /**
     * Display the chef de pôle dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        $totalDemandes = $user->demandes()->count();
        $pendingDemandes = $user->demandes()->where('status', 'en_attente')->count();
        $processedDemandes = $user->demandes()->where('status', 'traité')->count();
        
        $latestDemandes = $user->demandes()
            ->with('products')
            ->latest()
            ->take(5)
            ->get();

        return view('chef_pole.dashboard', compact(
            'totalDemandes',
            'pendingDemandes',
            'processedDemandes',
            'latestDemandes'
        ));
    }

    /**
     * Show the form to create a new stock request.
     */
    public function createDemande()
    {
        $products = Product::orderBy('name')->get();
        return view('chef_pole.create_demande', compact('products'));
    }

    /**
     * Store a newly created stock request in the database.
     */
    public function storeDemande(Request $request)
    {
        $request->validate([
            'teacher_name' => 'required|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantite_demandee' => 'required|integer|min:1',
        ], [
            'teacher_name.required' => 'Le nom de l\'enseignant est requis.',
            'products.required' => 'Vous devez sélectionner au moins un produit.',
            'products.*.quantite_demandee.min' => 'La quantité demandée doit être d\'au moins 1.',
        ]);

        DB::transaction(function () use ($request) {
            $demande = Demande::create([
                'user_id' => Auth::id(),
                'teacher_name' => $request->teacher_name,
                'status' => 'en_attente',
            ]);

            foreach ($request->products as $prodData) {
                $demande->products()->attach($prodData['id'], [
                    'quantite_demandee' => $prodData['quantite_demandee'],
                ]);
            }
        });

        return redirect()->route('chef_pole.dashboard')
            ->with('success', 'Votre demande de stock a été soumise avec succès.');
    }

    /**
     * Display the request history of the authenticated user.
     */
    public function history()
    {
        $demandes = Auth::user()->demandes()
            ->with('products')
            ->latest()
            ->paginate(10);

        return view('chef_pole.history', compact('demandes'));
    }
}
