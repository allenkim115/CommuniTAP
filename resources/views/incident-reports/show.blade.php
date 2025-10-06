<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Incident Report Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-lg font-medium">Incident Report #{{ $incidentReport->reportId }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Submitted on {{ $incidentReport->report_date->format('F d, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($incidentReport->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($incidentReport->status === 'under_review') bg-blue-100 text-blue-800
                                @elseif($incidentReport->status === 'resolved') bg-green-100 text-green-800
                                @elseif($incidentReport->status === 'dismissed') bg-gray-100 text-gray-800
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $incidentReport->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Reported User -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Reported User</h4>
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ substr($incidentReport->reportedUser->firstName, 0, 1) }}{{ substr($incidentReport->reportedUser->lastName, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $incidentReport->reportedUser->fullName }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $incidentReport->reportedUser->email }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Incident Type -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Incident Type</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($incidentReport->incident_type === 'non_participation') bg-yellow-100 text-yellow-800
                                @elseif($incidentReport->incident_type === 'abuse') bg-red-100 text-red-800
                                @elseif($incidentReport->incident_type === 'spam') bg-blue-100 text-blue-800
                                @elseif($incidentReport->incident_type === 'inappropriate_content') bg-purple-100 text-purple-800
                                @elseif($incidentReport->incident_type === 'harassment') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $incidentReport->incident_type)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Related Task -->
                    @if($incidentReport->task)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Related Task</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('tasks.show', $incidentReport->task) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $incidentReport->task->title }}
                                            </a>
                                        </h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ Str::limit($incidentReport->task->description, 200) }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucwords(str_replace('_', ' ', $incidentReport->task->task_type)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Description</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $incidentReport->description }}</p>
                        </div>
                    </div>

                    <!-- Evidence -->
                    @if($incidentReport->evidence)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Additional Evidence</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $incidentReport->evidence }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Moderation Details -->
                    @if($incidentReport->moderator)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Moderation Details</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Moderator</h5>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $incidentReport->moderator->fullName }}</p>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Moderation Date</h5>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $incidentReport->moderation_date->format('F d, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($incidentReport->action_taken)
                                    <div class="mb-4">
                                        <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action Taken</h5>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($incidentReport->action_taken === 'warning') bg-yellow-100 text-yellow-800
                                            @elseif($incidentReport->action_taken === 'suspension') bg-red-100 text-red-800
                                            @elseif($incidentReport->action_taken === 'no_action') bg-green-100 text-green-800
                                            @elseif($incidentReport->action_taken === 'dismissed') bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucwords(str_replace('_', ' ', $incidentReport->action_taken)) }}
                                        </span>
                                    </div>
                                @endif

                                @if($incidentReport->moderator_notes)
                                    <div>
                                        <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Moderator Notes</h5>
                                        <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $incidentReport->moderator_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('incident-reports.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Reports
                        </a>
                        
                        @if($incidentReport->isPending() && Auth::user()->isAdmin())
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.incident-reports.edit', $incidentReport) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Moderate Report
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
