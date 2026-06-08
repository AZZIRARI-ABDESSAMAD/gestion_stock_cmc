@extends('layouts.app')

@section('title', 'Tableau de bord - Magasinier')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Espace Magasinier</h2>
        <p class="text-sm text-slate-500">Gérez le stock de produits et traitez les demandes de matériel.</p>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Articles</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalProducts }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Stock Critique</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $lowStockCount }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Demandes en attente</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $pendingCount }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Demandes traitées</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalProcessed }}</h3>
            </div>
        </div>
    </div>

    {{-- Quick Actions Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Recent Pending Requests --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm flex flex-col overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200/60 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">Demandes en attente</h3>
                <a href="{{ route('magasinier.demandes.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Voir tout</a>
            </div>

            <div class="flex-1 overflow-y-auto max-h-96">
                @if($recentPending->isEmpty())
                    <div class="p-8 text-center text-slate-500">
                        <p class="text-sm">Aucune demande en attente.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($recentPending as $demande)
                            <div class="p-5 hover:bg-slate-50/50 transition-colors flex items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-800">#{{ $demande->id }}</span>
                                        <span class="text-xs text-slate-400 font-semibold">{{ $demande->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-slate-700 mt-1">Enseignant : {{ $demande->teacher_name }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Par : {{ $demande->user->name }} (Chef de Pôle)</p>
                                </div>
                                <div>
                                    <a href="{{ route('magasinier.demandes.approve', $demande) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-sm shadow-indigo-600/10">
                                        Traiter
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Shortcuts & Quick Actions --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Actions Rapides</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('magasinier.stock.create') }}"
                   class="p-4 rounded-xl border border-slate-200/60 hover:border-indigo-500 hover:bg-indigo-50/20 flex flex-col gap-3 group transition-all">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Ajouter un produit</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Enregistrer un nouvel article en stock.</p>
                    </div>
                </a>

                <a href="{{ route('magasinier.stock.index') }}"
                   class="p-4 rounded-xl border border-slate-200/60 hover:border-indigo-500 hover:bg-indigo-50/20 flex flex-col gap-3 group transition-all">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Voir le stock</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Consulter et modifier l'inventaire actuel.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
