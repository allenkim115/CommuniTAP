<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Incident Report Details') }}
        </h2>
    </x-slot>

    @php
        $statusClasses = [
            'pending' => 'badge-soft badge-soft-orange',
            'under_review' => 'badge-soft badge-soft-teal',
            'resolved' => 'badge-soft badge-soft-teal',
            'dismissed' => 'badge-soft badge-soft-slate',
        ];

        $actionTagClasses = [
            'warning' => 'bg-yellow-100 text-yellow-800',
            'suspension' => 'bg-red-100 text-red-800',
            'no_action' => 'bg-green-100 text-green-800',
            'dismissed' => 'bg-gray-100 text-gray-800',
        ];
    @endphp

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card-surface">
                <div class="p-6 lg:p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between mb-6">
                        <div>
                            <p class="text-sm uppercase tracking-wide text-gray-500">Incident Report</p>
                            <h3 class="mt-1 text-2xl font-semibold text-gray-900">#{{ $incidentReport->reportId }}</h3>
                            <p class="text-sm text-gray-500">
                                Submitted {{ $incidentReport->report_date->format('F d, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold {{ $statusClasses[$incidentReport->status] ?? 'badge-soft badge-soft-slate' }}">
                                {{ ucwords(str_replace('_', ' ', $incidentReport->status)) }}
                            </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Reporter -->
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 dark:bg-gray-700/40 p-5">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Reporter</h4>
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 bg-brand-teal/10 text-brand-teal-dark rounded-full flex items-center justify-center font-semibold">
                                        {{ substr($incidentReport->reporter->firstName, 0, 1) }}{{ substr($incidentReport->reporter->lastName, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $incidentReport->reporter->fullName }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $incidentReport->reporter->email }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        User ID: {{ $incidentReport->reporter->userId }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reported User -->
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 dark:bg-gray-700/40 p-5">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Reported User</h4>
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 bg-brand-orange/20 text-brand-orange-dark rounded-full flex items-center justify-center font-semibold">
                                        {{ substr($incidentReport->reportedUser->firstName, 0, 1) }}{{ substr($incidentReport->reportedUser->lastName, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $incidentReport->reportedUser->fullName }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $incidentReport->reportedUser->email }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        User ID: {{ $incidentReport->reportedUser->userId }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Incident Type -->
                        <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Incident Type</h4>
                            @php
                                $typeClass = in_array($incidentReport->incident_type, ['abuse', 'spam', 'harassment', 'inappropriate_content'])
                                    ? 'badge-soft badge-soft-teal'
                                    : 'badge-soft badge-soft-orange';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $typeClass }}">
                                {{ ucwords(str_replace('_', ' ', $incidentReport->incident_type)) }}
                            </span>
                        </div>

                        <!-- Related Task -->
                        <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Related Task</h4>
                            @if($incidentReport->task)
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('admin.tasks.show', $incidentReport->task) }}" class="text-brand-teal-dark hover:text-brand-teal">
                                            {{ $incidentReport->task->title }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Task ID: {{ $incidentReport->task->taskId }}
                                    </div>
                                </div>
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">No related task</span>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Description</h4>
                        <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5">
                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $incidentReport->description }}</p>
                        </div>
                    </div>

                    <!-- Evidence -->
                    @if($incidentReport->evidence)
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Additional Evidence</h4>
                            <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5 space-y-3">
                                @php
                                    $evidenceLines = preg_split("/\r?\n/", (string) $incidentReport->evidence);
                                @endphp
                                @foreach($evidenceLines as $line)
                                    @php $trimmed = trim($line); @endphp
                                    @if($trimmed !== '')
                                        @if(strpos($trimmed, 'Image: ') === 0)
                                            @php $path = trim(substr($trimmed, 7)); @endphp
                                            <div>
                                                <img src="{{ asset('storage/' . $path) }}" alt="Incident evidence image" class="max-h-80 rounded border border-gray-200 dark:border-gray-600">
                                                <div class="mt-1 text-xs text-gray-500 break-all">{{ $path }}</div>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $trimmed }}</p>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Moderation Details -->
                    @if($incidentReport->moderator)
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Moderation Details</h4>
                            <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5">
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
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $actionTagClasses[$incidentReport->action_taken] ?? 'bg-gray-100 text-gray-800' }}">
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
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between pt-6 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('admin.incident-reports.index') }}" 
                           class="btn-muted">
                            Back to Reports
                        </a>
                        
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.incident-reports.edit', $incidentReport) }}" 
                               class="btn-brand">
                                {{ $incidentReport->isPending() ? 'Moderate Report' : 'Edit Report' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
