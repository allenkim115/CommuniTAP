<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CommuniTap – Neighborhood Tasks, Fun Rewards</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700&display=swap" rel="stylesheet" />

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'sans-serif'] },
          colors: {
            primary: '#F3A261',
            dark: '#1B1B18',
            muted: '#706F6C',
            light: '#FDFDFC',
          },
          animation: {
            'fade-up': 'fadeUp 0.6s ease-out forwards',
          },
          keyframes: {
            fadeUp: {
              '0%': { opacity: '0', transform: 'translateY(1rem)' },
              '100%': { opacity: '1', transform: 'translateY(0)' },
            },
          },
        },
      },
    };
  </script>

  <style>
    .hero-illustration { view-transition-name: hero-illustration; }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px -4px rgba(243,162,97,0.25);
    }
  </style>
</head>

<body class="bg-light text-dark min-h-screen flex flex-col antialiased">

  <!-- Header -->
  <header class="absolute inset-x-0 top-0 z-50 px-6 py-5 lg:px-12">
    <nav class="flex justify-end items-center gap-6 text-sm font-medium">
   @auth
        <a href="{{ url('/dashboard') }}" class="text-dark hover:text-primary transition-colors">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="text-dark hover:text-primary transition-colors">Log in</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">Register</a>
        @endif
      @endauth
    </nav>
  </header>

  <!-- Hero Section -->
  <main class="flex-1 grid lg:grid-cols-5 items-center gap-10 px-6 py-20 lg:px-12 lg:py-32 max-w-7xl mx-auto">

    <!-- Left: Text + CTA -->
    <section class="lg:col-span-2 space-y-8 animate-fade-up" style="animation-delay:0.1s;">
      <div>
        <h1 class="text-5xl lg:text-6xl font-bold leading-tight">
          Communi<span class="text-primary">Tap</span>
        </h1>
        <p class="mt-3 text-lg text-muted max-w-md">
          Turn neighborhood chores into fun, rewarding micro-missions.
        </p>
      </div>

      <!-- CTA -->
      <button class="group flex items-center gap-3 px-7 py-4 bg-white border border-gray-300 rounded-xl shadow-sm font-semibold text-dark transition-all btn-primary">
        <svg class="w-6 h-6 text-primary group-hover:animate-pulse"
             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <circle cx="12" cy="12" r="6"/>
          <circle cx="12" cy="12" r="2" fill="currentColor"/>
        </svg>
        <span>Take a Task Now</span>
      </button>

      <!-- Features -->
      <ul class="space-y-4 text-muted">
        <li class="flex items-center gap-3">
          <span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary/10 flex items-center justify-center">
            <svg class="w-3 h-3 text-primary" viewBox="0 0 12 12" fill="none"
                 stroke="currentColor" stroke-width="2">
              <path d="M10 3L4.5 8.5L2 6"/>
            </svg>
          </span>
          Have fun while helping out
        </li>
        <li class="flex items-center gap-3">
          <span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary/10 flex items-center justify-center">
            <svg class="w-3 h-3 text-primary" viewBox="0 0 12 12" fill="none"
                 stroke="currentColor" stroke-width="2">
              <path d="M10 3L4.5 8.5L2 6"/>
            </svg>
          </span>
          Tap → complete → pass it on
        </li>
        <li class="flex items-center gap-3">
          <span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary/10 flex items-center justify-center">
            <svg class="w-3 h-3 text-primary" viewBox="0 0 12 12" fill="none"
                 stroke="currentColor" stroke-width="2">
              <path d="M10 3L4.5 8.5L2 6"/>
            </svg>
          </span>
          Earn real rewards
        </li>
      </ul>
    </section>

    <!-- Right: Illustration -->
    <div class="lg:col-span-3 flex justify-center items-center hero-illustration">
      <div class="relative w-full max-w-md">
        <!-- Primary logo (replace with your real asset) -->
        <img src="images/communitap-logo.svg"
             alt="CommuniTap Logo"
             class="w-full h-auto object-contain"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">

        <!-- Fallback SVG -->
        <svg class="w-full h-auto" style="display:none;" viewBox="0 0 400 400"
             fill="none" xmlns="http://www.w3.org/2000/svg">
          <!-- Orange crescent arc (left) -->
          <path d="M 200 40 A 160 160 0 0 0 200 360" fill="#f97316" stroke="#f97316" stroke-width="12"/>
          <!-- Teal crescent arc (right) -->
          <path d="M 200 40 A 160 160 0 0 1 200 360" fill="#14b8a6" stroke="#14b8a6" stroke-width="12"/>

          <!-- Hand pointing up -->
          <g transform="translate(200,160)">
            <ellipse cx="0" cy="15" rx="18" ry="12" fill="#fbbf24" stroke="#1b1b18" stroke-width="2"/>
            <path d="M 0 3 L 0 -35 L 6 -35 L 6 3 Z" fill="#fbbf24" stroke="#1b1b18" stroke-width="2"/>
            <path d="M -12 3 L -12 -15 L -6 -15 L -6 3 Z" fill="#fbbf24" stroke="#1b1b18" stroke-width="2"/>
            <path d="M 6 3 L 6 -15 L 12 -15 L 12 3 Z" fill="#fbbf24" stroke="#1b1b18" stroke-width="2"/>
            <circle cx="3" cy="-40" r="6" fill="#e5e7eb" stroke="#1b1b18" stroke-width="1.5"/>
          </g>

          <!-- Community figures -->
          <g transform="translate(200,240)">
            <g transform="translate(-50,0)">
              <circle cx="-12" cy="0" r="8" fill="#f97316"/>
              <path d="M -12 8 Q -20 20 -12 28 Q -4 20 -12 8" fill="#f97316"/>
              <circle cx="12" cy="0" r="8" fill="#9ca3af"/>
              <path d="M 12 8 Q 4 20 12 28 Q 20 20 12 8" fill="#9ca3af"/>
            </g>
            <g transform="translate(50,0)">
              <circle cx="-12" cy="0" r="8" fill="#14b8a6"/>
              <path d="M -12 8 Q -20 20 -12 28 Q -4 20 -12 8" fill="#14b8a6"/>
              <circle cx="12" cy="0" r="8" fill="#9ca3af"/>
              <path d="M 12 8 Q 4 20 12 28 Q 20 20 12 8" fill="#9ca3af"/>
            </g>
          </g>
        </svg>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-t border-gray-200 py-6 px-6 lg:px-12 text-center text-sm text-muted">
    © 2025 CommuniTap. All rights reserved.
  </footer>

  <!-- Fade-in on load -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.animate-fade-up').forEach(el => {
        el.style.opacity = '0';
        el.style.animationPlayState = 'running';
      });
    });
  </script>
</body>
</html>