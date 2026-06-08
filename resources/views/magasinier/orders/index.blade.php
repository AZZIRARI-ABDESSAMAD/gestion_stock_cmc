@extends('layouts.app')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="space-y-8 animate-fade-in">
    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Gestion des Commandes</h2>
        <p class="text-sm text-slate-500">Validez les nouvelles commandes ou mettez à jour le statut des commandes validées.</p>
    </div>

    {{-- Pending Orders Section --}}
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
            Commandes en attente de validation
        </h3>

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <th class="px-6 py-4">Référence</th>
                            <th class="px-6 py-4">Pôle / Espace</th>
                            <th class="px-6 py-4">Demandeur</th>
                            <th class="px-6 py-4">Date de demande</th>
                            <th class="px-6 py-4 w-40 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @if($pendingOrders->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                    Aucune commande en attente.
                                </td>
                            </tr>
                        @else
                            @foreach($pendingOrders as $commande)
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $commande->reference }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-700">
                                        {{ $commande->user->space->name_espace ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $commande->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $commande->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('magasinier.orders.show', $commande->id) }}" 
                                           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-indigo-600/10 transition-all">
                                            Traiter / Valider
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Active / Validated Orders Section --}}
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
            Suivi des commandes validées
        </h3>

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <th class="px-6 py-4">Référence</th>
                            <th class="px-6 py-4">Pôle / Espace</th>
                            <th class="px-6 py-4">Date de Validation</th>
                            <th class="px-6 py-4">Statut Actuel</th>
                            <th class="px-6 py-4 w-60 text-center">Changer le statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @if($activeOrders->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                    Aucune commande en cours d'expédition/livraison.
                                </td>
                            </tr>
                        @else
                            @foreach($activeOrders as $commande)
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $commande->reference }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-700">
                                        {{ $commande->user->space->name_espace ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $commande->updated_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($commande->isValidated())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Validée
                                            </span>
                                        @elseif($commande->isShipped())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                Expédiée
                                            </span>
                                        @elseif($commande->isDelivered())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                Livrée ✓
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($commande->isValidated())
                                                {{-- Transition to Expediee --}}
                                                <form action="{{ route('magasinier.orders.status', $commande->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <input type="hidden" name="status" value="expediee">
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-bold rounded-lg transition-all duration-150 shadow-sm hover:shadow">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/>
                                                        </svg>
                                                        Expédier
                                                    </button>
                                                </form>
                                            @elseif($commande->isShipped())
                                                <span class="text-xs text-slate-400 italic">En attente de réception par le Chef</span>
                                            @elseif($commande->isDelivered())
                                                <span class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Boucle fermée
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
