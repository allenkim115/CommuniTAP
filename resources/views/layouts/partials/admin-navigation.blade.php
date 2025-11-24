<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            @php
                $adminNotificationSummary = $adminNotificationSummary ?? [
                    'unreadCount' => 0,
                    'recentNotifications' => collect(),
                ];

                $adminUnreadNotificationsCount = $adminNotificationSummary['unreadCount'];
                $adminRecentNotifications = $adminNotificationSummary['recentNotifications'];
            @endphp

            <div class="flex items-center space-x-6">
                <a href="{{ route('admin.dashboard') ?? '#' }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/communitaplogo1.svg') }}" alt="CommuniTAP Logo" class="block h-16 w-auto" />
                </a>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('admin.dashboard') ?? '#' }}" class="flex items-center text-sm font-semibold text-gray-800 dark:text-gray-200 {{ request()->routeIs('admin.dashboard') ? 'border-b-2 pb-1' : '' }}"
                       @if(request()->routeIs('admin.dashboard')) style="border-color: #2B9D8D;" @endif>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13a8 8 0 0116 0v5H4z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4l2 1"></path>
                        </svg>
                        Dashboard
                    </a>
                    {{-- <a href="{{ route('admin.users.index') ?? '#' }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Users</a> --}}
                    
                    <!-- Tasks Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white flex items-center {{ request()->routeIs('admin.tasks.*') || request()->routeIs('admin.admin.task-submissions.*') ? 'border-b-2 pb-1' : '' }}"
                                @if(request()->routeIs('admin.tasks.*') || request()->routeIs('admin.admin.task-submissions.*')) style="border-color: #2B9D8D;" @endif>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5.5V4.5A1.5 1.5 0 0110.5 3h3A1.5 1.5 0 0115 4.5v1"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8a2 2 0 012 2v9a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2z"></path>
                            </svg>
                            Tasks
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="{{ route('admin.tasks.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.tasks.*') && !request()->routeIs('admin.admin.task-submissions.*') ? 'dark:bg-teal-900 dark:text-teal-300' : '' }}"
                                   @if(request()->routeIs('admin.tasks.*') && !request()->routeIs('admin.admin.task-submissions.*')) style="background-color: rgba(43, 157, 141, 0.1); color: #2B9D8D;" @endif>
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4h6M10 11l2 2 3-3M10 16h4"></path>
                                    </svg>
                                    Task Management
                                </a>
                                <a href="{{ route('admin.task-submissions.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.task-submissions.index') ? 'dark:bg-teal-900 dark:text-teal-300' : '' }}"
                                   @if(request()->routeIs('admin.task-submissions.index')) style="background-color: rgba(43, 157, 141, 0.1); color: #2B9D8D;" @endif>
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5h5l5 5v9a2 2 0 01-2 2H8a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5v5h5"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15h6M9 18h6"></path>
                                    </svg>
                                    Task Submissions
                                </a>
                                <a href="{{ route('admin.task-submissions.history') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.task-submissions.history') ? 'dark:bg-teal-900 dark:text-teal-300' : '' }}"
                                   @if(request()->routeIs('admin.task-submissions.history')) style="background-color: rgba(43, 157, 141, 0.1); color: #2B9D8D;" @endif>
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2.5 1.5"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 110-18 9 9 0 010 18z"></path>
                                    </svg>
                                    Submission History
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.rewards.index') ?? '#' }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 8H3.75A.75.75 0 003 8.75V18a2 2 0 002 2h14a2 2 0 002-2V8.75a.75.75 0 00-.75-.75z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8s-1.5-3-3.5-3S6 6.25 6 7.5 7.25 10 9 10h3"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8s1.5-3 3.5-3S18 6.25 18 7.5 16.75 10 15 10h-3"></path>
                        </svg>
                        Rewards
                    </a>
                    <a href="{{ route('admin.feedbacks.index') ?? '#' }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 11.5c0 4.142-3.806 7.5-8.5 7.5-.964 0-1.897-.128-2.769-.362L6 21v-3.612C4.182 15.938 3 13.861 3 11.5 3 7.358 6.806 4 11.5 4S21 7.358 21 11.5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5h.01M11.5 11.5h.01M14.5 11.5h.01"></path>
                        </svg>
                        Feedbacks
                    </a>
                    {{-- <a href="{{ route('admin.incident-reports.index') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white {{ request()->routeIs('admin.incident-reports.*') ? 'border-b-2 border-teal-500 pb-1' : '' }}">🚨 Incident Reports</a> --}}
                    <a href="{{ route('admin.tap-nominations.task-chain') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white {{ request()->routeIs('admin.tap-nominations.task-chain') ? 'border-b-2 pb-1' : '' }}"
                       @if(request()->routeIs('admin.tap-nominations.task-chain')) style="border-color: #2B9D8D;" @endif>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h3a3 3 0 013 3v0a3 3 0 01-3 3H7a3 3 0 010-6z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 11h3a3 3 0 010 6h-3a3 3 0 01-3-3v0a3 3 0 013-3z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l2 2m-4 4l2 2"></path>
                        </svg>
                        Task Chain
                    </a>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex items-center space-x-3">
                    <x-dropdown align="right" width="96" offset="right: -8rem;">
                        <x-slot name="trigger">
                            <button class="relative inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                @if($adminUnreadNotificationsCount > 0)
                                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-medium leading-none text-white rounded-full" style="background-color: #2B9D8D;">
                                        {{ $adminUnreadNotificationsCount }}
                                    </span>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @include('layouts.partials.notification-dropdown', [
                                'unreadCount' => $adminUnreadNotificationsCount,
                                'notifications' => $adminRecentNotifications,
                            ])
                        </x-slot>
                    </x-dropdown>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Log out</button>
                    </form>
                </div>

                <div class="md:hidden">
                    <button @click="open = !open" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="open" class="md:hidden border-t border-gray-100 dark:border-gray-800">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ route('admin.dashboard') ?? '#' }}" class="flex items-center text-sm font-semibold text-gray-800 dark:text-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13a8 8 0 0116 0v5H4z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4l2 1"></path>
                </svg>
                Dashboard
            </a>
            {{-- <a href="{{ route('admin.users.index') ?? '#' }}" class="block text-sm font-semibold text-gray-600 dark:text-gray-300">Users</a> --}}
            
            <!-- Tasks Section -->
            <div class="space-y-1">
                <div class="flex items-center text-sm font-semibold text-gray-800 dark:text-gray-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5.5V4.5A1.5 1.5 0 0110.5 3h3A1.5 1.5 0 0115 4.5v1"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8a2 2 0 012 2v9a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2z"></path>
                    </svg>
                    Tasks
                </div>
                <a href="{{ route('admin.tasks.index') }}" class="flex items-center text-sm text-gray-600 dark:text-gray-300 ml-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4h6M10 11l2 2 3-3M10 16h4"></path>
                    </svg>
                    Task Management
                </a>
                <a href="{{ route('admin.task-submissions.index') }}" class="flex items-center text-sm text-gray-600 dark:text-gray-300 ml-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5h5l5 5v9a2 2 0 01-2 2H8a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5v5h5"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15h6M9 18h6"></path>
                    </svg>
                    Task Submissions
                </a>
                <a href="{{ route('admin.task-submissions.history') }}" class="flex items-center text-sm text-gray-600 dark:text-gray-300 ml-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2.5 1.5"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 110-18 9 9 0 010 18z"></path>
                    </svg>
                    Submission History
                </a>
            </div>
            
            <a href="{{ route('admin.rewards.index') ?? '#' }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 8H3.75A.75.75 0 003 8.75V18a2 2 0 002 2h14a2 2 0 002-2V8.75a.75.75 0 00-.75-.75z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8s-1.5-3-3.5-3S6 6.25 6 7.5 7.25 10 9 10h3"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8s1.5-3 3.5-3S18 6.25 18 7.5 16.75 10 15 10h-3"></path>
                </svg>
                Rewards
            </a>
            <a href="{{ route('admin.feedbacks.index') ?? '#' }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 11.5c0 4.142-3.806 7.5-8.5 7.5-.964 0-1.897-.128-2.769-.362L6 21v-3.612C4.182 15.938 3 13.861 3 11.5 3 7.358 6.806 4 11.5 4S21 7.358 21 11.5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5h.01M11.5 11.5h.01M14.5 11.5h.01"></path>
                </svg>
                Feedbacks
            </a>
            <a href="{{ route('admin.incident-reports.index') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9l-8 12h16z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13v4M12 19h.01"></path>
                </svg>
                Incident Reports
            </a>
            <a href="{{ route('admin.tap-nominations.task-chain') }}" class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h3a3 3 0 013 3v0a3 3 0 01-3 3H7a3 3 0 010-6z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 11h3a3 3 0 010 6h-3a3 3 0 01-3-3v0a3 3 0 013-3z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l2 2m-4 4l2 2"></path>
                </svg>
                Task Chain
            </a>
            <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-gray-100 dark:border-gray-800">
                @csrf
                <button class="mt-2 text-sm font-medium" style="color: #2B9D8D;">Log out</button>
            </form>
        </div>
    </div>
</nav>


