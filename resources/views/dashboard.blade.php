<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Toast Notifications -->
            <x-session-toast />

            <!-- Main Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- On Going Tasks Section -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">On Going Tasks</h3>
                        @if(isset($ongoingTasks) && $ongoingTasks->count() > 0)
                            @foreach($ongoingTasks->take(3) as $task)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-4 transition-all duration-200 hover:shadow-md">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-bold text-gray-800 dark:text-gray-200 text-lg">{{ $task->title ?? 'Task Title' }}</h4>
                                    <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full">
                                        {{ ucfirst($task->pivot->status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    <p>Type: {{ ucfirst($task->task_type ?? 'Daily Task') }}</p>
                                    <p>Date: 
                                        @if($task->due_date)
                                            {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('F j, Y') : $task->due_date->format('F j, Y') }}
                                        @elseif($task->creation_date)
                                            {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('F j, Y') : $task->creation_date->format('F j, Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                    @if($task->start_time && $task->end_time)
                                        <p>Time: {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('g:i A') }}</p>
                                    @elseif($task->start_time)
                                        <p>Time: {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }} onwards</p>
                                    @endif
                                    @if($task->location)
                                        <p>Location: {{ $task->location }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                                        See more details →
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No ongoing tasks yet</p>
                            </div>
                        @endif
                    </div>

                    <!-- Leaderboards Section -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Leaderboards</h3>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Rank</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Points</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <svg class="h-6 w-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    <span class="ml-2 font-bold text-gray-800 dark:text-gray-200">1</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">JJ</span>
                                                    </div>
                                                    <span class="ml-3 text-gray-800 dark:text-gray-200">James Jone</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200 font-bold">120</td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    <span class="ml-2 font-bold text-gray-800 dark:text-gray-200">2</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">MA</span>
                                                    </div>
                                                    <span class="ml-3 text-gray-800 dark:text-gray-200">Michael Angelo</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200 font-bold">97</td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <svg class="h-6 w-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    <span class="ml-2 font-bold text-gray-800 dark:text-gray-200">3</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">BJ</span>
                                                    </div>
                                                    <span class="ml-3 text-gray-800 dark:text-gray-200">Bon Jovi</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200 font-bold">68</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="lg:col-span-1">
                    <!-- Completed Tasks Section -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Completed Tasks</h3>
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @if(isset($completedTasks) && $completedTasks->count() > 0)
                                @foreach($completedTasks->take(5) as $task)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 transition-all duration-200 hover:shadow-md">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-bold text-gray-800 dark:text-gray-200 text-lg">{{ $task->title ?? 'Task Title' }}</h4>
                                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                            Completed
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        <p>Type: {{ ucfirst($task->task_type ?? 'User-Uploaded Task') }}</p>
                                        <p>Date: 
                                            @if($task->due_date)
                                                {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('F j, Y') : $task->due_date->format('F j, Y') }}
                                            @elseif($task->creation_date)
                                                {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('F j, Y') : $task->creation_date->format('F j, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                        <p>Points: {{ $task->points_awarded }}</p>
                                        @if($task->pivot->completed_at)
                                            <p>Completed: {{ is_string($task->pivot->completed_at) ? \Carbon\Carbon::parse($task->pivot->completed_at)->format('M j, Y') : $task->pivot->completed_at->format('M j, Y') }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                                            See more details →
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No completed tasks yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Theme Toggle JavaScript -->
    <script>
        // Force light mode and clear any dark mode preference
        localStorage.setItem('theme', 'light');
        document.documentElement.classList.remove('dark');
        
        // Show the moon icon (indicating we're in light mode and can switch to dark)
        document.getElementById('theme-toggle-light-icon').classList.remove('hidden');
        document.getElementById('theme-toggle-dark-icon').classList.add('hidden');

        // Theme toggle functionality
        document.getElementById('theme-toggle').addEventListener('click', function() {
            const isDark = document.documentElement.classList.contains('dark');
            
            if (isDark) {
                // Switch to light mode
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                document.getElementById('theme-toggle-dark-icon').classList.add('hidden');
                document.getElementById('theme-toggle-light-icon').classList.remove('hidden');
            } else {
                // Switch to dark mode
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                document.getElementById('theme-toggle-light-icon').classList.add('hidden');
                document.getElementById('theme-toggle-dark-icon').classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
