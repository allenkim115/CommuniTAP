<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/communitaplogo1.svg') }}">
  <title>CommuniTAP</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

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
            'fade-in': 'fadeIn 0.8s ease-out forwards',
            'logo-bounce': 'logoBounce 1.2s ease-out forwards',
            'logo-float': 'logoFloat 3s ease-in-out infinite',
            'logo-pulse': 'logoPulse 2s ease-in-out infinite',
            'slide-up': 'slideUp 0.8s ease-out forwards',
            'scale-in': 'scaleIn 0.6s ease-out forwards',
            'shimmer': 'shimmer 2s linear infinite',
          },
          keyframes: {
            fadeUp: {
              '0%': { opacity: '0', transform: 'translateY(1rem)' },
              '100%': { opacity: '1', transform: 'translateY(0)' },
            },
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            logoBounce: {
              '0%': { opacity: '0', transform: 'translateY(-2rem) scale(0.8)' },
              '50%': { transform: 'translateY(0.5rem) scale(1.05)' },
              '70%': { transform: 'translateY(-0.2rem) scale(1)' },
              '100%': { opacity: '1', transform: 'translateY(0) scale(1)' },
            },
            logoFloat: {
              '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
              '50%': { transform: 'translateY(-10px) rotate(2deg)' },
            },
            logoPulse: {
              '0%, 100%': { transform: 'scale(1)', filter: 'drop-shadow(0 0 0px rgba(243,162,97,0))' },
              '50%': { transform: 'scale(1.05)', filter: 'drop-shadow(0 0 20px rgba(243,162,97,0.3))' },
            },
            slideUp: {
              '0%': { opacity: '0', transform: 'translateY(2rem)' },
              '100%': { opacity: '1', transform: 'translateY(0)' },
            },
            scaleIn: {
              '0%': { opacity: '0', transform: 'scale(0.9)' },
              '100%': { opacity: '1', transform: 'scale(1)' },
            },
            shimmer: {
              '0%': { backgroundPosition: '-200% 0' },
              '100%': { backgroundPosition: '200% 0' },
            },
          },
        },
      },
    };
  </script>

  <style>
  html {
    /* Match the main page background so overscroll / bounce areas don't show pure white */
    background-color: #FDFDFC;
  }
  .btn-primary {
      position: relative;
      overflow: hidden;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px -4px rgba(243,162,97,0.25);
    }
    .btn-primary::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }
    .btn-primary:hover::before {
      width: 300px;
      height: 300px;
    }
    .btn-primary span {
      position: relative;
      z-index: 1;
    }
    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(243,162,97,0.08), transparent);
      transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .feature-card:hover::before {
      left: 100%;
    }
    .feature-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px -8px rgba(243,162,97,0.15), 0 0 0 1px rgba(243,162,97,0.1);
    }
    .logo-container {
      position: relative;
      display: inline-block;
    }
    .logo-container img,
    .logo-container svg {
      transition: transform 0.3s ease;
    }
    .logo-container:hover img,
    .logo-container:hover svg {
      transform: scale(1.1) rotate(5deg);
    }
    .badge {
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }
    .badge:hover {
      transform: translateY(-2px) scale(1.05);
      box-shadow: 0 4px 12px rgba(243,162,97,0.2);
      background: rgba(243,162,97,0.15) !important;
    }
    .feature-card {
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
    }
    .feature-card::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #F3A261 0%, #2B9D8D 100%);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .feature-card:hover::after {
      transform: scaleX(1);
    }
    .feature-icon-wrapper {
      position: relative;
      background: linear-gradient(135deg, rgba(243,162,97,0.1) 0%, rgba(43,157,141,0.1) 100%);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .feature-card:hover .feature-icon-wrapper {
      background: linear-gradient(135deg, rgba(243,162,97,0.2) 0%, rgba(43,157,141,0.2) 100%);
      transform: scale(1.05);
    }
    .feature-card:hover .feature-icon-wrapper svg {
      transform: scale(1.1);
    }
    .feature-icon-wrapper svg {
      transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    header {
      /* Very subtle glassy background: visible enough for readability, but almost invisible */
      backdrop-filter: blur(10px);
      background: rgba(253, 253, 252, 0.4);
    }
    @keyframes gradient-shift {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }
    .gradient-text {
      background: linear-gradient(135deg, #F3A261 0%, #E76F51 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      background-size: 200% 200%;
      animation: gradient-shift 3s ease infinite;
    }
  </style>
</head>

<body class="bg-light text-dark min-h-screen flex flex-col antialiased">

  <!-- Header -->
  <header class="fixed inset-x-0 top-0 z-50 px-6 py-5 lg:px-12 animate-fade-in">
    <nav class="flex justify-between items-center">
      <div class="flex items-center gap-6 text-sm font-medium ml-auto">
        @auth
          <a href="{{ url('/dashboard') }}" class="text-dark hover:text-primary transition-all duration-300 hover:scale-105">Dashboard</a>
        @else
          <a href="{{ route('login') }}" class="text-dark hover:text-primary transition-all duration-300 hover:scale-105">Log in</a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 transition-all duration-300 font-semibold hover:scale-105">
              Register
            </a>
          @endif
        @endauth
      </div>
    </nav>
  </header>

  <!-- Hero Section -->
  <main class="flex-1 pt-44 lg:pt-32 pb-20 px-6 lg:px-12">
    
    <!-- Hero Content -->
    <section class="max-w-4xl mx-auto text-center space-y-8 animate-fade-up" style="animation-delay:0.1s;">
      <div class="space-y-6">
        <div class="flex justify-center mb-4">
          <div class="logo-container animate-logo-bounce">
            <img src="{{ asset('images/communitaplogo1.svg') }}"
                 alt="CommuniTap Logo"
                 class="h-28 lg:h-40 w-auto object-contain animate-logo-float"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <svg class="h-28 lg:h-40 w-auto animate-logo-float" style="display:none;" viewBox="0 0 400 400"
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
        <h1 class="text-5xl lg:text-7xl font-bold leading-tight animate-fade-up" style="animation-delay:0.3s;">
          Experience the future of<br />
          <span class="text-primary gradient-text">community engagement</span>
        </h1>
        <p class="text-xl text-muted max-w-2xl mx-auto leading-relaxed animate-fade-up" style="animation-delay:0.4s;">
          Turn neighborhood chores into fun, rewarding micro-missions. 
          Help your community while earning real rewards.
        </p>
      </div>

      <!-- CTA Button -->
      <div class="pt-4 animate-fade-up" style="animation-delay:0.5s;">
        @auth
          <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-primary text-white rounded-xl font-semibold text-lg transition-all btn-primary shadow-lg">
            <span>Go to Dashboard</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </a>
        @else
          <a href="{{ route('login') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-primary text-white rounded-xl font-semibold text-lg transition-all btn-primary shadow-lg">
            <span>Join the Community</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </a>
        @endauth
      </div>

      <!-- Feature Badges -->
      <div class="flex flex-wrap justify-center gap-3 sm:gap-4 pt-8 animate-fade-up" style="animation-delay:0.4s;">
        <span class="badge px-5 py-2.5 bg-primary/10 text-primary rounded-full text-sm font-semibold cursor-default shadow-sm flex items-center gap-2" style="animation-delay:0.5s;">
          <i class="fas fa-users"></i>
          <span>Community-Focused</span>
        </span>
        <span class="badge px-5 py-2.5 bg-primary/10 text-primary rounded-full text-sm font-semibold cursor-default shadow-sm flex items-center gap-2" style="animation-delay:0.6s;">
          <i class="fas fa-gift"></i>
          <span>Fun & Rewarding</span>
        </span>
        <span class="badge px-5 py-2.5 bg-primary/10 text-primary rounded-full text-sm font-semibold cursor-default shadow-sm flex items-center gap-2" style="animation-delay:0.7s;">
          <i class="fas fa-rocket"></i>
          <span>Easy to Use</span>
        </span>
        <span class="badge px-5 py-2.5 bg-primary/10 text-primary rounded-full text-sm font-semibold cursor-default shadow-sm flex items-center gap-2" style="animation-delay:0.8s;">
          <i class="fas fa-award"></i>
          <span>Real Rewards</span>
        </span>
      </div>
    </section>

    <!-- Explore Our Features Section -->
    <section class="max-w-6xl mx-auto mt-32 lg:mt-40 px-4 sm:px-6">
      <div class="text-center mb-16 lg:mb-20 animate-slide-up" style="animation-delay:0.3s;">
        <div class="inline-block mb-4">
          <span class="px-4 py-1.5 bg-primary/10 text-primary rounded-full text-xs font-semibold tracking-wide uppercase">
            Features
          </span>
        </div>
        <h2 class="text-4xl lg:text-5xl xl:text-6xl font-bold mb-5 leading-tight">
          Explore Our <span class="gradient-text">Features</span>
        </h2>
        <p class="text-lg lg:text-xl text-muted max-w-2xl mx-auto leading-relaxed">
          Everything you need to make community engagement fun and rewarding
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
        <!-- Feature 1: Tasks -->
        <div class="feature-card bg-white rounded-3xl p-8 lg:p-10 shadow-lg border border-gray-100/50 transition-all animate-scale-in group" style="animation-delay:0.5s;">
          <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl flex items-center justify-center mb-6 feature-icon-wrapper">
            <svg class="w-8 h-8 lg:w-10 lg:h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
          </div>
          <h3 class="text-2xl lg:text-3xl font-bold mb-4 text-gray-900 group-hover:text-primary transition-colors duration-300">Tasks</h3>
          <p class="text-muted leading-relaxed text-base lg:text-lg">
            Browse, create, and complete neighborhood tasks. Join daily tasks, one-time projects, 
            or propose your own tasks. Track your progress and submit proof of completion.
          </p>
        </div>

        <!-- Feature 2: Rewards -->
        <div class="feature-card bg-white rounded-3xl p-8 lg:p-10 shadow-lg border border-gray-100/50 transition-all animate-scale-in group" style="animation-delay:0.6s;">
          <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl flex items-center justify-center mb-6 feature-icon-wrapper">
            <svg class="w-8 h-8 lg:w-10 lg:h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
            </svg>
          </div>
          <h3 class="text-2xl lg:text-3xl font-bold mb-4 text-gray-900 group-hover:text-primary transition-colors duration-300">Rewards</h3>
          <p class="text-muted leading-relaxed text-base lg:text-lg">
            Earn points by completing tasks and redeem them for real rewards. 
            Track your redemptions and build your reputation as a community contributor.
          </p>
        </div>

        <!-- Feature 3: Tap & Pass -->
        <div class="feature-card bg-white rounded-3xl p-8 lg:p-10 shadow-lg border border-gray-100/50 transition-all animate-scale-in group" style="animation-delay:0.7s;">
          <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl flex items-center justify-center mb-6 feature-icon-wrapper">
            <i class="fas fa-handshake text-primary text-3xl lg:text-4xl"></i>
          </div>
          <h3 class="text-2xl lg:text-3xl font-bold mb-4 text-gray-900 group-hover:text-primary transition-colors duration-300">Tap & Pass</h3>
          <p class="text-muted leading-relaxed text-base lg:text-lg">
            After completing daily tasks, nominate neighbors to continue the chain. 
            Share feedback on tasks and report incidents to keep the community safe and engaged.
          </p>
        </div>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer class="border-t border-gray-200 py-8 px-6 lg:px-12 text-center text-sm text-muted animate-fade-in" style="animation-delay:0.8s;">
    <p>© 2025 CommuniTap. All rights reserved.</p>
  </footer>

  <!-- Enhanced animations on load -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Initialize fade-up animations
      document.querySelectorAll('.animate-fade-up').forEach(el => {
        el.style.opacity = '0';
        el.style.animationPlayState = 'running';
      });
      
      // Initialize slide-up animations
      document.querySelectorAll('.animate-slide-up').forEach(el => {
        el.style.opacity = '0';
        el.style.animationPlayState = 'running';
      });
      
      // Initialize scale-in animations
      document.querySelectorAll('.animate-scale-in').forEach(el => {
        el.style.opacity = '0';
        el.style.animationPlayState = 'running';
      });
      
      // Add scroll-triggered animations for feature cards
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.animationPlayState = 'running';
          }
        });
      }, observerOptions);
      
      document.querySelectorAll('.feature-card').forEach(card => {
        observer.observe(card);
      });
      
      // Add parallax effect to logo on scroll
      let lastScroll = 0;
      window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        const logo = document.querySelector('.logo-container');
        if (logo) {
          const parallaxValue = scrollY * 0.3;
          logo.style.transform = `translateY(${Math.min(parallaxValue, 50)}px)`;
        }
        lastScroll = scrollY;
      }, { passive: true });
    });
  </script>
</body>
</html>
