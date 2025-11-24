<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-gray-900 leading-tight">{{ __('Task Feedback Details') }}</h2>
            </div>
            <a href="{{ route('admin.feedback.index') }}" class="btn-muted text-sm px-4 py-2 w-fit">
                Back to Feedback
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="card-surface p-6 space-y-6">
                <div class="flex flex-col gap-3">
                    <p class="text-sm font-semibold text-brand-teal uppercase tracking-wide">Task</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $task->title }}</h3>
                    <p class="text-gray-600">{{ $task->description }}</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-2xl border border-brand-peach/60 bg-brand-peach/20 px-4 py-3">
                        <p class="text-xs font-semibold uppercase text-brand-orange-dark tracking-wide">Points</p>
                        <p class="text-2xl font-semibold text-brand-orange-dark">{{ $task->points_awarded }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 px-4 py-3">
                        <p class="text-xs font-semibold uppercase text-gray-500 tracking-wide">Type</p>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $task->task_type)) }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 px-4 py-3">
                        <p class="text-xs font-semibold uppercase text-gray-500 tracking-wide">Status</p>
                        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-brand-teal/10 px-3 py-1 text-sm font-semibold text-brand-teal">
                            <span class="h-2 w-2 rounded-full bg-brand-teal"></span>
                            {{ ucfirst($task->status) }}
                        </span>
                    </div>
                    <div class="rounded-2xl border border-gray-200 px-4 py-3">
                        <p class="text-xs font-semibold uppercase text-gray-500 tracking-wide">Participants</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $task->assignments->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="card-surface p-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h4 class="text-2xl font-semibold text-gray-900">Feedback ({{ $userFeedback->count() }})</h4>
                        <p class="text-sm text-gray-500">Real comments from participants who completed this task.</p>
                    </div>
                </div>

                @if($userFeedback->count() > 0)
                    <ol class="mt-8 space-y-6">
                        @foreach($userFeedback as $feedback)
                            <li class="relative pl-8">
                                <span class="absolute left-3 top-2 h-full border-l border-gray-200 last:hidden"></span>
                                <div class="card-surface border border-gray-100 p-4">
                                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="h-11 w-11 rounded-full bg-brand-teal/15 text-brand-teal flex items-center justify-center font-semibold uppercase">
                                                {{ substr($feedback->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $feedback->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $feedback->user->email }}</p>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center gap-2 rounded-full bg-brand-peach/60 px-3 py-1 text-sm font-semibold text-brand-orange-dark">
                                            <x-rating-stars :value="$feedback->rating" size="sm" />
                                            <span>{{ number_format($feedback->rating, 1) }}/5</span>
                                        </div>
                                    </div>
                                    <p class="mt-4 text-sm text-gray-700">{{ $feedback->comment }}</p>
                                    <p class="mt-3 text-xs text-gray-500">
                                        {{ $feedback->feedback_date->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <div class="mt-8 text-center py-10">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                            <i class="fas fa-comments text-2xl"></i>
                        </div>
                        <p class="mt-4 text-lg font-semibold text-gray-900">No user feedback yet</p>
                        <p class="text-sm text-gray-500">As responses arrive they will be listed here automatically.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
