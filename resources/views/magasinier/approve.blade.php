@extends('layouts.app')

@section('title', 'Traiter la Demande #' . $demande->id)

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('magasinier.demandes.index') }}" class="p-2 bg-white border border-slate-200 text-slate-500 hover:text-slate-700 rounded-xl hover:bg-slate-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Traiter la Demande #{{ $demande->id }}</h2>
            <p class="text-sm text-slate-500">Ajustez les quantités approuvées pour chaque article demandé.</p>
        </div>
    </div>

    {{-- Request Info Card --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Chef de Pôle</span>
            <span class="text-sm font-semibold text-slate-700">{{ $demande->user->name }}</span>
            <span class="block text-xs text-slate-400">{{ $demande->user->email }}</span>
        </div>
        <div>
            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Enseignant Bénéficiaire</span>
            <span class="text-sm font-semibold text-slate-700">{{ $demande->teacher_name }}</span>
        </div>
        <div>
            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Date de Soumission</span>
            <span class="text-sm font-semibold text-slate-700">{{ $demande->created_at->format('d/m/Y \à H:i') }}</span>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('magasinier.demandes.process', $demande) }}" method="POST" class="space-y-6">
        @csrf

        {{-- Products Grid Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/60 bg-slate-50/50">
                <h3 class="text-base font-bold text-slate-800">Articles demandés</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <th class="px-6 py-3.5">Produit</th>
                            <th class="px-6 py-3.5 w-32 text-center">Quantité Demandée</th>
                            <th class="px-6 py-3.5 w-32 text-center">Stock Actuel</th>
                            <th class="px-6 py-3.5 w-48">Quantité Approuvée</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($demande->products as $product)
                            @php
                                // Pre-fill calculation: min(stock, requested)
                                $suggestedQty = min($product->pivot->quantite_demandee, $product->quantity);
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-800">{{ $product->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-slate-700">
                                    {{ $product->pivot->quantite_demandee }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($product->isLowStock())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-100 animate-pulse">
                                            {{ $product->quantity }} (Bas)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                                            {{ $product->quantity }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <input type="number" name="quantities[{{ $product->id }}]" 
                                               value="{{ $suggestedQty }}" 
                                               min="0" 
                                               max="{{ min($product->pivot->quantite_demandee, $product->quantity) }}"
                                               required
                                               class="w-32 px-3 py-1.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm font-semibold bg-white"
                                               id="qty-input-{{ $product->id }}">
                                        <p class="text-[10px] text-slate-400 font-semibold">Max autorisé: {{ min($product->pivot->quantite_demandee, $product->quantity) }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('magasinier.demandes.index') }}"
               class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-sm rounded-xl transition-all duration-200">
                Annuler
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-600/20 transition-all duration-200 hover:-translate-y-0.5">
                Valider et Déduire du Stock
            </button>
        </div>
    </form>
</div>
@endsection
