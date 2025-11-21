<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Submission History') }}
            </h2>
            
            <a href="{{ route('admin.task-submissions.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Pending Submissions
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Completed</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_completed'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Rejected</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_rejected'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <a href="{{ route('admin.task-submissions.history', ['type' => 'completed']) }}" 
                           class="@if($type === 'completed') border-green-500 text-green-600 dark:text-green-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Completed ({{ $stats['total_completed'] }})
                            </div>
                        </a>
                        <a href="{{ route('admin.task-submissions.history', ['type' => 'rejected']) }}" 
                           class="@if($type === 'rejected') border-red-500 text-red-600 dark:text-red-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Rejected ({{ $stats['total_rejected'] }})
                            </div>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        @if($type === 'completed')
                            Completed Submissions
                        @else
                            Rejected Submissions
                        @endif
                    </h3>
                    
                    @if($submissions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Task</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            @if($type === 'completed')
                                                Completed
                                            @else
                                                Rejected
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Photos</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Points</th>
                                        @if($type === 'rejected')
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rejections</th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($submissions as $submission)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-10 w-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            {{ substr($submission->user->name, 0, 2) }}
                                                        </span>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->user->name }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $submission->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->task->title }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $submission->task->task_type)) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                @if($type === 'completed')
                                                    @if($submission->completed_at)
                                                        {{ is_string($submission->completed_at) ? \Carbon\Carbon::parse($submission->completed_at)->format('M j, Y g:i A') : $submission->completed_at->format('M j, Y g:i A') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                @else
                                                    @if($submission->updated_at)
                                                        {{ is_string($submission->updated_at) ? \Carbon\Carbon::parse($submission->updated_at)->format('M j, Y g:i A') : $submission->updated_at->format('M j, Y g:i A') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($submission->photos && count($submission->photos) > 0) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                    @endif">
                                                    {{ $submission->photos ? count($submission->photos) : 0 }} photos
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $submission->task->points_awarded }}
                                            </td>
                                            @if($type === 'rejected')
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        {{ $submission->rejection_count ?? 0 }}/3
                                                    </span>
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.task-submissions.show', $submission) }}" 
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $submissions->appends(['type' => $type])->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                                @if($type === 'completed')
                                    No completed submissions
                                @else
                                    No rejected submissions
                                @endif
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                @if($type === 'completed')
                                    There are no completed submissions yet.
                                @else
                                    There are no rejected submissions yet.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

