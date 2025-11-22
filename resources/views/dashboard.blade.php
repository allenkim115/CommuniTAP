<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen transition-colors duration-200" style="background: linear-gradient(135deg, #f6f7fb 0%, #f0f4f8 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Toast Notifications -->
            <x-session-toast />

            <!-- Welcome Hero Section -->
            <div class="relative overflow-hidden bg-gradient-to-r from-orange-500 via-orange-600 to-teal-500 rounded-3xl shadow-2xl mb-8 p-8 lg:p-10">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMC41IiBzdHJva2Utb3BhY2l0eT0iMC4xIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-20"></div>
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex-1">
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-2">
                                Welcome back, {{ Auth::user()->firstName ?? 'Volunteer' }}! 👋
                            </h1>
                            <p class="text-orange-50 text-lg lg:text-xl">
                                Ready to make a difference in your community today?
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3 lg:flex-nowrap">
                            <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-md text-white rounded-xl font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 border border-white/30">
                                <span class="text-xl">🔍</span>
                                Browse Tasks
                            </a>
                            <a href="{{ route('rewards.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-md text-white rounded-xl font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 border border-white/30">
                                <span class="text-xl">🎁</span>
                                View Rewards
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Summary Cards with Enhanced Design -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Points Card -->
                <div class="group relative bg-white rounded-3xl shadow-xl border-2 border-orange-200 overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-400/20 to-orange-600/20 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">Total Points</span>
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg text-2xl transform group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300">
                                🏆
                            </div>
                        </div>
                        <div class="text-5xl font-extrabold text-gray-900 mb-2 bg-gradient-to-br from-orange-600 to-orange-700 bg-clip-text text-transparent">
                            {{ $stats['points'] ?? 0 }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <div class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></div>
                            <span>Across all completed tasks</span>
                        </div>
                        <div class="mt-4 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-orange-500 to-orange-600 rounded-full" style="width: {{ min(($stats['points'] ?? 0) / 10, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Ongoing Tasks Card -->
                <div class="group relative bg-white rounded-3xl shadow-xl border-2 border-blue-200 overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/20 to-blue-600/20 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Ongoing</span>
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg text-2xl transform group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300">
                                ⏳
                            </div>
                        </div>
                        <div class="text-5xl font-extrabold text-gray-900 mb-2 bg-gradient-to-br from-blue-600 to-blue-700 bg-clip-text text-transparent">
                            {{ isset($ongoingTasks) ? $ongoingTasks->count() : 0 }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                            <span>Currently in progress</span>
                        </div>
                        @if(isset($ongoingTasks) && $ongoingTasks->count() > 0)
                        <div class="mt-4 flex gap-1">
                            @foreach($ongoingTasks->take(3) as $task)
                                <div class="flex-1 h-2 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full"></div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Completed Tasks Card -->
                <div class="group relative bg-white rounded-3xl shadow-xl border-2 border-green-200 overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-400/20 to-green-600/20 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Completed</span>
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg text-2xl transform group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300">
                                ✔
                            </div>
                        </div>
                        <div class="text-5xl font-extrabold text-gray-900 mb-2 bg-gradient-to-br from-green-600 to-green-700 bg-clip-text text-transparent">
                            {{ isset($completedTasks) ? $completedTasks->count() : 0 }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                            <span>All time completed</span>
                        </div>
                        @php
                            $completionRate = isset($userTasks) && $userTasks->count() > 0 
                                ? round((isset($completedTasks) ? $completedTasks->count() : 0) / $userTasks->count() * 100, 0) 
                                : 0;
                        @endphp
                        <div class="mt-4 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-green-500 to-green-600 rounded-full transition-all duration-1000" style="width: {{ min($completionRate, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Rank Card -->
                <div class="group relative bg-white rounded-3xl shadow-xl border-2 border-teal-200 overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-teal-400/20 to-teal-600/20 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">Your Rank</span>
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg text-2xl transform group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300">
                                ⭐
                            </div>
                        </div>
                        <div class="text-5xl font-extrabold text-gray-900 mb-2 bg-gradient-to-br from-teal-600 to-teal-700 bg-clip-text text-transparent">
                            #{{ $stats['rank'] ?? '—' }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <div class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></div>
                            <span>Community leaderboard</span>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            @if(($stats['rank'] ?? 999) <= 3)
                                <span class="text-xs font-bold text-teal-600 uppercase">🏅 Top Performer!</span>
                            @elseif(($stats['rank'] ?? 999) <= 10)
                                <span class="text-xs font-bold text-teal-600 uppercase">🌟 Rising Star!</span>
                            @else
                                <span class="text-xs font-bold text-gray-500 uppercase">Keep Going!</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- On Going Tasks Section -->
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6 lg:p-8 hover:shadow-2xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg text-white text-xl transform hover:rotate-6 transition-transform duration-300">
                                    ⏳
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">On Going Tasks</h3>
                                    <p class="text-sm text-gray-500">Tasks you're currently working on</p>
                                </div>
                            </div>
                            @if(isset($ongoingTasks) && $ongoingTasks->count() > 0)
                                <a href="{{ route('tasks.index', ['filter' => 'assigned']) }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700 px-4 py-2 rounded-lg hover:bg-orange-50 transition-colors duration-200">
                                    View All →
                                </a>
                            @endif
                        </div>
                        @if(isset($ongoingTasks) && $ongoingTasks->count() > 0)
                            <div class="space-y-4">
                            @foreach($ongoingTasks->take(3) as $task)
                                <div class="group relative bg-gradient-to-r from-orange-50 via-orange-50 to-transparent rounded-2xl p-6 border-2 border-orange-200 hover:border-orange-400 transition-all duration-300 hover:shadow-xl cursor-pointer transform hover:-translate-y-1" onclick="window.location='{{ route('tasks.show', $task) }}'">
                                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500/0 to-orange-500/0 group-hover:from-orange-500/5 group-hover:to-orange-500/0 rounded-2xl transition-all duration-300"></div>
                                    <div class="relative">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="font-bold text-gray-900 text-lg group-hover:text-orange-600 transition-colors duration-200 flex-1 pr-4">{{ $task->title ?? 'Task Title' }}</h4>
                                            <span class="px-4 py-1.5 text-xs font-bold bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-full shadow-md whitespace-nowrap">
                                                {{ ucfirst($task->pivot->status ?? 'In Progress') }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 text-sm text-gray-700">
                                            <div class="flex items-center gap-2">
                                                <span class="text-orange-500 text-base">🏷️</span>
                                                <span class="text-xs">{{ ucfirst($task->task_type ?? 'Daily Task') }}</span>
                                            </div>
                                            @if($task->due_date || $task->creation_date)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-orange-500 text-base">📅</span>
                                                    <span class="text-xs">
                                                        @if($task->due_date)
                                                            {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                                        @elseif($task->creation_date)
                                                            {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                            @if($task->start_time)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-orange-500 text-base">🕐</span>
                                                    <span class="text-xs">
                                                        @if($task->start_time && $task->end_time)
                                                            {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('g:i A') }}
                                                        @else
                                                            {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }} onwards
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                            @if($task->location)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-orange-500 text-base">📍</span>
                                                    <span class="text-xs truncate">{{ $task->location }}</span>
                                                </div>
                                            @endif
                                            @if($task->points_awarded)
                                                <div class="flex items-center gap-2 col-span-2">
                                                    <span class="text-orange-500 text-base">⭐</span>
                                                    <span class="text-xs font-bold text-orange-600">{{ $task->points_awarded }} points reward</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-orange-200">
                                            <span class="text-xs text-gray-500">Click to view details</span>
                                            <span class="text-orange-500 group-hover:translate-x-2 transition-transform duration-200 text-lg">→</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-12 text-center border-2 border-dashed border-gray-300">
                                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center text-4xl transform hover:scale-110 transition-transform duration-300">
                                    📋
                                </div>
                                <p class="text-gray-700 font-semibold text-lg mb-2">No ongoing tasks yet</p>
                                <p class="text-sm text-gray-500 mb-6">Start by browsing available tasks and contribute to your community</p>
                                <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-semibold hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    <span class="text-xl">➕</span>
                                    Browse Available Tasks
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Leaderboards Section -->
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6 lg:p-8 hover:shadow-2xl transition-shadow duration-300">
                        <div class="flex items-center gap-4 mb-6 pb-4 border-b-2 border-gray-100">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg text-white text-xl transform hover:rotate-6 transition-transform duration-300">
                                🏆
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Leaderboards</h3>
                                <p class="text-sm text-gray-500">Top community contributors</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @forelse($topUsers as $index => $user)
                                @php
                                    $rank = $index + 1;
                                    $rankClasses = [
                                        1 => [
                                            'bg' => 'from-yellow-50 via-amber-50 to-yellow-50',
                                            'border' => 'border-yellow-300',
                                            'badge' => 'from-yellow-400 to-yellow-600',
                                            'points' => 'bg-gradient-to-br from-yellow-600 to-amber-700 bg-clip-text text-transparent',
                                            'shadow' => 'shadow-lg',
                                            'title' => '👑',
                                            'subtitle' => 'Top contributor'
                                        ],
                                        2 => [
                                            'bg' => 'from-gray-50 to-gray-100',
                                            'border' => 'border-gray-300',
                                            'badge' => 'from-gray-400 to-gray-600',
                                            'points' => 'text-gray-700',
                                            'shadow' => 'shadow-md',
                                            'title' => '⭐',
                                            'subtitle' => 'Active member'
                                        ],
                                        3 => [
                                            'bg' => 'from-orange-50 via-amber-50 to-orange-50',
                                            'border' => 'border-orange-300',
                                            'badge' => 'from-orange-500 to-orange-600',
                                            'points' => 'text-orange-600',
                                            'shadow' => 'shadow-md',
                                            'title' => '🚀',
                                            'subtitle' => 'Rising star'
                                        ]
                                    ];
                                    $styles = $rankClasses[$rank] ?? $rankClasses[2];
                                @endphp
                                <div class="relative bg-gradient-to-r {{ $styles['bg'] }} rounded-2xl p-5 border-2 {{ $styles['border'] }} {{ $styles['shadow'] }} hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="absolute -top-3 -left-3 w-12 h-12 rounded-full bg-gradient-to-br {{ $styles['badge'] }} text-white font-bold shadow-xl flex items-center justify-center text-lg border-4 border-white">
                                        {{ $rank }}
                                    </div>
                                    <div class="flex items-center justify-between pl-6">
                                        <div class="flex items-center gap-4">
                                            <div class="h-14 w-14 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center shadow-md border-2 border-white">
                                                <span class="text-base font-bold text-white">{{ $user->initials }}</span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 text-lg">{{ $user->fullName }}</p>
                                                <p class="text-xs text-gray-600 flex items-center gap-1">
                                                    <span>{{ $styles['title'] }}</span>
                                                    <span>{{ $styles['subtitle'] }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-3xl font-extrabold {{ $styles['points'] }}">{{ number_format($user->points ?? 0) }}</p>
                                            <p class="text-xs text-gray-500 font-medium">points</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-12 text-center border-2 border-dashed border-gray-300">
                                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center text-4xl transform hover:scale-110 transition-transform duration-300">
                                        🏆
                                    </div>
                                    <p class="text-gray-700 font-semibold text-lg mb-2">No leaderboard data yet</p>
                                    <p class="text-sm text-gray-500">Start completing tasks to see top contributors!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="lg:col-span-1">
                    <!-- Completed Tasks Section -->
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6 lg:p-8 hover:shadow-2xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg text-white text-xl transform hover:rotate-6 transition-transform duration-300">
                                    ✅
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Completed Tasks</h3>
                                    <p class="text-sm text-gray-500">Your achievement history</p>
                                </div>
                            </div>
                            @if(isset($completedTasks) && $completedTasks->count() > 0)
                                <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" class="text-sm font-semibold text-green-600 hover:text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200">
                                    View All →
                                </a>
                            @endif
                        </div>
                        <div class="space-y-4 max-h-[700px] overflow-y-auto pr-2 custom-scrollbar">
                            @if(isset($completedTasks) && $completedTasks->count() > 0)
                                @foreach($completedTasks->take(5) as $task)
                                <div class="group relative bg-gradient-to-r from-green-50 via-emerald-50 to-transparent rounded-2xl p-6 border-2 border-green-200 hover:border-green-400 transition-all duration-300 hover:shadow-xl cursor-pointer transform hover:-translate-y-1" onclick="window.location='{{ route('tasks.show', $task) }}'">
                                    <div class="absolute inset-0 bg-gradient-to-r from-green-500/0 to-green-500/0 group-hover:from-green-500/5 group-hover:to-green-500/0 rounded-2xl transition-all duration-300"></div>
                                    <div class="relative">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="font-bold text-gray-900 text-lg group-hover:text-green-600 transition-colors duration-200 flex-1 pr-4">{{ $task->title ?? 'Task Title' }}</h4>
                                            <span class="px-4 py-1.5 text-xs font-bold bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full shadow-md whitespace-nowrap flex items-center gap-1">
                                                <span>✓</span>
                                                <span>Completed</span>
                                            </span>
                                        </div>
                                        <div class="space-y-2 text-sm text-gray-700 mb-4">
                                            <div class="flex items-center gap-2">
                                                <span class="text-green-500 text-base">🏷️</span>
                                                <span class="text-xs">{{ ucfirst($task->task_type ?? 'User-Uploaded Task') }}</span>
                                            </div>
                                            @if($task->due_date || $task->creation_date)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-green-500 text-base">📅</span>
                                                    <span class="text-xs">
                                                        @if($task->due_date)
                                                            {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                                        @elseif($task->creation_date)
                                                            {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                            @if($task->points_awarded)
                                                <div class="flex items-center gap-2 bg-green-100/50 rounded-lg px-3 py-2">
                                                    <span class="text-green-600 text-base">⭐</span>
                                                    <span class="text-sm font-bold text-green-700">{{ $task->points_awarded }} points earned</span>
                                                </div>
                                            @endif
                                            @if($task->pivot->completed_at)
                                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                                    <span class="text-green-500">✓</span>
                                                    <span>Completed {{ is_string($task->pivot->completed_at) ? \Carbon\Carbon::parse($task->pivot->completed_at)->diffForHumans() : $task->pivot->completed_at->diffForHumans() }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center justify-between pt-4 border-t border-green-200">
                                            <span class="text-xs text-gray-500">Click to view details</span>
                                            <span class="text-green-500 group-hover:translate-x-2 transition-transform duration-200 text-lg">→</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-12 text-center border-2 border-dashed border-gray-300">
                                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center text-4xl transform hover:scale-110 transition-transform duration-300">
                                        ✅
                                    </div>
                                    <p class="text-gray-700 font-semibold text-lg mb-2">No completed tasks yet</p>
                                    <p class="text-sm text-gray-500">Complete tasks to earn points and see your achievements here</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #10b981, #059669);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #059669, #047857);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(249, 115, 22, 0.3); }
            50% { box-shadow: 0 0 30px rgba(249, 115, 22, 0.6); }
        }
    </style>

    <!-- Theme Toggle JavaScript -->
    <script>
        // Force light mode and clear any dark mode preference
        localStorage.setItem('theme', 'light');
        document.documentElement.classList.remove('dark');
        
        // Show the moon icon (indicating we're in light mode and can switch to dark)
        if (document.getElementById('theme-toggle-light-icon')) {
            document.getElementById('theme-toggle-light-icon').classList.remove('hidden');
        }
        if (document.getElementById('theme-toggle-dark-icon')) {
            document.getElementById('theme-toggle-dark-icon').classList.add('hidden');
        }

        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                const isDark = document.documentElement.classList.contains('dark');
                
                if (isDark) {
                    // Switch to light mode
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    if (document.getElementById('theme-toggle-dark-icon')) {
                        document.getElementById('theme-toggle-dark-icon').classList.add('hidden');
                    }
                    if (document.getElementById('theme-toggle-light-icon')) {
                        document.getElementById('theme-toggle-light-icon').classList.remove('hidden');
                    }
                } else {
                    // Switch to dark mode
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    if (document.getElementById('theme-toggle-light-icon')) {
                        document.getElementById('theme-toggle-light-icon').classList.add('hidden');
                    }
                    if (document.getElementById('theme-toggle-dark-icon')) {
                        document.getElementById('theme-toggle-dark-icon').classList.remove('hidden');
                    }
                }
            });
        }
    </script>
</x-app-layout>
