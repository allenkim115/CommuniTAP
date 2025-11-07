<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                {{ __('Tap & Pass Nominations') }}
            </h2>
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M15 19l-7-7 7-7" />
                </svg>
                Back to Dashboard
            </a>
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
                    <strong>Tap & Pass:</strong> Accept or decline nominations sent to you for daily tasks. 
                    Accepting a nomination assigns you to the task and awards <strong>+1 point</strong>.
                </p>
            </div>

            @if($nominations->count() > 0)
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-900 dark:text-gray-100">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Nominations Received
                    </h3>
                    <span class="text-sm text-gray-500">Total: {{ $nominations->total() ?? $nominations->count() }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($nominations as $nomination)
                        @php
                            $badgeMap = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'accepted' => 'bg-green-100 text-green-800',
                                'declined' => 'bg-red-100 text-red-800',
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

                            <!-- Info row: Nominated by + Date -->
                            <div class="flex items-center space-x-2 mb-3 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>
                                    <span class="font-semibold">Nominated by:</span> {{ $nomination->nominator->fullName }} • {{ $nomination->nomination_date->format('M d, Y') }}
                                </span>
                            </div>

                            <!-- Footer actions / status -->
                            @if($nomination->status === 'pending')
                                <div class="flex items-center gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <form method="POST" action="{{ route('tap-nominations.accept', $nomination) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md shadow-sm transition" onclick="return confirm('Accept this nomination? You will earn +1 point.')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Accept
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('tap-nominations.decline', $nomination) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md shadow-sm transition" onclick="return confirm('Decline this nomination?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            @elseif($nomination->status === 'accepted')
                                <div class="text-sm text-green-700 dark:text-green-300 font-medium pt-2 border-t border-gray-200 dark:border-gray-700">Accepted</div>
                            @elseif($nomination->status === 'declined')
                                <div class="text-sm text-red-700 dark:text-red-300 font-medium pt-2 border-t border-gray-200 dark:border-gray-700">Declined</div>
                            @endif
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
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">No Nominations Yet</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-1">You haven’t received any Tap & Pass nominations yet.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Complete daily tasks to be eligible for future nominations.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
