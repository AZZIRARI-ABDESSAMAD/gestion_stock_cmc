@extends('layouts.app')

@section('title', $product->exists ? 'Modifier le Produit' : 'Ajouter un Produit')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">{{ $product->exists ? 'Modifier le Produit' : 'Ajouter un Produit' }}</h2>
        <p class="text-sm text-slate-500">Remplissez les informations ci-dessous pour gérer l'article en stock.</p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm">
        <form action="{{ $product->exists ? route('magasinier.stock.update', $product) : route('magasinier.stock.store') }}" method="POST" class="space-y-5">
            @csrf
            
            {{-- Name Field --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nom du produit <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required value="{{ old('name', $product->name) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('name') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror"
                       placeholder="Ex: Stylo Bleu, Papier A4...">
                @error('name')
                    <p class="text-xs font-semibold text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Quantity Field --}}
            <div>
                <label for="quantity" class="block text-sm font-semibold text-slate-700 mb-1.5">Quantité initiale en stock <span class="text-red-500">*</span></label>
                <input type="number" name="quantity" id="quantity" required min="0" value="{{ old('quantity', $product->exists ? $product->quantity : 0) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('quantity') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror"
                       placeholder="Ex: 50">
                @error('quantity')
                    <p class="text-xs font-semibold text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('magasinier.stock.index') }}"
                   class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-sm rounded-xl transition-all duration-200">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/20 transition-all duration-200 hover:-translate-y-0.5">
                    {{ $product->exists ? 'Enregistrer les modifications' : 'Ajouter au stock' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
