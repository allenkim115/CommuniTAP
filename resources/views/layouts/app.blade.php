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
        
        <!-- Force Light Mode CSS Override -->
        <style>
            /* Force light mode with CSS overrides */
            html, body { 
                background-color: #f9fafb !important; 
                color: #111827 !important;
            }
            
            /* Override all dark mode classes */
            .dark, .dark * { 
                background-color: inherit !important; 
                color: inherit !important; 
            }
            
            .dark\:bg-gray-900 { background-color: #f9fafb !important; }
            .dark\:bg-gray-800 { background-color: #ffffff !important; }
            .dark\:bg-gray-700 { background-color: #f3f4f6 !important; }
            .dark\:text-gray-200 { color: #111827 !important; }
            .dark\:text-gray-400 { color: #6b7280 !important; }
            .dark\:text-gray-500 { color: #6b7280 !important; }
            .dark\:border-gray-700 { border-color: #d1d5db !important; }
            .dark\:border-gray-800 { border-color: #e5e7eb !important; }
            .dark\:hover\:bg-gray-700:hover { background-color: #f3f4f6 !important; }
            .dark\:hover\:border-gray-600:hover { border-color: #9ca3af !important; }
            
            /* Override all text colors to ensure they're dark */
            .dark\:text-white { color: #1f2937 !important; }
            .dark\:text-gray-100 { color: #111827 !important; }
            .dark\:text-gray-200 { color: #111827 !important; }
            .dark\:text-gray-300 { color: #374151 !important; }
            .dark\:text-gray-400 { color: #6b7280 !important; }
            .dark\:text-gray-500 { color: #6b7280 !important; }
            .dark\:text-gray-600 { color: #4b5563 !important; }
            .dark\:text-gray-700 { color: #374151 !important; }
            .dark\:text-gray-800 { color: #1f2937 !important; }
            .dark\:text-gray-900 { color: #111827 !important; }
            
            /* Override any remaining white text */
            .text-white { color: #1f2937 !important; }
            .text-gray-100 { color: #111827 !important; }
            .text-gray-200 { color: #111827 !important; }
            .text-gray-300 { color: #374151 !important; }
            
            /* Force all elements to have dark text */
            * { color: #1f2937 !important; }
            h1, h2, h3, h4, h5, h6 { color: #111827 !important; }
            p, span, div, a, li, td, th { color: #374151 !important; }
        </style>
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
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
