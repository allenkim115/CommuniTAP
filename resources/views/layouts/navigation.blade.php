<nav x-data="{ open: false }" class="fixed inset-x-0 top-0 z-50 bg-white/70 backdrop-blur border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo on the left -->
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/communitaplogo1.svg') }}" alt="CommuniTAP Logo" class="block h-16 w-auto" />
                    </a>
                </div>
            </div>

            <!-- Centered Navigation Links -->
            <div class="flex-1 hidden sm:flex justify-center pl-8">
                <div class="space-x-8 sm:-my-px flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5l9-7.5 9 7.5"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11v10h6v-6h2v6h6V11"></path>
                        </svg>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5.5V4.5A1.5 1.5 0 0110.5 3h3A1.5 1.5 0 0115 4.5v1"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8a2 2 0 012 2v9a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 12.5l1.75 1.75L15 11"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9.5h4M10 16h4"></path>
                        </svg>
                        {{ __('Tasks') }}
                    </x-nav-link>
                    
                    @php
                        $pendingNominationsCount = Auth::user() ? 
                            \App\Models\TapNomination::where('FK3_nomineeId', Auth::user()->userId)
                            ->where('status', 'pending')
                            ->count() : 0;

                        $notificationSummary = $notificationSummary ?? [
                            'unreadCount' => 0,
                            'recentNotifications' => collect(),
                        ];

                        $unreadNotificationsCount = $notificationSummary['unreadCount'];
                        $recentNotifications = $notificationSummary['recentNotifications'];
                        $profilePictureUrl = Auth::user()?->profile_picture_url;
                    @endphp
                    
                    <x-nav-link :href="route('tap-nominations.index')" :active="request()->routeIs('tap-nominations.*')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4h8v5.5a4 4 0 01-4 4 4 4 0 01-4-4V4z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v1a3 3 0 003 3h1"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4h2a2 2 0 012 2v1a3 3 0 01-3 3h-1"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 13.5v1.75A2.75 2.75 0 0012.75 18h.5A2.75 2.75 0 0016 15.25V13.5"></path>
                        </svg>
                        {{ __('Nominations') }}
                        @if($pendingNominationsCount > 0)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                {{ $pendingNominationsCount }}
                            </span>
                        @endif
                    </x-nav-link>
                    
                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.tap-nominations.task-chain')" :active="request()->routeIs('admin.tap-nominations.task-chain')">
                            🔗 {{ __('Task Chain') }}
                        </x-nav-link>
                    @endif
                    
                    <x-nav-link :href="route('progress')" :active="request()->routeIs('progress')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V9"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V6"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16v-4"></path>
                        </svg>
                        {{ __('Progress') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.*') || request()->routeIs('rewards.mine')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 8H3.75A.75.75 0 003 8.75V18a2 2 0 002 2h14a2 2 0 002-2V8.75a.75.75 0 00-.75-.75z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v12"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8s-1.5-3-3.5-3S6 6.25 6 7.5 7.25 10 9 10h3"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8s1.5-3 3.5-3S18 6.25 18 7.5 16.75 10 15 10h-3"></path>
                        </svg>
                        {{ __('Rewards') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('feedback.index')" :active="request()->routeIs('feedback.*')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 11.5c0 4.142-3.806 7.5-8.5 7.5-.964 0-1.897-.128-2.769-.362L6 21v-3.612C4.182 15.938 3 13.861 3 11.5 3 7.358 6.806 4 11.5 4S21 7.358 21 11.5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5h.01M11.5 11.5h.01M14.5 11.5h.01"></path>
                        </svg>
                        {{ __('Feedbacks') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->isAdmin() && request()->is('admin*'))
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.tasks.index')" :active="request()->routeIs('admin.tasks.*')">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            {{ __('Tasks') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.task-submissions.index')" :active="request()->routeIs('admin.task-submissions.*')">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('Submissions') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.incident-reports.index')" :active="request()->routeIs('admin.incident-reports.*')">
                            🚨 {{ __('Incident Reports') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- User Menu, Notifications, and Hamburger -->
            <div class="flex items-center space-x-4">
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-2">
                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <!-- User Avatar -->
                                <div class="h-8 w-8 rounded-full flex items-center justify-center mr-2 overflow-hidden bg-gray-300 dark:bg-gray-600">
                                    @if($profilePictureUrl)
                                        <img src="{{ $profilePictureUrl }}" alt="{{ Auth::user()->firstName }}'s avatar" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ substr(Auth::user()->firstName, 0, 1) }}{{ substr(Auth::user()->lastName, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>{{ Auth::user()->firstName }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('incident-reports.index')">
                                {{ __('Report User') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" novalidate>
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>

                    <!-- Notifications Dropdown -->
                    <x-dropdown align="right" width="96" offset="right: -8rem;">
                        <x-slot name="trigger">
                            <button class="relative inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                                @if($unreadNotificationsCount > 0)
                                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-medium leading-none text-white rounded-full" style="background-color: #2B9D8D;">
                                        {{ $unreadNotificationsCount }}
                                    </span>
                                @endif
                    </button>
                        </x-slot>
                        <x-slot name="content">
                            @include('layouts.partials.notification-dropdown', [
                                'unreadCount' => $unreadNotificationsCount,
                                'notifications' => $recentNotifications,
                            ])
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                {{ __('Tasks') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('tap-nominations.index')" :active="request()->routeIs('tap-nominations.*')">
                {{ __('Nominations') }}
                @if($pendingNominationsCount > 0)
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $pendingNominationsCount }}
                    </span>
                @endif
            </x-responsive-nav-link>
            
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.tap-nominations.task-chain')" :active="request()->routeIs('admin.tap-nominations.task-chain')">
                    🔗 {{ __('Task Chain') }}
                </x-responsive-nav-link>
            @endif
            
            <x-responsive-nav-link :href="route('progress')" :active="request()->routeIs('progress')">
                {{ __('Progress') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.*') || request()->routeIs('rewards.mine')">
                {{ __('Rewards') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                {{ __('Notifications') }}
                @if($unreadNotificationsCount > 0)
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $unreadNotificationsCount }}
                    </span>
                @endif
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('feedback.index')" :active="request()->routeIs('feedback.*')">
                {{ __('Feedbacks') }}
            </x-responsive-nav-link>
            
            @if(Auth::user()->isAdmin() && request()->is('admin*'))
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.tasks.index')" :active="request()->routeIs('admin.tasks.*')">
                    {{ __('Tasks') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.task-submissions.index')" :active="request()->routeIs('admin.task-submissions.*')">
                    {{ __('Submissions') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.incident-reports.index')" :active="request()->routeIs('admin.incident-reports.*')">
                    🚨 {{ __('Incident Reports') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full flex items-center justify-center mr-3 overflow-hidden bg-gray-300 dark:bg-gray-600">
                        @if($profilePictureUrl)
                            <img src="{{ $profilePictureUrl }}" alt="{{ Auth::user()->firstName }}'s avatar" class="w-full h-full object-cover">
                        @else
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ substr(Auth::user()->firstName, 0, 1) }}{{ substr(Auth::user()->lastName, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->firstName }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('incident-reports.index')" :active="request()->routeIs('incident-reports.*')">
                    {{ __('Report User') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" novalidate>
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

