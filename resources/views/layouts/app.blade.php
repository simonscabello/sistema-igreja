<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
        <div class="flex flex-1 min-h-0">
            @auth
                @include('layouts.sidebar')
            @endauth
            <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 {{ auth()->check() ? 'ml-0 sm:ml-64' : '' }}">
                @include('layouts.navigation')

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="bg-white border-t mt-auto">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        <p class="text-center text-sm text-gray-500">
                            &copy; {{ date('Y') }} Jornada. Todos os direitos reservados.
                        </p>
                    </div>
                </footer>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
