<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="CMC - Connexion au système de gestion de stock">

        <title>{{ config('app.name', 'CMC Stock') }} - Connexion</title>

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
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-indigo-50/50 to-purple-50/30 relative overflow-hidden">
            {{-- Background decorative elements --}}
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-indigo-200/40 blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-purple-200/30 blur-3xl"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full bg-blue-100/20 blur-3xl"></div>
            </div>

            <div class="relative z-10 w-full max-w-md mx-4">
                {{-- Logo --}}
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-xl shadow-indigo-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <h1 class="text-xl font-bold text-gray-900">CMC Stock</h1>
                            <p class="text-xs text-gray-500">Gestion de Stock</p>
                        </div>
                    </a>
                </div>

                {{-- Card --}}
                <div class="bg-white rounded-3xl border border-gray-200/80 shadow-2xl shadow-indigo-500/5 p-8">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <p class="text-center text-xs text-gray-400 mt-6">
                    &copy; {{ date('Y') }} CMC - Centre de Management et de Commerce
                </p>
            </div>
        </div>
    </body>
</html>
