@extends('layouts.app')

@section('title', $category->exists ? 'Modifier la catégorie' : 'Créer une catégorie')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">
            {{ $category->exists ? 'Modifier la catégorie' : 'Créer une nouvelle catégorie' }}
        </h2>
        <p class="text-sm text-slate-500">
            Définissez le titre et la description de la catégorie d'articles.
        </p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm">
        <form action="{{ $category->exists ? route('magasinier.categories.update', $category->id) : route('magasinier.categories.store') }}" method="POST" class="space-y-5">
            @csrf
            
            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Titre de la catégorie <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" required value="{{ old('title', $category->title) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('title') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror"
                       placeholder="Ex: Fournitures Administratives, Matériel Informatique">
                @error('title')
                    <p class="text-xs font-semibold text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('description') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror"
                          placeholder="Ex: Papiers, stylos, agrafeuses, classeurs...">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-xs font-semibold text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <a href="{{ route('magasinier.categories.index') }}"
                   class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-sm rounded-xl transition-all duration-200">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/20 transition-all duration-200 hover:-translate-y-0.5">
                    {{ $category->exists ? 'Modifier' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
