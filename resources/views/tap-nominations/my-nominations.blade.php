<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                {{ __('My Nominations') }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('tap-nominations.index') }}" 
                   class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 5l7 7-7 7" />
                    </svg>
                    Nominations Received
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-950 py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
            <!-- Toast Notifications -->
            <x-session-toast />

            <!-- Info Banner -->
            <div class="mb-8 bg-blue-100 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5 flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>
                <p class="text-sm text-blue-900 dark:text-blue-200 leading-relaxed">
                    <strong>Track Your Nominations:</strong> View all nominations you've sent to others. 
                    Track their status - pending, accepted, or declined. You earned <strong>+1 point</strong> for each nomination you sent.
                </p>
            </div>

            @if($nominations->count() > 0)
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-900 dark:text-gray-100">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Nominations I Sent
                    </h3>
                    <span class="text-sm text-gray-500">Total: {{ $nominations->total() ?? $nominations->count() }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($nominations as $nomination)
                        @php
                            $badgeMap = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                'accepted' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                'declined' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                            ];
                            $isDaily = strtolower($nomination->task->task_type) === 'daily';
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 transition-all duration-200 hover:shadow-md border border-gray-200 dark:border-gray-700">
                            <!-- Header: Title + Status Badge -->
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 text-lg leading-tight flex-1 pr-2">
                                    {{ $nomination->task->title }}
                                </h4>
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $badgeMap[$nomination->status] }}">
                                    {{ ucfirst($nomination->status) }}
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                {{ $nomination->task->description }}
                            </p>

                            <!-- Badges: Points, Task Type (Daily), Location -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 px-2.5 py-1 rounded-md text-xs font-medium">
                                    +{{ $nomination->task->points_awarded }} pts
                                </span>
                                <span class="bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-300 px-2.5 py-1 rounded-md text-xs font-medium">
                                    {{ $isDaily ? 'Daily' : ucfirst($nomination->task->task_type) }}
                                </span>
                                @if($nomination->task->location)
                                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2.5 py-1 rounded-md text-xs font-medium">
                                        {{ $nomination->task->location }}
                                    </span>
                                @endif
                            </div>

                            <!-- Info row: Nominated to + Date -->
                            <div class="flex items-center space-x-2 mb-3 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>
                                    <span class="font-semibold">Nominated to:</span> {{ $nomination->nominee->fullName }} • {{ $nomination->nomination_date->format('M d, Y') }}
                                </span>
                            </div>

                            <!-- Footer status message -->
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                @if($nomination->status === 'pending')
                                    <div class="flex items-center gap-2 text-sm text-yellow-700 dark:text-yellow-300">
                                        <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-medium">Waiting for response...</span>
                                    </div>
                                @elseif($nomination->status === 'accepted')
                                    <div class="flex items-center gap-2 text-sm text-green-700 dark:text-green-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="font-medium">Accepted! They've been assigned to the task.</span>
                                    </div>
                                @elseif($nomination->status === 'declined')
                                    <div class="flex items-center gap-2 text-sm text-red-700 dark:text-red-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span class="font-medium">Declined. You can nominate someone else for this task.</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $nominations->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-lg p-10 text-center">
                    <div class="mx-auto w-20 h-20 text-gray-400 mb-5">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">No Nominations Sent Yet</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-1">You haven't sent any Tap & Pass nominations yet.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Complete a daily task and nominate someone to get started!</p>
                    <a href="{{ route('tasks.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Browse Tasks
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

