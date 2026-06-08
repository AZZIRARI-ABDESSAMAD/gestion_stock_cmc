@extends('layouts.app')

@section('title', 'Mon Historique - Chef de Pôle')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Historique des Demandes</h2>
            <p class="text-sm text-slate-500">Consultez l'état de toutes vos demandes de stock de matériel.</p>
        </div>
        <div>
            <a href="{{ route('chef_pole.demandes.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-indigo-600/20 transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle Demande
            </a>
        </div>
    </div>

    {{-- Requests Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        @if($demandes->isEmpty())
            <div class="px-6 py-12 text-center text-slate-500">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <h4 class="text-lg font-bold text-slate-700 mb-1">Aucune demande trouvée</h4>
                <p class="text-sm">Vous n'avez pas encore effectué de demandes de stock.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/60 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <th class="px-6 py-4">Réf & Date</th>
                            <th class="px-6 py-4">Enseignant</th>
                            <th class="px-6 py-4">Articles Demandés & Validés</th>
                            <th class="px-6 py-4">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($demandes as $demande)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-800">#{{ $demande->id }}</span>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $demande->created_at->format('d/m/Y H:i') }}</p>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700">
                                    {{ $demande->teacher_name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1.5">
                                        @foreach($demande->products as $product)
                                            <div class="flex items-center gap-2 text-slate-700">
                                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                                <span class="font-medium">{{ $product->name }}</span>
                                                <span class="text-xs text-slate-400 font-semibold">(Demandé: {{ $product->pivot->quantite_demandee }})</span>
                                                
                                                @if(!$demande->isPending())
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                        Validé: {{ $product->pivot->quantite_approuvee ?? 0 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($demande->isPending())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            En attente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Traité
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($demandes->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $demandes->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
