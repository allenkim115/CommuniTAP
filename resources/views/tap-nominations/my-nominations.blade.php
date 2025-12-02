<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-3xl bg-gradient-to-r from-orange-600 via-orange-500 to-teal-500 bg-clip-text text-transparent">
                {{ __('My Nominations') }}
            </h2>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('tap-nominations.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-orange-600 hover:text-orange-700 bg-orange-50 hover:bg-orange-100 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 5l7 7-7 7" />
                    </svg>
                    Nominations Received
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 via-orange-50/30 to-teal-50/20 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Toast Notifications -->
            <x-session-toast />

            <!-- Info Banner -->
            <div class="mb-8 bg-gradient-to-r from-orange-50 to-teal-50 dark:from-orange-900/20 dark:to-teal-900/20 border-l-4 border-orange-500 dark:border-orange-400 rounded-xl p-6 shadow-lg flex items-start gap-4 hover:shadow-xl transition-shadow duration-300">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-orange-900 dark:text-orange-100 mb-2">Track Your Nominations</h3>
                    <p class="text-sm text-orange-800 dark:text-orange-200 leading-relaxed">
                        View all nominations you've sent to others. Track their status - pending, accepted, or declined. 
                        You earned <span class="font-bold text-orange-600 dark:text-orange-400">+1 point</span> for each nomination you sent.
                </p>
                </div>
            </div>

            @if($nominations->count() > 0)
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Nominations I Sent</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Track the status of your nominations</p>
                        </div>
                    </div>
                    <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Total: <span class="text-orange-600 dark:text-orange-400">{{ $nominations->total() ?? $nominations->count() }}</span></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($nominations as $nomination)
                        @php
                            $statusConfig = [
                                'pending' => [
                                    'badge' => 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 dark:from-yellow-900/30 dark:to-amber-900/30 dark:text-yellow-300',
                                    'border' => 'border-yellow-300 dark:border-yellow-700',
                                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'color' => 'yellow'
                                ],
                                'accepted' => [
                                    'badge' => 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/30 dark:to-emerald-900/30 dark:text-green-300',
                                    'border' => 'border-green-300 dark:border-green-700',
                                    'icon' => 'M5 13l4 4L19 7',
                                    'color' => 'green'
                                ],
                                'declined' => [
                                    'badge' => 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 dark:from-red-900/30 dark:to-rose-900/30 dark:text-red-300',
                                    'border' => 'border-red-300 dark:border-red-700',
                                    'icon' => 'M6 18L18 6M6 6l12 12',
                                    'color' => 'red'
                                ],
                            ];
                            $status = $statusConfig[$nomination->status];
                            $isDaily = strtolower($nomination->task->task_type) === 'daily';
                        @endphp
                        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 border-2 {{ $status['border'] }} overflow-hidden cursor-pointer" onclick="window.location='{{ route('tasks.show', $nomination->task) }}'">
                            <!-- Decorative gradient background -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-400/10 to-teal-400/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                            <!-- Shine effect on hover -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                            
                            <div class="relative">
                            <!-- Header: Title + Status Badge -->
                                <div class="flex justify-between items-start mb-3 gap-3">
                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 text-lg leading-tight flex-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                    {{ $nomination->task->title }}
                                </h4>
                                    <span class="px-3 py-1.5 text-xs font-bold rounded-full {{ $status['badge'] }} shadow-sm whitespace-nowrap flex-shrink-0">
                                    {{ ucfirst($nomination->status) }}
                                </span>
                            </div>

                            <!-- Description -->
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2 leading-relaxed">
                                {{ $nomination->task->description }}
                            </p>

                            <!-- Badges: Points, Task Type (Daily), Location -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="inline-flex items-center gap-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    +{{ $nomination->task->points_awarded }} pts
                                </span>
                                    <span class="inline-flex items-center bg-gradient-to-r from-teal-500 to-teal-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md">
                                    {{ $isDaily ? 'Daily' : ucfirst($nomination->task->task_type) }}
                                </span>
                                @if($nomination->task->location)
                                        <span class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-lg text-xs font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        {{ $nomination->task->location }}
                                    </span>
                                @endif
                            </div>

                            <!-- Info row: Nominated to + Date -->
                                <div class="flex items-center gap-2 mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl text-sm">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $nomination->nominee->fullName }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                            {{ $nomination->nomination_date->format('M d, Y') }}
                                        </p>
                                    </div>
                            </div>

                            <!-- Footer status message -->
                                <div class="pt-4 border-t-2 border-gray-100 dark:border-gray-700">
                                @if($nomination->status === 'pending')
                                        <div class="flex items-center gap-2 text-sm font-medium text-yellow-700 dark:text-yellow-300 bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 p-3 rounded-xl border border-yellow-200 dark:border-yellow-800">
                                            <svg class="w-5 h-5 animate-pulse flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                            <span>Waiting for response...</span>
                                    </div>
                                @elseif($nomination->status === 'accepted')
                                        <div class="flex items-center gap-2 text-sm font-medium text-green-700 dark:text-green-300 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-3 rounded-xl border border-green-200 dark:border-green-800">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                            <span>Accepted! They've been assigned to the task.</span>
                                    </div>
                                @elseif($nomination->status === 'declined')
                                        <div class="flex items-center gap-2 text-sm font-medium text-red-700 dark:text-red-300 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 p-3 rounded-xl border border-red-200 dark:border-red-800">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                            <span>Declined. You can nominate someone else for this task.</span>
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-2 border border-gray-200 dark:border-gray-700">
                    {{ $nominations->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-3xl shadow-xl p-12 text-center">
                    <div class="mx-auto w-24 h-24 mb-6 rounded-full bg-gradient-to-br from-orange-100 to-teal-100 dark:from-orange-900/30 dark:to-teal-900/30 flex items-center justify-center">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-12 h-12 text-orange-500 dark:text-orange-400">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">No Nominations Sent Yet</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-2 text-lg">You haven't sent any Tap & Pass nominations yet.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Complete a daily task and nominate someone to get started!</p>
                    <a href="{{ route('tasks.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5"
                       style="background-color: #F3A261;"
                       onmouseover="this.style.backgroundColor='#E8944F'"
                       onmouseout="this.style.backgroundColor='#F3A261'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Browse Tasks
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes card-enter {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .grid > div {
            animation: card-enter 0.5s ease-out forwards;
        }
        
        .grid > div:nth-child(1) { animation-delay: 0.1s; }
        .grid > div:nth-child(2) { animation-delay: 0.2s; }
        .grid > div:nth-child(3) { animation-delay: 0.3s; }
        .grid > div:nth-child(4) { animation-delay: 0.4s; }
        .grid > div:nth-child(5) { animation-delay: 0.5s; }
        .grid > div:nth-child(6) { animation-delay: 0.6s; }
        
        .grid > div {
            opacity: 0;
        }
    </style>
</x-app-layout>

