@extends('layouts.app')

@section('title', 'Tableau de bord - Magasinier')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Espace Magasinier</h2>
        <p class="text-sm text-slate-500">Gérez l'inventaire des produits, les catégories et validez les commandes des espaces.</p>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-650 font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Catégories</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalCategories }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Produits au catalogue</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalProducts }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Stock Critique (&lt; 5)</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $lowStockCount }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Commandes en attente</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $pendingCount }}</h3>
            </div>
        </div>
    </div>

    {{-- Layout Body --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Pending Orders --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm lg:col-span-2 overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-6 py-5 border-b border-slate-200/60 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">Commandes en cours</h3>
                    <a href="{{ route('magasinier.orders.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Gérer les commandes</a>
                </div>

                <div class="divide-y divide-slate-100">
                    @if($recentPending->isEmpty())
                        <div class="p-8 text-center text-slate-400">
                            Aucune commande en attente de validation.
                        </div>
                    @else
                        @foreach($recentPending as $commande)
                            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-800">{{ $commande->reference }}</span>
                                        <span class="text-xs text-slate-400 font-semibold">{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="text-xs text-indigo-650 font-bold mt-1">
                                        Par : {{ $commande->user->name }} (Pôle : {{ $commande->user->space->name_espace ?? 'N/A' }})
                                    </p>
                                    <div class="mt-2 space-y-1">
                                        @foreach($commande->products->take(2) as $product)
                                            <p class="text-xs text-slate-500 flex items-center gap-1">
                                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                {{ $product->title }} <span class="font-semibold text-slate-700">(Demandé: {{ $product->pivot->quantite_commander }})</span>
                                            </p>
                                        @endforeach
                                        @if($commande->products->count() > 2)
                                            <p class="text-xs text-slate-400 font-medium italic">+ {{ $commande->products->count() - 2 }} autre(s) produit(s)...</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <a href="{{ route('magasinier.orders.show', $commande->id) }}" 
                                       class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-xs rounded-xl border border-indigo-100/40 transition-colors">
                                        Traiter / Valider
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Low Stock Alert Box --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm space-y-4">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Alertes de Stock
            </h3>
            
            <p class="text-xs text-slate-400">Les articles suivants ont un niveau de stock critique et doivent être réapprovisionnés.</p>

            <div class="space-y-3 max-h-[280px] overflow-y-auto pr-1">
                @php
                    $lowStockProducts = \App\Models\Product::with('category')->where('quantity', '<', 5)->orderBy('quantity', 'asc')->take(5)->get();
                @endphp

                @if($lowStockProducts->isEmpty())
                    <p class="text-sm text-emerald-600 font-semibold text-center py-6">Aucune alerte de stock. Tout est correct !</p>
                @else
                    @foreach($lowStockProducts as $product)
                        <div class="p-3 bg-rose-50/50 border border-rose-100/60 rounded-xl flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $product->title }}</p>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wide">{{ $product->category->title }}</p>
                            </div>
                            <span class="px-2.5 py-1 bg-rose-100 text-rose-700 text-xs font-bold rounded-lg whitespace-nowrap">
                                Stock: {{ $product->quantity }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
