@extends('layouts.app')

@section('title', $product->exists ? 'Modifier un article' : 'Ajouter un article')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">
            {{ $product->exists ? 'Modifier l\'article' : 'Ajouter un nouvel article' }}
        </h2>
        <p class="text-sm text-slate-500">
            Renseignez les détails de l'article ci-dessous pour mettre à jour ou ajouter au catalogue.
        </p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm">
        <form action="{{ $product->exists ? route('magasinier.stock.update', $product->id) : route('magasinier.stock.store') }}" method="POST" class="space-y-5">
            @csrf
            
            {{-- Category selection --}}
            <div>
                <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Catégorie <span class="text-red-500">*</span></label>
                <select name="category_id" id="category_id" required 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm bg-white @error('category_id') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror">
                    <option value="">-- Sélectionner une catégorie --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->title }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-xs font-semibold text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Nom de l'article <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" required value="{{ old('title', $product->title) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('title') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror"
                       placeholder="Ex: Papier A4, Marqueur Bleu">
                @error('title')
                    <p class="text-xs font-semibold text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('description') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror"
                          placeholder="Ex: Rame de papier de 500 feuilles, 80g/m²...">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-xs font-semibold text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Quantity --}}
            <div>
                <label for="quantity" class="block text-sm font-semibold text-slate-700 mb-1.5">Quantité initiale en stock <span class="text-red-500">*</span></label>
                <input type="number" name="quantity" id="quantity" required min="0" value="{{ old('quantity', $product->exists ? $product->quantity : 0) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('quantity') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror">
                @error('quantity')
                    <p class="text-xs font-semibold text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <a href="{{ route('magasinier.stock.index') }}"
                   class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-sm rounded-xl transition-all duration-200">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/20 transition-all duration-200 hover:-translate-y-0.5">
                    {{ $product->exists ? 'Enregistrer les modifications' : 'Ajouter l\'article' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
