<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Incident Report Details') }}
        </h2>
    </x-slot>

    @php
        $statusClasses = [
            'pending' => 'badge-soft badge-soft-amber',
            'under_review' => 'badge-soft badge-soft-teal',
            'resolved' => 'badge-soft badge-soft-teal',
            'dismissed' => 'badge-soft badge-soft-slate',
        ];
        $typeClasses = [
            'non_participation' => 'badge-soft badge-soft-amber',
            'abuse' => 'badge-soft badge-soft-orange',
            'spam' => 'badge-soft badge-soft-teal',
            'inappropriate_content' => 'badge-soft badge-soft-orange',
            'harassment' => 'badge-soft badge-soft-orange',
        ];

        $timeline = [
            [
                'label' => 'Report submitted',
                'date' => $incidentReport->report_date,
                'description' => 'You shared details about this incident.',
                'complete' => true,
            ],
            [
                'label' => 'Moderation in progress',
                'date' => $incidentReport->moderation_date,
                'description' => $incidentReport->moderator ? 'A moderator is reviewing your report.' : 'Waiting for a moderator to review.',
                'complete' => in_array($incidentReport->status, ['under_review', 'resolved', 'dismissed']),
            ],
            [
                'label' => 'Outcome shared',
                'date' => $incidentReport->moderation_date,
                'description' => $incidentReport->action_taken ? 'Moderation outcome recorded.' : 'Pending final decision.',
                'complete' => in_array($incidentReport->status, ['resolved', 'dismissed']),
            ],
        ];
    @endphp

    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-end">
                <a href="{{ route('incident-reports.index') }}" class="btn-muted">
                    Back to reports
                </a>
            </div>

            <div class="card-surface p-6 lg:p-8 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col gap-4 border-b border-gray-100 dark:border-gray-700 pb-6 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="section-heading">Incident Report #{{ $incidentReport->reportId }}</p>
                        <h3 class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">Detailed summary</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Submitted on {{ $incidentReport->report_date->format('F d, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <div class="flex flex-col items-start gap-3 md:items-end">
                        <span class="{{ $statusClasses[$incidentReport->status] ?? 'badge-soft badge-soft-slate' }}">
                            {{ ucwords(str_replace('_', ' ', $incidentReport->status)) }}
                        </span>
                        <div class="flex flex-wrap gap-2">
                            @if($incidentReport->isPending() && Auth::user() && Auth::user()->isAdmin())
                                <a href="{{ route('admin.incident-reports.edit', $incidentReport) }}" class="btn-brand">
                                    Moderate report
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4 md:col-span-2">
                        <p class="section-heading">Reported user</p>
                        <div class="mt-3 flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-peach/60 text-base font-semibold text-brand-orange-dark">
                                {{ strtoupper(substr($incidentReport->reportedUser->firstName, 0, 1)) }}{{ strtoupper(substr($incidentReport->reportedUser->lastName, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $incidentReport->reportedUser->fullName }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $incidentReport->reportedUser->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
                        <p class="section-heading">Incident type</p>
                        <span class="{{ $typeClasses[$incidentReport->incident_type] ?? 'badge-soft badge-soft-slate' }} mt-3 inline-flex">
                            {{ ucwords(str_replace('_', ' ', $incidentReport->incident_type)) }}
                        </span>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 lg:grid-cols-3">
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 p-4">
                        <p class="section-heading">Reported on</p>
                        <p class="mt-3 text-base font-semibold text-gray-900 dark:text-white">{{ $incidentReport->report_date->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $incidentReport->report_date->format('g:i A') }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 p-4">
                        <p class="section-heading">Current status</p>
                        <p class="mt-3 text-base font-semibold text-gray-900 dark:text-white">{{ ucwords(str_replace('_', ' ', $incidentReport->status)) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">We’ll notify you when this updates.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 p-4">
                        <p class="section-heading">Moderator assigned</p>
                        <p class="mt-3 text-base font-semibold text-gray-900 dark:text-white">
                            {{ $incidentReport->moderator ? $incidentReport->moderator->fullName : 'Pending assignment' }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $incidentReport->moderator ? 'Review in progress' : 'Awaiting moderator' }}
                        </p>
                    </div>
                </div>

                <div class="mt-10">
                    <p class="section-heading mb-4">Review timeline</p>
                    <ol class="relative space-y-6 border-l border-gray-200 dark:border-gray-700 pl-6">
                        @foreach($timeline as $step)
                            <li class="ml-4">
                                <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full {{ $step['complete'] ? 'bg-brand-teal text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' }}">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['complete'] ? 'M5 13l4 4L19 7' : 'M12 8v4l2 2' }}"></path>
                                    </svg>
                                </span>
                                <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/40 p-4">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $step['label'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $step['date'] ? $step['date']->format('M d, Y g:i A') : 'Pending' }}
                                        </p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $step['description'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                @if($incidentReport->task)
                    <div class="mt-10">
                        <p class="section-heading mb-3">Related task</p>
                        <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <a href="{{ route('tasks.show', $incidentReport->task) }}" class="text-base font-semibold text-brand-teal hover:text-brand-teal-dark dark:text-brand-peach dark:hover:text-brand-peach-dark">
                                        {{ $incidentReport->task->title }}
                                    </a>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ Str::limit($incidentReport->task->description, 220) }}</p>
                                </div>
                                <span class="badge-soft badge-soft-teal">{{ ucwords(str_replace('_', ' ', $incidentReport->task->task_type)) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-10">
                    <p class="section-heading mb-3">Description</p>
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/60 p-5">
                        <p class="text-sm leading-relaxed text-gray-800 dark:text-gray-100 whitespace-pre-wrap">{{ $incidentReport->description }}</p>
                    </div>
                </div>

                @if($incidentReport->evidence)
                    <div class="mt-10">
                        <p class="section-heading mb-3">Additional evidence</p>
                        <div class="grid gap-4 lg:grid-cols-2">
                            @php
                                $evidenceLines = preg_split("/\r?\n/", (string) $incidentReport->evidence);
                            @endphp
                            @foreach($evidenceLines as $line)
                                @php $trimmed = trim($line); @endphp
                                @if($trimmed !== '')
                                    @if(strpos($trimmed, 'Image: ') === 0)
                                        @php $path = trim(substr($trimmed, 7)); @endphp
                                        <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
                                            <img src="{{ asset('storage/' . $path) }}" alt="Incident evidence image" class="h-64 w-full rounded-2xl object-cover">
                                            <div class="mt-2 text-xs text-gray-500 break-all">{{ $path }}</div>
                                        </div>
                                    @else
                                        <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/60 p-4">
                                            <p class="text-sm text-gray-800 dark:text-gray-100 whitespace-pre-wrap">{{ $trimmed }}</p>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($incidentReport->moderator)
                    <div class="mt-10">
                        <p class="section-heading mb-3">Moderation details</p>
                        <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/60 p-5 space-y-6">
                            <div class="grid gap-6 md:grid-cols-2">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Moderator</p>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $incidentReport->moderator->fullName }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Moderation date</p>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $incidentReport->moderation_date->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>

                            @if($incidentReport->action_taken)
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Action taken</p>
                                    <span class="mt-2 inline-flex {{ in_array($incidentReport->action_taken, ['suspension', 'no_action']) ? 'badge-soft badge-soft-teal' : 'badge-soft badge-soft-orange' }}">
                                        {{ ucwords(str_replace('_', ' ', $incidentReport->action_taken)) }}
                                    </span>
                                </div>
                            @endif

                            @if($incidentReport->moderator_notes)
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Moderator notes</p>
                                    <p class="mt-2 text-sm text-gray-800 dark:text-gray-100 whitespace-pre-wrap">{{ $incidentReport->moderator_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
