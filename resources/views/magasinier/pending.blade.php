@extends('layouts.app')

@section('title', 'Demandes en Attente')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Demandes de Stock en Attente</h2>
        <p class="text-sm text-slate-500">Traitez et validez les demandes soumises par les Chefs de Pôle.</p>
    </div>

    {{-- Demandes List Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        @if($demandes->isEmpty())
            <div class="px-6 py-12 text-center text-slate-500">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <h4 class="text-lg font-bold text-slate-700 mb-1">Aucune demande en attente</h4>
                <p class="text-sm">Toutes les demandes de matériel ont été traitées.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/60 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <th class="px-6 py-4">Réf & Date</th>
                            <th class="px-6 py-4">Chef de Pôle</th>
                            <th class="px-6 py-4">Enseignant</th>
                            <th class="px-6 py-4">Articles Demandés</th>
                            <th class="px-6 py-4 text-right">Action</th>
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
                                    {{ $demande->user->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $demande->teacher_name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5 max-w-sm">
                                        @foreach($demande->products as $product)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs bg-slate-100 text-slate-700 font-medium">
                                                {{ $product->name }} (x{{ $product->pivot->quantite_demandee }})
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('magasinier.demandes.approve', $demande) }}"
                                       class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-indigo-600/10 transition-all duration-200 hover:-translate-y-0.5">
                                        Traiter
                                    </a>
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
