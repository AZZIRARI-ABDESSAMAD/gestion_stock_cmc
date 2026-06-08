<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50/50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="CMC - Système de Gestion de Stock Interne pour établissement éducatif">

    <title>{{ config('app.name', 'CMC Stock') }} - @yield('title', 'Gestion')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="h-full text-slate-600 antialiased bg-slate-50/50">
    <div class="min-h-full flex flex-col md:flex-row">
        {{-- Sidebar --}}
        <aside class="w-full md:w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col border-r border-slate-800 shadow-xl">
            {{-- Sidebar Header --}}
            <div class="px-6 py-5 flex items-center justify-between border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-white tracking-wide">CMC Stock</h1>
                        <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">
                            {{ auth()->user()->role === 'magasinier' ? 'Magasinier' : 'Chef Espace' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Sidebar Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                @if(auth()->user()->isMagasinier())
                    {{-- Magasinier Navigation --}}
                    <a href="{{ route('magasinier.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('magasinier.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                        Tableau de bord
                    </a>

                    <a href="{{ route('magasinier.stock.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('magasinier.stock.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Stock & Produits
                    </a>

                    <a href="{{ route('magasinier.categories.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('magasinier.categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Catégories
                    </a>

                    <a href="{{ route('magasinier.orders.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium relative transition-all {{ request()->routeIs('magasinier.orders.*') && !request()->routeIs('magasinier.orders.history') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Gestion Commandes
                    </a>

                    <a href="{{ route('magasinier.orders.history') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('magasinier.orders.history') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Historique Global
                    </a>
                @else
                    {{-- Chef de l'espace Navigation --}}
                    <a href="{{ route('chef_espace.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('chef_espace.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                        Tableau de bord
                    </a>

                    <a href="{{ route('chef_espace.commandes.create') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('chef_espace.commandes.create') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nouvelle Commande
                    </a>

                    <a href="{{ route('chef_espace.history') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('chef_espace.history') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Mon Historique
                    </a>
                @endif
            </nav>

            {{-- Sidebar Footer --}}
            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <div class="flex items-center gap-3 px-2 mb-3">
                    <div class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center text-sm font-bold text-white uppercase">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-800/50 hover:bg-red-950/20 hover:text-red-400 text-xs font-bold rounded-xl transition-all border border-slate-850">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Page Wrap --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top Navbar --}}
            <header class="h-16 bg-white border-b border-slate-200/60 flex items-center justify-between px-8 shadow-sm">
                <div class="flex items-center gap-4">
                    <h2 class="text-base font-bold text-slate-800">
                        @if(auth()->user()->isChefEspace() && auth()->user()->space)
                            Pôle : <span class="text-indigo-655 font-extrabold">{{ auth()->user()->space->name_espace }}</span>
                        @else
                            Système de Gestion de Stock CMC
                        @endif
                    </h2>
                </div>
                <div class="text-xs font-medium text-slate-400">
                    Aujourd'hui : {{ now()->format('d M Y') }}
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 p-8 overflow-y-auto">
                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-medium shadow-sm flex items-start gap-3 animate-fade-in">
                        <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-800 text-sm font-medium shadow-sm flex items-start gap-3 animate-fade-in">
                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
