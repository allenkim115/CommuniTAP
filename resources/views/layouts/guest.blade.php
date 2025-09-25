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
    <body class="font-sans text-gray-900 antialiased">
        <script>
            // Force light mode immediately
            localStorage.setItem('theme', 'light');
            document.documentElement.classList.remove('dark');
            document.documentElement.style.colorScheme = 'light';
            
            // Override any system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.remove('dark');
            }
        </script>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            @if (!request()->routeIs('login') && !request()->routeIs('register'))
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>
            @endif

            <div class="w-full mt-6 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg {{ request()->routeIs('register') ? 'max-w-4xl px-8 py-8' : 'max-w-sm px-6 py-6' }}">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
