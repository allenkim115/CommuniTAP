<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-6">
                <a href="{{ route('admin.dashboard') ?? '#' }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/communitaplogo1.svg') }}" alt="CommuniTAP Logo" class="block h-16 w-auto" />
                </a>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('admin.dashboard') ?? '#' }}" class="text-sm font-semibold text-gray-800 dark:text-gray-200 {{ request()->routeIs('admin.dashboard') ? 'border-b-2 border-teal-500 pb-1' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.users.index') ?? '#' }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Users</a>
                    
                    <!-- Tasks Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white flex items-center {{ request()->routeIs('admin.tasks.*') || request()->routeIs('admin.admin.task-submissions.*') ? 'border-b-2 border-teal-500 pb-1' : '' }}">
                            Tasks
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="{{ route('admin.tasks.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.tasks.*') && !request()->routeIs('admin.admin.task-submissions.*') ? 'bg-teal-50 dark:bg-teal-900 text-teal-700 dark:text-teal-300' : '' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Task Management
                                </a>
                                <a href="{{ route('admin.task-submissions.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.task-submissions.*') ? 'bg-teal-50 dark:bg-teal-900 text-teal-700 dark:text-teal-300' : '' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Task Submissions
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.rewards.index') ?? '#' }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Rewards</a>
                    <a href="{{ route('admin.notifications.index') ?? '#' }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Notifications</a>
                    <a href="{{ route('admin.feedbacks.index') ?? '#' }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Feedbacks</a>
                    <a href="{{ route('admin.incident-reports.index') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white {{ request()->routeIs('admin.incident-reports.*') ? 'border-b-2 border-teal-500 pb-1' : '' }}">🚨 Incident Reports</a>
                    <a href="{{ route('admin.tap-nominations.task-chain') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white {{ request()->routeIs('admin.tap-nominations.task-chain') ? 'border-b-2 border-teal-500 pb-1' : '' }}">🔗 Task Chain</a>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex items-center">
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
            <a href="{{ route('admin.dashboard') ?? '#' }}" class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Dashboard</a>
            <a href="{{ route('admin.users.index') ?? '#' }}" class="block text-sm font-semibold text-gray-600 dark:text-gray-300">Users</a>
            
            <!-- Tasks Section -->
            <div class="space-y-1">
                <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">Tasks</div>
                <a href="{{ route('admin.tasks.index') }}" class="block text-sm text-gray-600 dark:text-gray-300 ml-4">Task Management</a>
                <a href="{{ route('admin.task-submissions.index') }}" class="block text-sm text-gray-600 dark:text-gray-300 ml-4">Task Submissions</a>
            </div>
            
            <a href="{{ route('admin.rewards.index') ?? '#' }}" class="block text-sm font-semibold text-gray-600 dark:text-gray-300">Rewards</a>
            <a href="{{ route('admin.notifications.index') ?? '#' }}" class="block text-sm font-semibold text-gray-600 dark:text-gray-300">Notifications</a>
            <a href="{{ route('admin.feedbacks.index') ?? '#' }}" class="block text-sm font-semibold text-gray-600 dark:text-gray-300">Feedbacks</a>
            <a href="{{ route('admin.incident-reports.index') }}" class="block text-sm font-semibold text-gray-600 dark:text-gray-300">🚨 Incident Reports</a>
            <a href="{{ route('admin.tap-nominations.task-chain') }}" class="block text-sm font-semibold text-gray-600 dark:text-gray-300">🔗 Task Chain</a>
            <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-gray-100 dark:border-gray-800">
                @csrf
                <button class="mt-2 text-sm font-medium text-red-600">Log out</button>
            </form>
        </div>
    </div>
</nav>


