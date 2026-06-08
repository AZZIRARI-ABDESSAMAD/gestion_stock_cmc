@extends('layouts.app')

@section('title', 'Tableau de bord - Chef Espace')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Espace Pôle</h2>
        <p class="text-sm text-slate-500">Bienvenue dans votre espace de commande. Suivez vos demandes en temps réel.</p>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-650 font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Commandes</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalOrders }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">En cours</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $pendingCount }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Validées</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $validatedCount }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Refusées</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $refusedCount }}</h3>
            </div>
        </div>
    </div>

    {{-- Dashboard Body --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Orders --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm lg:col-span-2 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200/60 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">Commandes Récentes</h3>
                <a href="{{ route('chef_espace.history') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Voir tout</a>
            </div>

            <div class="divide-y divide-slate-100">
                @if($recentOrders->isEmpty())
                    <div class="p-8 text-center text-slate-500">
                        <p class="text-sm">Aucune commande récente.</p>
                    </div>
                @else
                    @foreach($recentOrders as $order)
                        <div class="p-6 hover:bg-slate-50/50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2.5">
                                    <span class="font-bold text-slate-800 text-sm">{{ $order->reference }}</span>
                                    <span class="text-xs text-slate-400 font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mt-2 space-y-1">
                                    @foreach($order->products->take(3) as $product)
                                        <p class="text-xs text-slate-500 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                            {{ $product->title }} <span class="font-semibold text-slate-700">(Demandé: {{ $product->pivot->quantite_commander }})</span>
                                        </p>
                                    @endforeach
                                    @if($order->products->count() > 3)
                                        <p class="text-xs text-slate-400 font-semibold italic">Et {{ $order->products->count() - 3 }} de plus...</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                @if($order->isOnCours())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        En cours
                                    </span>
                                @elseif($order->isValidated())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Validée
                                    </span>
                                @elseif($order->isShipped())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Expédiée
                                    </span>
                                @elseif($order->isDelivered())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        Livrée
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Refusée
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Shortcuts --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm space-y-4 flex flex-col justify-between">
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-slate-800">Actions</h3>
                <p class="text-sm text-slate-500">Besoin de nouvelles fournitures ou de matériel pour votre pôle ? Créez une nouvelle commande en quelques clics.</p>
            </div>
            
            <a href="{{ route('chef_espace.commandes.create') }}" 
               class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl text-center shadow-lg shadow-indigo-650/15 hover:shadow-indigo-655/25 transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle Commande
            </a>
        </div>
    </div>
</div>
@endsection
