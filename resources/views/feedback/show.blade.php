<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Task Feedback') }}
            </h2>
            <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-brand-teal/40 text-brand-teal font-medium rounded-xl hover:bg-brand-teal/10 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Task
            </a>
        </div>
    </x-slot>

    @php
        $feedbackCount = $userFeedback->count();
        $averageRating = $feedbackCount ? round($userFeedback->avg('rating'), 1) : 0;
        $lastUpdatedRaw = $feedbackCount ? $userFeedback->max('feedback_date') : null;
        if (is_string($lastUpdatedRaw)) {
            $lastUpdated = \Carbon\Carbon::parse($lastUpdatedRaw)->format('M j, Y');
        } elseif ($lastUpdatedRaw instanceof \Carbon\CarbonInterface) {
            $lastUpdated = $lastUpdatedRaw->format('M j, Y');
        } else {
            $lastUpdated = 'Not available';
        }
    @endphp

    <div class="min-h-screen bg-white dark:bg-gray-950 transition-colors duration-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            <!-- Task Summary -->
            <div class="bg-white/95 dark:bg-gray-900/70 backdrop-blur rounded-2xl shadow-xl border border-brand-peach/60 dark:border-gray-800 p-6">
                <div class="flex flex-col gap-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $task->title }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $task->description }}</p>
                    <div class="flex flex-wrap gap-3 text-xs font-semibold text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-teal/10 text-brand-teal-dark">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Active feedback stream
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-peach/70 text-brand-orange-dark">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10M11 20h2"/>
                            </svg>
                            {{ $feedbackCount }} responses
                        </span>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-brand-peach/70 bg-white/80 dark:bg-gray-900/70 p-5 shadow">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Average Rating</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-brand-orange">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">/ 5</span>
                    </div>
                </div>
                <div class="rounded-2xl border border-brand-teal/50 bg-white/80 dark:bg-gray-900/70 p-5 shadow">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Responses</p>
                    <span class="mt-2 block text-3xl font-bold text-brand-teal">{{ $feedbackCount }}</span>
                </div>
                <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-900/70 p-5 shadow">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Last Updated</p>
                    <span class="mt-2 block text-lg font-semibold text-gray-900 dark:text-white">{{ $feedbackCount ? $lastUpdated : 'Not available' }}</span>
                </div>
            </div>

            <!-- User Feedback List -->
            <div class="bg-white/95 dark:bg-gray-900/80 rounded-2xl shadow-2xl border border-brand-peach/60 dark:border-gray-800 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Feedback ({{ $feedbackCount }})</h4>
                    @if($feedbackCount)
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-brand-peach/70 text-brand-orange-dark">
                            Sorted by recency
                        </span>
                    @endif
                </div>

                @if($feedbackCount > 0)
                    <div class="space-y-4">
                        @foreach($userFeedback as $feedback)
                            @php
                                $displayDate = is_string($feedback->feedback_date)
                                    ? \Carbon\Carbon::parse($feedback->feedback_date)
                                    : $feedback->feedback_date;
                            @endphp
                            <article class="relative flex gap-4 rounded-2xl border border-gray-100 dark:border-gray-800 p-4 hover:border-brand-peach/70 transition bg-white/70 dark:bg-gray-900/60">
                                <div class="flex flex-col items-center">
                                    <div class="w-2 h-2 rounded-full bg-brand-teal"></div>
                                    <div class="flex-1 w-px bg-gradient-to-b from-brand-teal/50 to-transparent mt-2"></div>
                                </div>
                                <div class="flex-1 space-y-3">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <x-user-avatar
                                                :user="$feedback->user"
                                                size="h-10 w-10"
                                                text-size="text-sm"
                                                class="bg-brand-peach/70 text-brand-orange-dark"
                                            />
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $feedback->user->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $feedback->user->email }}</p>
                                            </div>
                                        </div>
                                        <x-rating-stars :value="$feedback->rating" size="sm">
                                            <span class="ml-2 text-xs font-semibold text-brand-orange-dark">{{ number_format($feedback->rating, 1) }}/5</span>
                                        </x-rating-stars>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-200 bg-brand-peach/20 dark:bg-gray-900/50 rounded-xl px-4 py-3 border border-brand-peach/60 dark:border-gray-800">
                                        {{ $feedback->comment }}
                                    </p>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-brand-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $displayDate?->format('M j, Y \a\t g:i A') }}
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-brand-peach/20 border border-dashed border-brand-peach/70 rounded-2xl">
                        <svg class="mx-auto h-12 w-12 text-brand-orange-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="mt-3 text-sm font-medium text-gray-900 dark:text-white">No feedback yet</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Encourage teammates to share their thoughts on this task.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
