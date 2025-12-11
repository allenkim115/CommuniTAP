<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/communitaplogo1.svg') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Force Light Mode CSS Override -->
        <style>
            /* Force light mode with CSS overrides */
            html, body { 
                background-color: #f6f7fb !important; 
                color: #111827 !important;
            }
            
            /* Override all dark mode classes */
            .dark, .dark * { 
                background-color: inherit !important; 
                color: inherit !important; 
            }
            
            .dark\:bg-gray-900 { background-color: #f6f7fb !important; }
            .dark\:bg-gray-800 { background-color: #ffffff !important; }
            .dark\:bg-gray-700 { background-color: #f3f4f6 !important; }
            .dark\:text-gray-200 { color: #111827 !important; }
            .dark\:text-gray-400 { color: #6b7280 !important; }
            .dark\:text-gray-500 { color: #6b7280 !important; }
            .dark\:border-gray-700 { border-color: #d1d5db !important; }
            .dark\:border-gray-800 { border-color: #e5e7eb !important; }
            .dark\:hover\:bg-gray-700:hover { background-color: #f3f4f6 !important; }
            .dark\:hover\:border-gray-600:hover { border-color: #9ca3af !important; }
            
            /* Note: Avoid overriding base text utility colors so icons keep their color */
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
        <div class="min-h-screen pt-16" style="background-color:#f6f7fb">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="shadow bg-transparent">
                    <div class="page-heading max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
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
