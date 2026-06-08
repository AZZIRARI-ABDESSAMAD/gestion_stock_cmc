@extends('layouts.app')

@section('title', 'Nouvelle Demande - Chef de Pôle')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Nouvelle Demande</h2>
        <p class="text-sm text-slate-500">Sélectionnez le nom de l'enseignant concerné et ajoutez les articles demandés.</p>
    </div>

    <form action="{{ route('chef_pole.demandes.store') }}" method="POST" id="demande-form" class="space-y-6">
        @csrf

        {{-- Teacher Card --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm space-y-4">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Informations Enseignant
            </h3>
            
            <div>
                <label for="teacher_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nom complet de l'enseignant <span class="text-red-500">*</span></label>
                <select name="teacher_name" id="teacher_name" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('teacher_name') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror bg-white">
                    <option value="">-- Choisir un enseignant --</option>
                    <option value="M. Alaoui Rachid" {{ old('teacher_name') == 'M. Alaoui Rachid' ? 'selected' : '' }}>M. Alaoui Rachid</option>
                    <option value="M. Bensalah Hamid" {{ old('teacher_name') == 'M. Bensalah Hamid' ? 'selected' : '' }}>M. Bensalah Hamid</option>
                    <option value="M. El Idrissi Ahmed" {{ old('teacher_name') == 'M. El Idrissi Ahmed' ? 'selected' : '' }}>M. El Idrissi Ahmed</option>
                    <option value="Mme. Tazi Fatima" {{ old('teacher_name') == 'Mme. Tazi Fatima' ? 'selected' : '' }}>Mme. Tazi Fatima</option>
                    <option value="M. Amrani Youssef" {{ old('teacher_name') == 'M. Amrani Youssef' ? 'selected' : '' }}>M. Amrani Youssef</option>
                    <option value="Mme. Cherkaoui Sanae" {{ old('teacher_name') == 'Mme. Cherkaoui Sanae' ? 'selected' : '' }}>Mme. Cherkaoui Sanae</option>
                </select>
                @error('teacher_name')
                    <p class="text-xs font-semibold text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Products Picker Card --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm space-y-6">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Sélection des Produits
            </h3>

            {{-- Picker Controls --}}
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end bg-slate-50 p-4 rounded-xl border border-slate-200/40">
                <div class="sm:col-span-7">
                    <label for="product_select" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Produit</label>
                    <select id="product_select" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm bg-white">
                        <option value="">-- Sélectionner un produit --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-stock="{{ $product->quantity }}">
                                {{ $product->name }} (En stock: {{ $product->quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-3">
                    <label for="product_qty" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Quantité</label>
                    <input type="number" id="product_qty" min="1" value="1"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm bg-white">
                </div>
                <div class="sm:col-span-2">
                    <button type="button" id="btn-add-product"
                            class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-lg shadow-md shadow-indigo-600/10 transition-all duration-200 hover:-translate-y-0.5">
                        Ajouter
                    </button>
                </div>
            </div>

            {{-- Selected Products Table --}}
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-left border-collapse" id="products-table">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <th class="px-6 py-3">Produit</th>
                            <th class="px-6 py-3 w-32">Disponibilité</th>
                            <th class="px-6 py-3 w-36">Quantité Demandée</th>
                            <th class="px-6 py-3 w-20 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm" id="selected-products-tbody">
                        <tr id="empty-row" class="text-slate-400 text-center">
                            <td colspan="4" class="px-6 py-8">Aucun produit ajouté pour le moment.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @error('products')
                <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Form Controls --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('chef_pole.dashboard') }}"
               class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-sm rounded-xl transition-all duration-200">
                Annuler
            </a>
            <button type="submit" id="submit-demande"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/20 transition-all duration-200 hover:-translate-y-0.5">
                Soumettre la demande
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnAdd = document.getElementById('btn-add-product');
        const selectProd = document.getElementById('product_select');
        const inputQty = document.getElementById('product_qty');
        const tbody = document.getElementById('selected-products-tbody');
        const emptyRow = document.getElementById('empty-row');
        
        let rowCount = 0;
        const addedProductIds = new Set();

        btnAdd.addEventListener('click', function() {
            const prodId = selectProd.value;
            if (!prodId) {
                alert('Veuillez sélectionner un produit.');
                return;
            }

            if (addedProductIds.has(prodId)) {
                alert('Ce produit a déjà été ajouté.');
                return;
            }

            const qty = parseInt(inputQty.value, 10);
            if (isNaN(qty) || qty <= 0) {
                alert('Veuillez spécifier une quantité valide (> 0).');
                return;
            }

            const option = selectProd.options[selectProd.selectedIndex];
            const name = option.getAttribute('data-name');
            const stock = option.getAttribute('data-stock');

            // Hide empty row
            if (emptyRow) {
                emptyRow.style.display = 'none';
            }

            // Create new row
            const tr = document.createElement('tr');
            tr.id = `row-${prodId}`;
            tr.className = 'hover:bg-slate-50/50 transition-colors';
            
            tr.innerHTML = `
                <td class="px-6 py-4">
                    <span class="font-medium text-slate-800">${name}</span>
                    <input type="hidden" name="products[${rowCount}][id]" value="${prodId}">
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs bg-slate-100 text-slate-700 font-medium">
                        ${stock} en stock
                    </span>
                </td>
                <td class="px-6 py-4">
                    <input type="number" name="products[${rowCount}][quantite_demandee]" value="${qty}" min="1" required
                           class="w-24 px-3 py-1.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm bg-white">
                </td>
                <td class="px-6 py-4 text-center">
                    <button type="button" class="btn-remove-row p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" data-id="${prodId}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
            addedProductIds.add(prodId);
            rowCount++;

            // Reset picker
            selectProd.value = '';
            inputQty.value = 1;

            // Bind remove event
            tr.querySelector('.btn-remove-row').addEventListener('click', function() {
                const idToRemove = this.getAttribute('data-id');
                document.getElementById(`row-${idToRemove}`).remove();
                addedProductIds.delete(idToRemove);

                if (addedProductIds.size === 0) {
                    emptyRow.style.display = '';
                }
            });
        });
    });
</script>
@endpush
@endsection
