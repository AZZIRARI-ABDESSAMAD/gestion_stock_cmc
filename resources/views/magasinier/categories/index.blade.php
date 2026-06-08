@extends('layouts.app')

@section('title', 'Gestion des Catégories')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Gestion des Catégories</h2>
            <p class="text-sm text-slate-500">Gérez les catégories de produits de l'établissement.</p>
        </div>
        <div>
            <a href="{{ route('magasinier.categories.create') }}" 
               class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-650/15 hover:shadow-indigo-655/25 transition-all duration-200 hover:-translate-y-0.5 inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle Catégorie
            </a>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase font-bold tracking-wider">
                        <th class="px-6 py-4">Nom de la catégorie</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 w-40 text-center">Nombre de Produits</th>
                        <th class="px-6 py-4 w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @if($categories->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                Aucune catégorie trouvée.
                            </td>
                        </tr>
                    @else
                        @foreach($categories as $category)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $category->title }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 max-w-sm truncate">
                                    {{ $category->description ?? 'Aucune description' }}
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-slate-700">
                                    {{ $category->products_count }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('magasinier.categories.edit', $category->id) }}" 
                                           class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors"
                                           title="Modifier">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>

                                        <form action="{{ route('magasinier.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Tous ses produits associés seront supprimés.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
