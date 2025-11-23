<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #F3A261;
            --orange: #F3A261;
            --teal: #2B9D8D;
            --peach: #FED2B3;
            --light: #F1F1F1;
            --card-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            margin: 0;
        }

        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, var(--light) 0%, var(--orange) 48%, var(--teal) 96%);
            min-height: 100vh;
        }

        /* Glassmorphic Card */
        .card {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 28px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
        }

        /* Floating Label Inputs */
        .input-group {
            position: relative;
            margin-bottom: 1.75rem;
        }/* Floating Label for Select */
.input-group select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.25em;
    padding-right: 2.5rem;
}

.input-group select:focus ~ label,
.input-group select:not(:placeholder-shown) ~ label,
.input-group select:valid ~ label {
    top: 0.75rem;
    font-size: 0.75rem;
    color: var(--primary);
    font-weight: 600;
}

.input-group select ~ label {
    position: absolute;
    top: 1.25rem;
    left: 1rem;
    font-size: 1rem;
    color: #64748b;
    pointer-events: none;
    transition: all 0.2s ease;
    transform-origin: left;
}

        .input-group input {
            width: 100%;
            padding: 1.25rem 1rem 0.5rem;
            background: transparent;
            border: none;
            border-bottom: 2.5px solid #cbd5e1;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: var(--primary);
        }

        .input-group label {
            position: absolute;
            top: 1.25rem;
            left: 1rem;
            font-size: 1rem;
            color: #64748b;
            pointer-events: none;
            transition: all 0.2s ease;
            transform-origin: left;
        }

        .input-group input:focus ~ label,
        .input-group input:valid ~ label,
        .input-group input:not(:placeholder-shown) ~ label {
            top: 0.5rem;
            font-size: 0.8rem;
            color: var(--primary);
            font-weight: 600;
        }

        /* Primary Button */
        .btn-primary {
            background-color: #F3A261;
            color: white;
            padding: 0.875rem 2.5rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition);
            box-shadow: 0 6px 16px rgba(243, 162, 97, 0.3);
            min-width: 180px;
        }

        .btn-primary:hover {
            background-color: #E8944F;
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(243, 162, 97, 0.4);
        }

        /* Fade In */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
        .logo-container {
        animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
    </style>
</head>

<body class="text-gray-800 antialiased">

    <!-- Force Light Mode -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            localStorage.setItem('theme', 'light');
            document.documentElement.classList.remove('dark');
            document.documentElement.style.colorScheme = 'light';
        });
    </script>

    <!-- Full-Screen Gradient Container -->
<div class="gradient-bg flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-md lg:max-w-lg card p-6 sm:p-8 lg:p-10 animate-fade-in" style="animation-delay: 0.15s;">
        {{ $slot }}
    </div>
    <footer class="mt-10 text-center text-xs text-white/80">
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </footer>
</div>

    <!-- Optional: Confetti on Success -->
    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target.closest('form');
            if (form && form.checkValidity()) {
                setTimeout(() => {
                    if (typeof confetti !== 'undefined') {
                        confetti({
                            particleCount: 100,
                            spread: 70,
                            origin: { y: 0.6 }
                        });
                    }
                }, 600);
            }
        });
    </script>
    
    <!-- Global Alert Modal -->
    <x-alert-modal />
    
    <!-- Global Confirmation Modal -->
    <x-confirmation-modal />
</body>
</html>