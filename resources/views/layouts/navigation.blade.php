<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo on the left -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/communitaplogo1.svg') }}" alt="CommuniTAP Logo" class="block h-16 w-auto" />
                    </a>
                </div>
            </div>

            <!-- Navigation Links and User Dropdown on the right -->
            <div class="flex items-center space-x-4">
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
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
                    @endphp
                    
                    <x-nav-link :href="route('tap-nominations.index')" :active="request()->routeIs('tap-nominations.*')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
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
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        {{ __('Progress') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.*') || request()->routeIs('rewards.mine')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        {{ __('Spend Points') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('feedback.index')" :active="request()->routeIs('feedback.*')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        {{ __('Feedbacks') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('incident-reports.index')" :active="request()->routeIs('incident-reports.*')">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16m0-12l2-1a4 4 0 013.2 0l1.6.8a4 4 0 003.2 0l2-1A4 4 0 0118 6v9m-14 5h4" />
                        </svg>
                        {{ __('Report User') }}
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

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-2">
                    <!-- Notifications Dropdown -->
                    <x-dropdown align="right" width="80">
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
                            <div class="px-4 py-3 flex items-center justify-between border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">Notifications</span>
                                    @if($unreadNotificationsCount > 0)
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white rounded-full" style="background-color: #F3A261;">
                                            {{ $unreadNotificationsCount }}
                                        </span>
                                    @endif
                                </div>
                                @if($unreadNotificationsCount > 0)
                                    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold dark:text-orange-400 dark:hover:text-orange-300 hover:underline transition-colors"
                                                style="color: #F3A261;"
                                                onmouseover="this.style.color='#E8944F';"
                                                onmouseout="this.style.color='#F3A261';">
                                            Mark all read
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                @forelse($recentNotifications as $notification)
                                    @php
                                        $message = strtolower($notification->message ?? '');
                                        $iconColor = 'text-gray-500';
                                        if (str_contains($message, 'reward') || str_contains($message, 'redeem')) {
                                            $iconColor = '#FED2B3';
                                        } elseif (str_contains($message, 'task') || str_contains($message, 'assign')) {
                                            $iconColor = '#2B9D8D';
                                        } elseif (str_contains($message, 'point') || str_contains($message, 'earn')) {
                                            $iconColor = '#2B9D8D';
                                        } elseif (str_contains($message, 'feedback')) {
                                            $iconColor = '#2B9D8D';
                                        }
                                    @endphp
                                    <a href="{{ $notification->data['url'] ?? route('notifications.index') }}" 
                                       class="block px-4 py-3 border-t border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $notification->status === 'unread' ? 'dark:bg-orange-900/10 border-l-4' : '' }}"
                                       @if($notification->status === 'unread') style="background-color: rgba(243, 162, 97, 0.1); border-color: #F3A261;" @endif>
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <div class="p-1.5 rounded-lg {{ $notification->status === 'unread' ? 'dark:bg-orange-900/30' : 'bg-gray-100 dark:bg-gray-700' }}"
                                                     @if($notification->status === 'unread') style="background-color: rgba(243, 162, 97, 0.2);" @endif>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ is_string($iconColor) ? $iconColor : '#2B9D8D' }};">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 leading-snug line-clamp-2">
                                                        {{ $notification->message }}
                                                    </p>
                                                    @if($notification->status === 'unread')
                                                        <span class="flex-shrink-0 w-2 h-2 rounded-full mt-1" style="background-color: #F3A261;"></span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ optional($notification->created_at)->diffForHumans() ?? optional($notification->created_date)->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center">
                                        <svg class="mx-auto w-12 h-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No notifications yet.</p>
                                    </div>
                                @endforelse
                            </div>
                            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                                <a href="{{ route('notifications.index') }}" 
                                   class="inline-flex items-center gap-2 text-sm font-semibold dark:text-orange-400 dark:hover:text-orange-300 transition-colors"
                                   style="color: #F3A261;"
                                   onmouseover="this.style.color='#E8944F';"
                                   onmouseout="this.style.color='#F3A261';">
                                    <span>View all notifications</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </x-slot>
                    </x-dropdown>

                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <!-- User Avatar -->
                                <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ substr(Auth::user()->firstName, 0, 1) }}{{ substr(Auth::user()->lastName, 0, 1) }}</span>
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

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
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
                🎯 {{ __('Nominations') }}
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
                {{ __('Spend Points') }}
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
            
            <x-responsive-nav-link :href="route('incident-reports.index')" :active="request()->routeIs('incident-reports.*')">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16m0-12l2-1a4 4 0 013.2 0l1.6.8a4 4 0 003.2 0l2-1A4 4 0 0118 6v9m-14 5h4" />
                </svg>
                {{ __('Report User') }}
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
                    <div class="h-10 w-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ substr(Auth::user()->firstName, 0, 1) }}{{ substr(Auth::user()->lastName, 0, 1) }}</span>
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

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
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

