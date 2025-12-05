<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/communitaplogo1.svg') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} — Admin</title>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js for interactive components -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased">
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
        <div class="min-h-screen" style="background-color:#f1f1f1">
            @include('layouts.partials.admin-navigation')

            @isset($header)
                <header class="shadow" style="background-color:#f1f1f1">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>
        
        <!-- Toast Notifications -->
        <x-session-toast />
        
        <!-- Global Alert Modal -->
        <x-alert-modal />
        
        <!-- Global Confirmation Modal -->
        <x-confirmation-modal />

        @stack('scripts')
    </body>
</html>
