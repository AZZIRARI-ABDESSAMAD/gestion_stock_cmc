@extends('layouts.app')

@section('title', 'Traiter la Commande ' . $commande->reference)

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Validation de la Commande</h2>
            <p class="text-sm text-slate-500">Référence : <span class="font-bold text-slate-800">{{ $commande->reference }}</span></p>
        </div>
        <div>
            <a href="{{ route('magasinier.orders.index') }}" 
               class="px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-sm rounded-xl transition-colors inline-block">
                Retour
            </a>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div>
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Demandeur</span>
            <span class="text-sm font-bold text-slate-800">{{ $commande->user->name }}</span>
            <span class="block text-xs text-slate-400 mt-0.5">{{ $commande->user->email }}</span>
        </div>
        <div>
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Pôle / Espace</span>
            <span class="text-sm font-bold text-slate-800">{{ $commande->user->space->name_espace ?? 'N/A' }}</span>
            <span class="block text-xs text-slate-400 mt-0.5">Téléphone : {{ $commande->user->phone ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Date de demande</span>
            <span class="text-sm font-bold text-slate-800">{{ $commande->created_at->format('d F Y à H:i') }}</span>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('magasinier.orders.process', $commande->id) }}" method="POST" id="process-form" class="space-y-6">
        @csrf

        {{-- Products Table Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200/60">
                <h3 class="text-lg font-bold text-slate-800">Détails des articles demandés</h3>
                <p class="text-xs text-slate-400 mt-1">Ajustez la quantité validée en fonction du stock disponible avant de valider.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <th class="px-6 py-4">Nom de l'article</th>
                            <th class="px-6 py-4">Catégorie</th>
                            <th class="px-6 py-4 w-36 text-center">Quantité Demandée</th>
                            <th class="px-6 py-4 w-36 text-center">Stock Actuel</th>
                            <th class="px-6 py-4 w-48 text-center">Quantité Validée <span class="text-red-500">*</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($commande->products as $product)
                            @php
                                // Default validated quantity is capped at min of stock and requested qty
                                $defaultValide = min($product->quantity, $product->pivot->quantite_commander);
                            @endphp
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $product->title }}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $product->category->title }}
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-slate-700">
                                    {{ $product->pivot->quantite_commander }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($product->quantity < 5)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs bg-rose-100 text-rose-700 font-bold">
                                            {{ $product->quantity }} en stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs bg-slate-100 text-slate-700 font-medium">
                                            {{ $product->quantity }} en stock
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center">
                                        <input type="number" 
                                               name="quantities[{{ $product->id }}]" 
                                               value="{{ old('quantities.'.$product->id, $defaultValide) }}" 
                                               min="0" 
                                               max="{{ min($product->quantity, $product->pivot->quantite_commander) }}"
                                               required
                                               class="qty-input w-28 px-3 py-1.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm bg-white text-center font-bold text-slate-800"
                                               data-requested="{{ $product->pivot->quantite_commander }}"
                                               data-stock="{{ $product->quantity }}"
                                               data-title="{{ $product->title }}">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Validation Controls --}}
        <div class="flex items-center justify-end gap-3">
            <button type="submit" name="action" value="refuser"
                    class="px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-100 font-semibold text-sm rounded-xl transition-all duration-200 hover:-translate-y-0.5">
                Refuser la commande
            </button>
            <button type="submit" name="action" value="valider" id="submit-valider"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-600/20 transition-all duration-200 hover:-translate-y-0.5">
                Valider la commande
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qtyInputs = document.querySelectorAll('.qty-input');
        const submitValider = document.getElementById('submit-valider');

        qtyInputs.forEach(input => {
            input.addEventListener('change', function() {
                const val = parseInt(this.value, 10);
                const requested = parseInt(this.getAttribute('data-requested'), 10);
                const stock = parseInt(this.getAttribute('data-stock'), 10);
                const title = this.getAttribute('data-title');

                if (isNaN(val) || val < 0) {
                    alert('Veuillez entrer une quantité valide supérieure ou égale à 0.');
                    this.value = 0;
                    return;
                }

                if (val > requested) {
                    alert(`La quantité validée pour "${title}" ne peut pas dépasser la quantité demandée (${requested}).`);
                    this.value = Math.min(requested, stock);
                } else if (val > stock) {
                    alert(`Le stock disponible pour "${title}" est insuffisant (${stock}).`);
                    this.value = stock;
                }
            });
        });
    });
</script>
@endpush
@endsection
