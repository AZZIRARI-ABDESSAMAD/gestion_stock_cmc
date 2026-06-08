<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="CMC - Système de Gestion de Stock Interne pour établissement éducatif">

        <title>{{ config('app.name', 'CMC Stock') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex bg-gray-50">
            {{-- ─── Sidebar ─── --}}
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-white flex flex-col shadow-2xl -translate-x-full">
                {{-- Logo / Brand --}}
                <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-700/50">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight">CMC Stock</h1>
                        <p class="text-xs text-slate-400">Gestion de Stock</p>
                    </div>
                </div>

                {{-- User Info Badge --}}
                <div class="px-4 py-3 mx-3 mt-4 rounded-xl bg-slate-700/40 border border-slate-600/30">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/20">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400">
                                @if(Auth::user()->isMagasinier())
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        Magasinier
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                        Chef de Pôle
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 mt-4 px-3 space-y-1 overflow-y-auto">
                    <p class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Navigation</p>

                    @if(Auth::user()->isMagasinier())
                        {{-- Magasinier Navigation --}}
                        <a href="{{ route('magasinier.dashboard') }}" id="nav-dashboard"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->routeIs('magasinier.dashboard') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Tableau de bord
                        </a>

                        <a href="{{ route('magasinier.stock.index') }}" id="nav-stock"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->routeIs('magasinier.stock*') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Produits (Stock)
                        </a>

                        <a href="{{ route('magasinier.demandes.index') }}" id="nav-demandes"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->routeIs('magasinier.demandes*') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Demandes de stock
                        </a>

                        <a href="{{ route('magasinier.history') }}" id="nav-history"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->routeIs('magasinier.history') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Historique global
                        </a>
                    @else
                        {{-- Chef de Pôle Navigation --}}
                        <a href="{{ route('chef_pole.dashboard') }}" id="nav-dashboard"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->routeIs('chef_pole.dashboard') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Tableau de bord
                        </a>

                        <a href="{{ route('chef_pole.demandes.create') }}" id="nav-new-demande"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->routeIs('chef_pole.demandes.create') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Nouvelle Demande
                        </a>

                        <a href="{{ route('chef_pole.history') }}" id="nav-history"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->routeIs('chef_pole.history') ? 'bg-indigo-600/80 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Mon Historique
                        </a>
                    @endif
                </nav>

                {{-- Logout --}}
                <div class="px-3 py-4 border-t border-slate-700/50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" id="logout-button"
                                class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </aside>

            {{-- ─── Main Content Area ─── --}}
            <div class="flex-1 flex flex-col min-h-screen">
                {{-- Top Bar (mobile menu toggle) --}}
                <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-xl border-b border-gray-200/60 lg:hidden">
                    <div class="flex items-center justify-between px-4 py-3">
                        <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')"
                                class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors" id="mobile-menu-toggle">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <span class="font-bold text-gray-800">CMC Stock</span>
                        </div>
                        <div class="w-10"></div>
                    </div>
                </header>

                {{-- Page Content --}}
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="mb-6 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-3 animate-fade-in" id="flash-success">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 animate-fade-in" id="flash-error">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        {{-- Sidebar overlay for mobile --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-20 bg-black/50 hidden lg:hidden"
             onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); this.classList.add('hidden')"></div>

        <script>
            // Auto-dismiss flash messages after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('#flash-success, #flash-error').forEach(el => {
                    el.style.transition = 'opacity 0.5s';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                });
            }, 5000);
        </script>

        @stack('scripts')
    </body>
</html>
