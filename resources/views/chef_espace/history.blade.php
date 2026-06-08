@extends('layouts.app')

@section('title', 'Historique des Commandes - Chef Espace')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Historique des Commandes</h2>
        <p class="text-sm text-slate-500">Consultez l'état de l'ensemble de vos commandes passées et leurs détails.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase font-bold tracking-wider">
                        <th class="px-6 py-4">Référence</th>
                        <th class="px-6 py-4">Date de Commande</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4">Articles & Quantités</th>
                        <th class="px-6 py-4 text-right">Total Articles</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @if($commandes->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                Aucune commande trouvée dans votre historique.
                            </td>
                        </tr>
                    @else
                        @foreach($commandes as $commande)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $commande->reference }}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $commande->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($commande->isOnCours())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            En cours
                                        </span>
                                    @elseif($commande->isValidated())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Validée
                                        </span>
                                    @elseif($commande->isShipped())
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                Expédiée
                                            </span>
                                            <form action="{{ route('chef_espace.commandes.status', $commande->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold rounded-lg transition-all shadow-sm hover:-translate-y-0.5 duration-100">
                                                    Reçue ✓
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($commande->isDelivered())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            Livrée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Refusée
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1.5 max-w-md">
                                        @foreach($commande->products as $product)
                                            <div class="flex items-center justify-between text-xs border-b border-slate-100 pb-1 last:border-0 last:pb-0">
                                                <span class="text-slate-700 font-medium">{{ $product->title }}</span>
                                                <span class="text-slate-500 font-semibold">
                                                    Demandé: {{ $product->pivot->quantite_commander }} 
                                                    @if($commande->isValidated() || $commande->isShipped() || $commande->isDelivered())
                                                        | Validé: <span class="text-emerald-600 font-bold">{{ $product->pivot->quantite_valide ?? 0 }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-700">
                                    {{ $commande->products->count() }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        @if($commandes->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $commandes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
