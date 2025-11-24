<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors" style="color: white !important;">
                <i class="fas fa-arrow-left" style="color: white !important;"></i>
                Back to Tasks
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            
            @php 
                $showAdminLayout = $isCreator && $task->task_type === 'user_uploaded';
            @endphp
            
            @if($showAdminLayout)
                @php
                    $creatorFeedbackEntries = $feedbackEntries ?? collect();
                    $creatorFeedbackSummary = $feedbackSummary ?? [
                        'average_rating' => null,
                        'total' => 0,
                        'latest' => null,
                    ];
                    $creatorActiveTab = ($activeCreatorTab ?? 'overview') === 'feedback' ? 'feedback' : 'overview';
                @endphp
                <!-- Admin-style layout for creators of user-uploaded tasks -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <!-- Task Header -->
                    <div class="px-6 py-6" style="background: linear-gradient(135deg, #F3A261 0%, #2B9D8D 100%);">
                        <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                            <div class="flex-1">
                                <h1 class="text-2xl lg:text-3xl font-bold text-white mb-3">{{ $task->title }}</h1>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-full bg-white/20 text-white">
                                        <i class="fas 
                                            @if($task->status === 'pending') fa-clock
                                            @elseif($task->status === 'approved') fa-check-circle
                                            @elseif($task->status === 'published') fa-rocket
                                            @elseif($task->status === 'assigned') fa-user-check
                                            @elseif($task->status === 'submitted') fa-paper-plane
                                            @elseif($task->status === 'completed') fa-check-double
                                            @elseif($task->status === 'rejected') fa-times-circle
                                            @elseif($task->status === 'uncompleted') fa-exclamation-triangle
                                            @elseif($task->status === 'inactive') fa-pause-circle
                                            @else fa-list
                                            @endif text-xs
                                        "></i>
                                        {{ ucfirst($task->status) }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-full bg-white/20 text-white">
                                        <i class="fas 
                                            @if($task->task_type === 'daily') fa-calendar-day
                                            @elseif($task->task_type === 'one_time') fa-bullseye
                                            @else fa-user-plus
                                            @endif text-xs
                                        "></i>
                                        {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="bg-white/20 rounded-lg px-6 py-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-white mb-1">{{ $task->points_awarded }}</div>
                                    <div class="text-xs text-white/90 font-medium">Points</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Creator Tabs -->
                    <div class="border-b border-gray-200 bg-white">
                        <nav class="flex space-x-8 px-6" aria-label="Creator tabs">
                            @php
                                $overviewActive = $creatorActiveTab === 'overview';
                                $feedbackActive = $creatorActiveTab === 'feedback';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'overview']) }}"
                               class="py-4 px-1 border-b-2 text-sm transition-colors {{ $overviewActive ? 'font-bold text-gray-900' : 'font-semibold text-gray-500 hover:text-gray-700 border-transparent' }}"
                               style="border-color: {{ $overviewActive ? '#F3A261' : 'transparent' }};"
                               aria-current="{{ $overviewActive ? 'page' : 'false' }}">
                                Overview
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'feedback']) }}"
                               class="py-4 px-1 border-b-2 text-sm transition-colors {{ $feedbackActive ? 'font-bold text-gray-900' : 'font-semibold text-gray-500 hover:text-gray-700 border-transparent' }}"
                               style="border-color: {{ $feedbackActive ? '#F3A261' : 'transparent' }};"
                               aria-current="{{ $feedbackActive ? 'page' : 'false' }}">
                                Feedback
                                @if($creatorFeedbackSummary['total'] > 0)
                                    <span class="ml-2 inline-flex items-center justify-center rounded-full bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-0.5">
                                        {{ $creatorFeedbackSummary['total'] }}
                                    </span>
                                @endif
                            </a>
                        </nav>
                    </div>

                    <!-- Task Content Section -->
                    @if($overviewActive)
                    <div id="creator-overview-content" class="creator-tab-content px-6 py-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Main Content Column -->
                            <div class="lg:col-span-2 space-y-4">
                                <!-- Description -->
                                <div class="bg-white rounded-lg p-5 border border-gray-200 shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                                    <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $task->description }}</p>
                                </div>

                            <!-- Task Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($task->creation_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-calendar-alt" style="color: #F3A261;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Created</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y \a\t g:i A') : $task->creation_date->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                @endif

                                @if($task->approval_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-check-square" style="color: #2B9D8D;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Approved</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ is_string($task->approval_date) ? \Carbon\Carbon::parse($task->approval_date)->format('M j, Y \a\t g:i A') : $task->approval_date->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                @endif

                                @if($task->published_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-bullhorn" style="color: #2B9D8D;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Published</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ is_string($task->published_date) ? \Carbon\Carbon::parse($task->published_date)->format('M j, Y \a\t g:i A') : $task->published_date->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                @endif

                                @if($task->due_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-hourglass-end text-gray-600"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Due Date</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        @php
                                            $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                            if ($task->end_time) {
                                                $deadline = \Carbon\Carbon::parse($dueDate->toDateString() . ' ' . $task->end_time);
                                                echo $deadline->format('M j, Y \a\t g:i A');
                                            } else {
                                                echo $dueDate->format('M j, Y \a\t g:i A');
                                            }
                                        @endphp
                                    </p>
                                </div>
                                @endif

                                @if($task->location)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-map-marker-alt" style="color: #2B9D8D;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Location</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $task->location }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Sidebar Column -->
                        <div class="lg:col-span-1 space-y-6">
                            <!-- Participants Section -->
                            <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-users" style="color: #F3A261;"></i>
                                        Participants
                                    </h3>
                                    <span class="px-3 py-1 text-white text-sm font-bold rounded-full" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                                        {{ $task->assignments->count() }}
                                    </span>
                                </div>

                                @if($task->assignments->count() > 0)
                                    @php
                                        $displayLimit = 3;
                                        $displayedParticipants = $task->assignments->take($displayLimit);
                                        $hasMore = $task->assignments->count() > $displayLimit;
                                    @endphp
                                    <!-- Limited Participants List -->
                                    <div class="space-y-3 mb-4">
                                        @foreach($displayedParticipants as $assignment)
                                            @php
                                                $firstName = $assignment->user->firstName ?? '';
                                                $lastName = $assignment->user->lastName ?? '';
                                                $initials = strtoupper(
                                                    (!empty($firstName) ? substr($firstName, 0, 1) : '') . 
                                                    (!empty($lastName) ? substr($lastName, 0, 1) : '')
                                                );
                                                if (empty($initials)) {
                                                    $initials = strtoupper(substr($assignment->user->name ?? 'U', 0, 1));
                                                }
                                            @endphp
                                            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                    <div class="h-10 w-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                                                        <span class="text-sm font-bold text-white">
                                                            {{ $initials }}
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $assignment->user->name }}</p>
                                                        <p class="text-xs text-gray-500 truncate">{{ $assignment->user->email }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2 flex-shrink-0 ml-3">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full
                                                        @if($assignment->status === 'assigned') bg-orange-100 text-orange-800
                                                        @elseif($assignment->status === 'submitted') bg-yellow-100 text-yellow-800
                                                        @elseif($assignment->status === 'completed') bg-teal-100 text-teal-800
                                                        @endif">
                                                        <i class="fas 
                                                            @if($assignment->status === 'assigned') fa-user-check
                                                            @elseif($assignment->status === 'submitted') fa-paper-plane
                                                            @elseif($assignment->status === 'completed') fa-check-circle
                                                            @endif"></i>
                                                        {{ ucfirst($assignment->status) }}
                                                    </span>
                                                    @if(!empty($assignment->progress))
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full
                                                        @switch($assignment->progress)
                                                            @case('accepted') bg-gray-100 text-gray-800 @break
                                                            @case('on_the_way') text-white @break
                                                            @php $bgColor = '#2B9D8D'; @endphp
                                                            @case('working') text-white @break
                                                            @php $bgColor = '#2B9D8D'; @endphp
                                                            @case('done') text-white @break
                                                            @php $bgColor = '#2B9D8D'; @endphp
                                                            @case('submitted_proof') text-white @break
                                                            @php $bgColor = '#F3A261'; @endphp
                                                            @default bg-gray-100 text-gray-800
                                                        @endswitch">
                                                        <i class="fas 
                                                            @if($assignment->progress === 'accepted') fa-handshake
                                                            @elseif($assignment->progress === 'on_the_way') fa-route
                                                            @elseif($assignment->progress === 'working') fa-tools
                                                            @elseif($assignment->progress === 'done') fa-clipboard-check
                                                            @elseif($assignment->progress === 'submitted_proof') fa-file-upload
                                                            @endif"></i>
                                                        {{ ucfirst(str_replace('_',' ', $assignment->progress)) }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($assignment->status === 'submitted')
                                                <div class="ml-11 mb-3">
                                                    <a href="{{ route('tasks.creator.show', $assignment) }}" class="text-sm" style="color: #2B9D8D;" onmouseover="this.style.color='#248A7C';" onmouseout="this.style.color='#2B9D8D';">
                                                        Review Submission →
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    <button type="button" onclick="openParticipantsModal()" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg text-white shadow-md transition-all hover:shadow-lg transform hover:-translate-y-0.5"
                                            style="background-color: #F3A261;"
                                            onmouseover="this.style.backgroundColor='#E8944F'"
                                            onmouseout="this.style.backgroundColor='#F3A261'">
                                        <i class="fas fa-eye"></i>
                                        View All ({{ $task->assignments->count() }})
                                    </button>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-users-slash text-gray-300 text-3xl mb-3"></i>
                                        <p class="text-sm text-gray-500 font-medium">No users have joined this task yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($feedbackActive)
                    <div id="creator-feedback-content" class="creator-tab-content px-6 py-6">
                        <div class="space-y-8">
                            <div class="bg-white/95 rounded-2xl shadow-xl border border-brand-peach/40 p-6">
                                <div class="flex flex-col gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900">Participant Sentiment</h3>
                                    <p class="text-sm text-gray-600">A quick glance at how your volunteers feel about this task.</p>
                                    <div class="flex flex-wrap gap-3 text-xs font-semibold text-gray-500">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-teal/10 text-brand-teal-dark">
                                            <i class="fas fa-wave-square"></i>
                                            Live feedback stream
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-peach/70 text-brand-orange-dark">
                                            <i class="fas fa-comments"></i>
                                            {{ $creatorFeedbackSummary['total'] }} responses
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="rounded-2xl border border-brand-peach/70 bg-white/80 p-5 shadow">
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Average Rating</p>
                                    <div class="mt-2 flex items-baseline gap-2">
                                        <span class="text-3xl font-bold text-brand-orange">
                                            {{ $creatorFeedbackSummary['average_rating'] ? number_format($creatorFeedbackSummary['average_rating'], 1) : '—' }}
                                        </span>
                                        <span class="text-sm text-gray-500">/ 5</span>
                                    </div>
                                    @if($creatorFeedbackSummary['average_rating'])
                                        <x-rating-stars :value="$creatorFeedbackSummary['average_rating']" size="sm" class="mt-2" />
                                    @else
                                        <p class="text-sm text-gray-500 mt-2">No ratings yet.</p>
                                    @endif
                                </div>
                                <div class="rounded-2xl border border-brand-teal/50 bg-white/80 p-5 shadow">
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Total Responses</p>
                                    <span class="mt-2 block text-3xl font-bold text-brand-teal">{{ $creatorFeedbackSummary['total'] }}</span>
                                    <p class="text-sm text-gray-500">from task participants</p>
                                </div>
                                <div class="rounded-2xl border border-gray-200 bg-white/80 p-5 shadow">
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Most Recent</p>
                                    @if($creatorFeedbackSummary['latest'])
                                        <p class="mt-2 text-base font-semibold text-gray-900">
                                            {{ $creatorFeedbackSummary['latest']->user->name ?? 'Unknown User' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ optional($creatorFeedbackSummary['latest']->feedback_date ?? $creatorFeedbackSummary['latest']->created_at)->format('M j, Y \\a\\t g:i A') }}
                                        </p>
                                    @else
                                        <p class="mt-2 text-sm text-gray-500">No feedback submitted yet.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-white/95 rounded-2xl shadow-2xl border border-brand-peach/60 p-6">
                                <div class="flex flex-col gap-2 mb-6 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">Participant Feedback</h4>
                                        <p class="text-sm text-gray-500">Sorted by latest responses</p>
                                    </div>
                                    @if($creatorFeedbackSummary['total'] > 0)
                                        <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1 rounded-full bg-brand-peach/70 text-brand-orange-dark">
                                            <i class="fas fa-bolt"></i>
                                            Fresh insights
                                        </span>
                                    @endif
                                </div>

                                @if($creatorFeedbackSummary['total'] > 0)
                                    <div class="space-y-4">
                                        @foreach($creatorFeedbackEntries as $feedback)
                                            @php
                                                $feedbackTimestamp = $feedback->feedback_date ?? $feedback->created_at;
                                            @endphp
                                            <article class="relative flex gap-4 rounded-2xl border border-gray-100 p-4 hover:border-brand-peach/70 transition bg-white/80">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-2 h-2 rounded-full bg-brand-teal"></div>
                                                    <div class="flex-1 w-px bg-gradient-to-b from-brand-teal/50 to-transparent mt-2"></div>
                                                </div>
                                                <div class="flex-1 space-y-3">
                                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                                        <div class="flex items-center gap-3">
                                                            <div class="h-10 w-10 rounded-full bg-brand-peach/70 text-brand-orange-dark flex items-center justify-center text-sm font-semibold">
                                                                {{ \Illuminate\Support\Str::of($feedback->user->name ?? 'UU')->substr(0, 2)->upper() }}
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-semibold text-gray-900">{{ $feedback->user->name ?? 'Unknown User' }}</p>
                                                                <p class="text-xs text-gray-500">{{ $feedback->user->email ?? 'No email provided' }}</p>
                                                            </div>
                                                        </div>
                                                        <x-rating-stars :value="$feedback->rating" size="sm">
                                                            <span class="ml-2 text-xs font-semibold text-brand-orange-dark">{{ number_format($feedback->rating, 1) }}/5</span>
                                                        </x-rating-stars>
                                                    </div>
                                                    <p class="text-sm text-gray-700 bg-brand-peach/20 rounded-xl px-4 py-3 border border-brand-peach/40">
                                                        {{ $feedback->comment }}
                                                    </p>
                                                    <div class="text-xs text-gray-500 flex items-center gap-2">
                                                        <i class="fas fa-clock text-brand-teal"></i>
                                                        {{ optional($feedbackTimestamp)->format('M j, Y \\a\\t g:i A') }}
                                                    </div>
                                                </div>
                                            </article>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-12 bg-brand-peach/20 border border-dashed border-brand-peach/70 rounded-2xl">
                                        <i class="fas fa-comments text-brand-orange-dark text-4xl mb-4"></i>
                                        <p class="text-sm font-medium text-gray-900">No feedback yet</p>
                                        <p class="text-sm text-gray-600">Encourage participants to submit their experience once they finish.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <!-- Regular user layout with tabs -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <!-- Task Header -->
                    <div class="px-6 py-6" style="background: linear-gradient(135deg, #F3A261 0%, #2B9D8D 100%);">
                        <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                            <div class="flex-1">
                                <h1 class="text-2xl lg:text-3xl font-bold text-white mb-3">
                                    {{ $task->title }}
                                </h1>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-full bg-white/20 text-white">
                                        <i class="fas 
                                            @if($task->status === 'pending') fa-clock
                                            @elseif($task->status === 'approved') fa-check-circle
                                            @elseif($task->status === 'published') fa-rocket
                                            @elseif($task->status === 'assigned') fa-user-check
                                            @elseif($task->status === 'submitted') fa-paper-plane
                                            @elseif($task->status === 'completed') fa-check-double
                                            @elseif($task->status === 'rejected') fa-times-circle
                                            @elseif($task->status === 'uncompleted') fa-exclamation-triangle
                                            @elseif($task->status === 'inactive') fa-pause-circle
                                            @else fa-list
                                            @endif text-xs
                                        "></i>
                                        {{ ucfirst($task->status) }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-full bg-white/20 text-white">
                                        <i class="fas 
                                            @if($task->task_type === 'daily') fa-calendar-day
                                            @elseif($task->task_type === 'one_time') fa-bullseye
                                            @else fa-user-plus
                                            @endif text-xs
                                        "></i>
                                        {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                    </span>
                                    @php $uploader = $task->assignedUser; @endphp
                                    @if($uploader)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-full bg-white/20 text-white">
                                        <i class="fas fa-user text-xs"></i>
                                        Uploaded by: {{ $uploader->name ?? 'Admin' }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-white/20 rounded-lg px-6 py-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-white mb-1">
                                        {{ $task->points_awarded }}
                                    </div>
                                    <div class="text-xs text-white/90 font-medium">Points</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="border-b border-gray-200 bg-white">
                        <nav class="flex space-x-8 px-6" aria-label="Tabs">
                            <button id="details-tab" class="py-4 px-1 border-b-2 font-bold text-sm text-gray-900 transition-colors" style="border-color: #F3A261;">
                                Details
                            </button>
                            <button id="participants-tab" class="py-4 px-1 border-b-2 border-transparent font-semibold text-sm text-gray-500 hover:text-gray-700 transition-colors">
                                Participants
                            </button>
                        </nav>
                    </div>

                <!-- Tab Content -->
                <div class="px-6 py-6">
                    <!-- Details Tab Content -->
                    <div id="details-content" class="tab-content">
                        <!-- Task Description -->
                        <div class="mb-6">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $task->description }}</p>
                        </div>

                        <!-- Task Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @if($task->due_date)
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-calendar-alt" style="color: #F3A261;"></i>
                                    <h4 class="text-xs font-semibold text-gray-600 uppercase">Date</h4>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                </p>
                            </div>
                            @elseif($task->creation_date)
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-calendar-alt" style="color: #F3A261;"></i>
                                    <h4 class="text-xs font-semibold text-gray-600 uppercase">Created</h4>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                </p>
                            </div>
                            @endif
                            
                            @if($task->start_time || $task->end_time)
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-clock" style="color: #2B9D8D;"></i>
                                    <h4 class="text-xs font-semibold text-gray-600 uppercase">Time</h4>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    @if($task->start_time && $task->end_time)
                                        {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('g:i A') }}
                                    @elseif($task->start_time)
                                        {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }} onwards
                                    @else
                                        Flexible
                                    @endif
                                </p>
                            </div>
                            @endif
                            
                            @if($task->location)
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-map-marker-alt" style="color: #2B9D8D;"></i>
                                    <h4 class="text-xs font-semibold text-gray-600 uppercase">Location</h4>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $task->location }}
                                </p>
                            </div>
                            @endif
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-star" style="color: #F3A261;"></i>
                                    <h4 class="text-xs font-semibold text-gray-600 uppercase">Points</h4>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $task->points_awarded }}
                                </p>
                            </div>
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-tag" style="color: #2B9D8D;"></i>
                                    <h4 class="text-xs font-semibold text-gray-600 uppercase">Type</h4>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                </p>
                            </div>
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-users" style="color: #F3A261;"></i>
                                    <h4 class="text-xs font-semibold text-gray-600 uppercase">Participants</h4>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    @if($task->max_participants !== null)
                                        {{ $task->assignments->count() }} / {{ $task->max_participants }}
                                    @else
                                        {{ $task->assignments->count() }} (unlimited)
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Join Task Button - Only show if user hasn't joined the task and is not the creator -->
                        @if(!$task->isAssignedTo(Auth::id()) && !$isCreator && $task->status === 'published')
                        <div class="mb-6 text-center">
                            @php
                                $isFull = !is_null($task->max_participants) && $task->assignments->count() >= $task->max_participants;
                            @endphp
                            @if($isFull)
                                <button type="button" class="bg-gray-400 text-white font-bold py-3 px-8 rounded-lg cursor-not-allowed shadow-md" title="This task has reached its participant limit" aria-disabled="true">
                                    <i class="fas fa-users-slash mr-2"></i>Join This Task
                                </button>
                            @else
                                <form action="{{ route('tasks.join', $task) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 border border-transparent text-sm font-bold rounded-lg text-white shadow-md transition-colors brand-primary-btn">
                                        <i class="fas fa-user-plus"></i> Join This Task
                                    </button>
                                </form>
                            @endif
                        </div>
                        @endif
                        @if($isCreator)
                        <div class="mb-6 text-center">
                            <button type="button" class="bg-gray-400 text-white font-bold py-3 px-8 rounded-lg cursor-not-allowed shadow-md" title="You created this task" aria-disabled="true">
                                <i class="fas fa-user-check mr-2"></i>Join This Task
                            </button>
                        </div>
                        @endif

                        <!-- Task Status Section - Only show if user has joined the task -->
                        @if($task->isAssignedTo(Auth::id()))
                            @php
                                $userAssignment = $task->assignments->where('userId', Auth::id())->first();
                            @endphp
                            
                            <!-- Progress tracker -->
                            @php
                                $steps = ['accepted' => 'Accepted', 'on_the_way' => 'On the way', 'working' => 'Working', 'done' => 'Task done', 'submitted_proof' => 'Submitted proof'];
                                $current = $userAssignment->progress ?? 'accepted';
                                $stepKeys = array_keys($steps);
                                $currentIndex = array_search($current, $stepKeys);
                                if ($currentIndex === false) { $currentIndex = 0; }
                            @endphp
                            <div class="mb-6">
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                    @php $percent = ($currentIndex) * (100 / (count($steps) - 1)); @endphp
                                    <div class="h-2 rounded-full" style="width: {{ $percent }}%; background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);"></div>
                                </div>
                                <div class="flex justify-between">
                                    @foreach($steps as $key => $label)
                                        @php $index = array_search($key, $stepKeys); $active = $index <= $currentIndex; @endphp
                                        <div class="text-xs {{ $active ? 'text-gray-900 font-semibold' : 'text-gray-500' }}">
                                            {{ $label }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Progress actions (except submitted_proof which is set by submit) -->
                            @if($userAssignment->status === 'assigned')
                                <div class="mb-6 flex flex-wrap gap-2">
                                    @foreach(['accepted','on_the_way','working','done'] as $p)
                                        @php
                                            $order = ['accepted','on_the_way','working','done'];
                                            $curr = $userAssignment->progress ?? 'accepted';
                                            $currIdx = array_search($curr, $order);
                                            $btnIdx = array_search($p, $order);
                                            // Only allow the immediate next step; disable current, previous, or skipping ahead
                                            $disabled = $btnIdx === false || $currIdx === false || $btnIdx !== $currIdx + 1;
                                            $progressLabel = ucfirst(str_replace('_',' ', $p));
                                        @endphp
                                        <form id="progress-form-{{ $p }}" action="{{ route('tasks.progress', $task) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="progress" value="{{ $p }}">
                                            <button type="button" 
                                                    onclick="showProgressModal('{{ $progressLabel }}', '{{ $p }}')" 
                                                    {{ $disabled ? 'disabled' : '' }} 
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border-2 text-sm font-bold transition-all duration-200 {{ ($userAssignment->progress ?? 'accepted') === $p ? 'text-white border-transparent shadow-md brand-primary-btn' : 'bg-white text-gray-700 border-gray-300 hover:border-orange-400' }} {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}">
                                                <i class="fas 
                                                    @if($p === 'accepted') fa-handshake
                                                    @elseif($p === 'on_the_way') fa-route
                                                    @elseif($p === 'working') fa-tools
                                                    @elseif($p === 'done') fa-clipboard-check
                                                    @endif"></i>
                                                {{ $progressLabel }}
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($userAssignment->status === 'submitted')
                                <!-- Pending Approval Status -->
                                <div class="mb-6">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-clock text-yellow-500 text-5xl mb-4"></i>
                                            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Pending Approval</h3>
                                            <p class="text-sm text-yellow-700 mb-4">
                                                @if($task->task_type === 'user_uploaded')
                                                    Your task completion has been submitted and is waiting for the task creator's approval.
                                                @else
                                                    Your task completion has been submitted and is waiting for admin approval.
                                                @endif
                                            </p>
                                            @if($userAssignment->submitted_at)
                                                <p class="text-xs text-yellow-600">
                                                    Submitted on {{ is_string($userAssignment->submitted_at) ? \Carbon\Carbon::parse($userAssignment->submitted_at)->format('M j, Y \a\t g:i A') : $userAssignment->submitted_at->format('M j, Y \a\t g:i A') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- User Submission Preview -->
                                    <div class="mt-6 text-left">
                                        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Your Submission</h4>
                                        @if(($userAssignment->completion_notes ?? null))
                                            <div class="mb-4 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                                                {{ $userAssignment->completion_notes }}
                                            </div>
                                        @endif
                                        @php
                                            $photos = $userAssignment->photos ?? [];
                                        @endphp
                                        @if(is_array($photos) && count($photos) > 0)
                                            <div class="mb-4">
                                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                                    @foreach($photos as $index => $photo)
                                                        @php 
                                                            // Build a robust relative URL to avoid APP_URL issues
                                                            $photoUrl = \Illuminate\Support\Str::startsWith($photo, ['http://','https://','/'])
                                                                ? $photo
                                                                : '/storage/' . ltrim($photo, '/');
                                                        @endphp
                                                        <div class="relative group">
                                                            <div class="relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200" onclick="openImageModal('{{ $photoUrl }}')">
                                                                <img src="{{ $photoUrl }}" 
                                                                    alt="Task completion proof {{ $index + 1 }}" 
                                                                    class="w-full h-28 object-cover cursor-pointer hover:scale-105 transition-transform duration-200"
                                                                    data-photo-url="{{ $photoUrl }}"
                                                                    onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjgwIiB2aWV3Qm94PSIwIDAgMTAwIDgwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iODAiIHJ4PSIxMiIgZmlsbD0iI0YzRjRGNiIvPjxwYXRoIGQ9Ik0zMCAzMEg3MFY1MEgzMFYzMFoiIHN0cm9rZT0iIzkzOTM5MyIgc3Ryb2tlLXdpZHRoPSIyIi8+PHBhdGggZD0iTTQyIDQyTDQ4IDQ4TDYwIDM2IiBzdHJva2U9IiM5MzkzOTMiIHN0cm9rZS13aWR0aD0iMiIvPjwvc3ZnPg==';">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="mt-3">
                                                    <button type="button" onclick="openImageModal(document.querySelector('img[data-photo-url]')?.getAttribute('data-photo-url'))" class="inline-flex items-center px-3 py-2 bg-gray-900 text-white text-sm rounded hover:bg-black" style="color: white !important;">
                                                        Review Proof
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400">No photos attached.</p>
                                        @endif
                                    </div>

                                    <!-- Task Feedback Button -->
                                    <div class="mt-4 flex flex-wrap justify-center items-center gap-3">
                                        <a href="{{ route('feedback.create', $task) }}" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-lg transition-colors"
                                           style="background-color: #2B9D8D;"
                                           onmouseover="this.style.backgroundColor='#248A7C'"
                                           onmouseout="this.style.backgroundColor='#2B9D8D'">
                                            <i class="fas fa-comment mr-2"></i>
                                            Task Feedback
                                        </a>
                                        <a href="{{ route('feedback.show', $task) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Feedback
                                        </a>
                                    </div>
                                </div>
                            @elseif($userAssignment->status === 'assigned' && $userAssignment->rejection_count > 0)
                                <!-- Rejected Status - Show rejection reason and allow resubmission -->
                                <div class="mb-6">
                                    <div class="border rounded-lg p-6" style="background-color: rgba(43, 157, 141, 0.1); border-color: #2B9D8D;">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-times-circle text-5xl mb-4" style="color: #2B9D8D;"></i>
                                            <h3 class="text-lg font-semibold mb-2" style="color: #2B9D8D;">Submission Rejected</h3>
                                            <p class="text-sm mb-4" style="color: #2B9D8D;">
                                                Your task submission was rejected. Please review the feedback and resubmit.
                                            </p>
                                            
                                            <!-- Rejection Details -->
                                            <div class="w-full max-w-md rounded-lg p-4 mb-4" style="background-color: rgba(43, 157, 141, 0.2);">
                                                <p class="text-sm font-medium mb-2" style="color: #2B9D8D;">Rejection Details:</p>
                                                <p class="text-sm mb-2" style="color: #2B9D8D;">
                                                    <strong>Attempt:</strong> {{ $userAssignment->rejection_count }}/3
                                                </p>
                                                @if($userAssignment->rejection_reason)
                                                    <p class="text-sm" style="color: #2B9D8D;">
                                                        <strong>Reason:</strong> {{ $userAssignment->rejection_reason }}
                                                    </p>
                                                @endif
                                            </div>
                                            
                                            @if($userAssignment->rejection_count < 3)
                                                <p class="text-sm mb-4" style="color: #2B9D8D;">
                                                    You have {{ 3 - $userAssignment->rejection_count }} remaining attempts to resubmit.
                                                </p>
                                            @else
                                                <p class="text-sm mb-4" style="color: #2B9D8D;">
                                                    You have reached the maximum number of attempts (3). Please contact admin for assistance.
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Removed duplicate upload area to consolidate into the form below -->
                            @elseif($userAssignment->status === 'assigned')
                                <!-- Removed duplicate upload area to consolidate into the form below -->
                            @elseif($userAssignment->status === 'completed')
                                <!-- Completed Status -->
                                <div class="mb-6">
                                    <div class="border rounded-lg p-6 text-center" style="background-color: rgba(43, 157, 141, 0.1); border-color: #2B9D8D;">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-check-circle text-5xl mb-4" style="color: #2B9D8D;"></i>
                                            <h3 class="text-lg font-semibold mb-2" style="color: #2B9D8D;">Task Completed</h3>
                                            <p class="text-sm mb-4" style="color: #2B9D8D;">
                                                Congratulations! Your task has been approved and completed.
                                            </p>
                                            @if($userAssignment->completed_at)
                                                <p class="text-xs" style="color: #2B9D8D;">
                                                    Completed on {{ is_string($userAssignment->completed_at) ? \Carbon\Carbon::parse($userAssignment->completed_at)->format('M j, Y \a\t g:i A') : $userAssignment->completed_at->format('M j, Y \a\t g:i A') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="mt-4 flex flex-wrap justify-center items-center gap-3">
                                        <!-- Task Feedback Button -->
                                        <a href="{{ route('feedback.create', $task) }}" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-lg transition-colors"
                                           style="background-color: #2B9D8D;"
                                           onmouseover="this.style.backgroundColor='#248A7C'"
                                           onmouseout="this.style.backgroundColor='#2B9D8D'">
                                            <i class="fas fa-comment mr-2"></i>
                                            Task Feedback
                                        </a>
                                        
                                        <a href="{{ route('feedback.show', $task) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Feedback
                                        </a>
                                        
                                        <!-- Tap & Pass Button - Only for daily tasks completed TODAY -->
                                        @if($task->task_type === 'daily' && $userAssignment->completed_at && \Carbon\Carbon::parse($userAssignment->completed_at)->isToday())
                                            <button onclick="openNominationModal({{ $task->taskId }})" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-lg transition-colors"
                                                    style="background-color: #2B9D8D;"
                                                    onmouseover="this.style.backgroundColor='#248A7C'"
                                                    onmouseout="this.style.backgroundColor='#2B9D8D'">
                                                <i class="fas fa-bolt mr-2"></i>
                                                🎯 Tap & Pass
                                            </button>
                                        @elseif($task->task_type === 'daily' && $userAssignment->completed_at && !\Carbon\Carbon::parse($userAssignment->completed_at)->isToday())
                                            <div class="inline-flex items-center px-4 py-2 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed opacity-60">
                                                <i class="fas fa-bolt mr-2"></i>
                                                🎯 Tap & Pass
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Helper text for Tap & Pass -->
                                    @if($task->task_type === 'daily' && $userAssignment->completed_at)
                                        <p class="text-xs text-gray-500 mt-2 text-center">
                                            @if(\Carbon\Carbon::parse($userAssignment->completed_at)->isToday())
                                                Nominate someone for a daily task (completed today)
                                            @else
                                                Only available for tasks completed today
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            @endif
                        @endif

                        <!-- Complete Task Form (only when progress is 'done') -->
                        @php $assignmentForForm = $task->assignments->where('userId', Auth::id())->first(); @endphp
                        @if($task->isAssignedTo(Auth::id()) 
                            && $assignmentForForm 
                            && $assignmentForForm->status === 'assigned' 
                            && ($assignmentForForm->progress ?? 'accepted') === 'done')
                        <div class="flex justify-center">
                            <form id="task-submit-form" action="{{ route('tasks.submit', $task) }}" method="POST" enctype="multipart/form-data" class="w-full max-w-md">
                                @csrf
                                
                                <!-- Completion Notes (visible only when progress is done) -->
                                <div class="mb-4">
                                    <label for="completion_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Completion Notes (Optional)
                                    </label>
                                    <textarea 
                                        id="completion_notes" 
                                        name="completion_notes" 
                                        rows="3" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Describe what you did to complete this task..."></textarea>
                                    @error('completion_notes')
                                        <p class="mt-2 text-sm dark:text-red-400" style="color: #2B9D8D;">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- File input for photos (max 3) -->
                                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden" aria-describedby="photos-help">
                                
                                <!-- In-form Upload Area (only when progress is done) -->
                                <div id="form-upload-area" class="mt-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #2B9D8D;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <p id="form-upload-text" class="text-sm text-gray-600 dark:text-gray-400 mb-1">Click to select photos or drag and drop (2–3 photos)</p>
                                        <p id="photos-help" class="text-xs text-gray-500 dark:text-gray-400">Max 3 images. Accepted: jpeg, png, jpg, gif.</p>
                                    </div>
                                </div>
                                @error('photos')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                @error('photos.*')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <div id="form-selected-files" class="mt-3 hidden">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Files:</h4>
                                    <div id="form-file-list" class="space-y-2 grid grid-cols-3 gap-2"></div>
                                </div>
                                
                                <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white shadow-md transition-colors brand-primary-btn">
                                    <i class="fas fa-check-double"></i> Complete Task
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <!-- Participants Tab Content -->
                    <div id="participants-content" class="tab-content hidden">
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-users" style="color: #F3A261;"></i>
                                    Participants
                                </h3>
                                <span class="px-3 py-1 text-white text-sm font-bold rounded-full" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                                    @if($task->max_participants !== null)
                                        {{ $task->assignments->count() }} / {{ $task->max_participants }}
                                    @else
                                        {{ $task->assignments->count() }}
                                    @endif
                                </span>
                            </div>
                            @if($task->assignments->count() > 0)
                                @php
                                    $displayLimit = 3;
                                    $displayedParticipants = $task->assignments->take($displayLimit);
                                    $hasMore = $task->assignments->count() > $displayLimit;
                                @endphp
                                <!-- Limited Participants List -->
                                <div class="space-y-3 mb-4">
                                    @foreach($displayedParticipants as $assignment)
                                        @php
                                            $firstName = $assignment->user->firstName ?? '';
                                            $lastName = $assignment->user->lastName ?? '';
                                            $initials = strtoupper(
                                                (!empty($firstName) ? substr($firstName, 0, 1) : '') . 
                                                (!empty($lastName) ? substr($lastName, 0, 1) : '')
                                            );
                                            if (empty($initials)) {
                                                $initials = strtoupper(substr($assignment->user->name ?? 'U', 0, 1));
                                            }
                                        @endphp
                                        <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                                                    <span class="text-sm font-bold text-white">
                                                        {{ $initials }}
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $assignment->user->name ?? 'Unknown User' }}</p>
                                                    <p class="text-xs text-gray-500 truncate">{{ $assignment->user->email ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 flex-shrink-0 ml-3 flex-wrap justify-end gap-2">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full
                                                    @if($assignment->status === 'assigned') bg-orange-100 text-orange-800
                                                    @elseif($assignment->status === 'submitted') bg-yellow-100 text-yellow-800
                                                    @elseif($assignment->status === 'completed') bg-teal-100 text-teal-800
                                                    @endif">
                                                    <i class="fas 
                                                        @if($assignment->status === 'assigned') fa-user-check
                                                        @elseif($assignment->status === 'submitted') fa-paper-plane
                                                        @elseif($assignment->status === 'completed') fa-check-circle
                                                        @endif"></i>
                                                    {{ ucfirst($assignment->status) }}
                                                </span>
                                                @if($assignment->user->userId !== Auth::id())
                                                    <a href="{{ route('incident-reports.create', ['reported_user' => $assignment->user->userId, 'task' => $task->taskId]) }}" 
                                                       class="text-xs font-medium"
                                                       style="color: #2B9D8D;"
                                                       onmouseover="this.style.color='#248A7C';"
                                                       onmouseout="this.style.color='#2B9D8D';"
                                                       title="Report this user">
                                                        <i class="fas fa-exclamation-triangle"></i> Report
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <button type="button" onclick="openParticipantsModal()" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg text-white shadow-md transition-all hover:shadow-lg transform hover:-translate-y-0.5"
                                        style="background-color: #F3A261;"
                                        onmouseover="this.style.backgroundColor='#E8944F'"
                                        onmouseout="this.style.backgroundColor='#F3A261'">
                                    <i class="fas fa-eye"></i>
                                    View All ({{ $task->assignments->count() }})
                                </button>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-users-slash text-gray-300 text-3xl mb-3"></i>
                                    <p class="text-sm text-gray-500 font-medium">No participants yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($showAdminLayout)
    <!-- Participants Modal for Creator Layout -->
    <div id="participants-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true" style="backdrop-filter: blur(4px);">
        <div class="absolute inset-0 bg-black/50" onclick="closeParticipantsModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-orange-300 transform transition-all max-h-[90vh] flex flex-col">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-users text-white text-xl"></i>
                        <h3 class="text-xl font-bold text-white">All Participants</h3>
                        <span class="px-3 py-1 bg-white/20 text-white text-sm font-bold rounded-full">
                            {{ $task->assignments->count() }}
                        </span>
                    </div>
                    <button type="button" class="text-white hover:text-gray-200 transition-colors" onclick="closeParticipantsModal()" aria-label="Close modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Content -->
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    @if($task->assignments->count() > 0)
                        <div class="space-y-3">
                            @foreach($task->assignments as $assignment)
                                @php
                                    $firstName = $assignment->user->firstName ?? '';
                                    $lastName = $assignment->user->lastName ?? '';
                                    $initials = strtoupper(
                                        (!empty($firstName) ? substr($firstName, 0, 1) : '') . 
                                        (!empty($lastName) ? substr($lastName, 0, 1) : '')
                                    );
                                    if (empty($initials)) {
                                        $initials = strtoupper(substr($assignment->user->name ?? 'U', 0, 1));
                                    }
                                @endphp
                                <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        <div class="h-12 w-12 rounded-full flex items-center justify-center flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                                            <span class="text-base font-bold text-white">
                                                {{ $initials }}
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900">{{ $assignment->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $assignment->user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 flex-shrink-0 ml-3 flex-wrap justify-end gap-2">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full
                                            @if($assignment->status === 'assigned') bg-orange-100 text-orange-800
                                            @elseif($assignment->status === 'submitted') bg-yellow-100 text-yellow-800
                                            @elseif($assignment->status === 'completed') bg-teal-100 text-teal-800
                                            @endif">
                                            <i class="fas 
                                                @if($assignment->status === 'assigned') fa-user-check
                                                @elseif($assignment->status === 'submitted') fa-paper-plane
                                                @elseif($assignment->status === 'completed') fa-check-circle
                                                @endif"></i>
                                            {{ ucfirst($assignment->status) }}
                                        </span>
                                        @if(!empty($assignment->progress))
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full
                                            @switch($assignment->progress)
                                                @case('accepted') bg-gray-100 text-gray-800 @break
                                                @case('on_the_way') bg-purple-100 text-purple-800 @break
                                                @case('working') bg-indigo-100 text-indigo-800 @break
                                                @case('done') bg-teal-100 text-teal-800 @break
                                                @case('submitted_proof') bg-orange-100 text-orange-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            <i class="fas 
                                                @if($assignment->progress === 'accepted') fa-handshake
                                                @elseif($assignment->progress === 'on_the_way') fa-route
                                                @elseif($assignment->progress === 'working') fa-tools
                                                @elseif($assignment->progress === 'done') fa-clipboard-check
                                                @elseif($assignment->progress === 'submitted_proof') fa-file-upload
                                                @endif"></i>
                                            {{ ucfirst(str_replace('_',' ', $assignment->progress)) }}
                                        </span>
                                        @endif
                                        @if($assignment->status === 'submitted')
                                            <a href="{{ route('tasks.creator.show', $assignment) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i> Review
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-users-slash text-gray-300 text-4xl mb-4"></i>
                            <p class="text-sm text-gray-500 font-medium">No users have joined this task yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Participants Modal -->
    <script>
        function openParticipantsModal() {
            const modal = document.getElementById('participants-modal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeParticipantsModal() {
            const modal = document.getElementById('participants-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeParticipantsModal();
            }
        });

    </script>
    @endif

    @if(!$showAdminLayout)
    <!-- Progress Confirmation Modal -->
    <div id="progressModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Update Task Progress</h3>
                </div>
                <p class="text-gray-700 mb-6">
                    Are you sure you want to move progress to <span id="modalProgressLabel" class="font-semibold" style="color: #F3A261;"></span>?
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeProgressModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="button" 
                            onclick="confirmProgressUpdate()" 
                            class="px-4 py-2 text-white rounded-lg shadow-md transition-colors font-medium brand-primary-btn">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab JavaScript -->
    <script>
        let pendingProgressForm = null;
        
        function showProgressModal(progressLabel, progressValue) {
            document.getElementById('modalProgressLabel').textContent = progressLabel;
            pendingProgressForm = document.getElementById('progress-form-' + progressValue);
            const modal = document.getElementById('progressModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeProgressModal() {
            const modal = document.getElementById('progressModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            pendingProgressForm = null;
        }
        
        function confirmProgressUpdate() {
            if (pendingProgressForm) {
                pendingProgressForm.submit();
            }
        }
        
        // Close modal on backdrop click
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('progressModal');
            if (e.target === modal) {
                closeProgressModal();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('progressModal');
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeProgressModal();
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailsTab = document.getElementById('details-tab');
            const participantsTab = document.getElementById('participants-tab');
            const detailsContent = document.getElementById('details-content');
            const participantsContent = document.getElementById('participants-content');

            // Initialize with Details tab active
            function setActiveTab(tab) {
                if (tab === 'details') {
                    detailsTab.style.borderColor = '#F3A261';
                    detailsTab.classList.add('text-gray-900', 'font-bold');
                    detailsTab.classList.remove('text-gray-500', 'font-semibold');
                    
                    participantsTab.style.borderColor = 'transparent';
                    participantsTab.classList.remove('text-gray-900', 'font-bold');
                    participantsTab.classList.add('text-gray-500', 'font-semibold');
                    
                    detailsContent.classList.remove('hidden');
                    participantsContent.classList.add('hidden');
                } else {
                    participantsTab.style.borderColor = '#F3A261';
                    participantsTab.classList.add('text-gray-900', 'font-bold');
                    participantsTab.classList.remove('text-gray-500', 'font-semibold');
                    
                    detailsTab.style.borderColor = 'transparent';
                    detailsTab.classList.remove('text-gray-900', 'font-bold');
                    detailsTab.classList.add('text-gray-500', 'font-semibold');
                    
                    participantsContent.classList.remove('hidden');
                    detailsContent.classList.add('hidden');
                }
            }

            detailsTab.addEventListener('click', function() {
                setActiveTab('details');
            });

            participantsTab.addEventListener('click', function() {
                setActiveTab('participants');
            });

            // Check URL parameter for tab preference, default to details
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            const defaultTab = tabParam === 'participants' ? 'participants' : 'details';
            setActiveTab(defaultTab);

            // Photo upload functionality (limit 3 files, require min 2 client-side)
            // Support multiple upload areas elsewhere, but prioritize in-form scoped elements to prevent conflicts
            const photosInput = document.getElementById('photos');

            // Scoped elements for the submission form
            const formUploadArea = document.getElementById('form-upload-area');
            const formUploadText = document.getElementById('form-upload-text');
            const formSelectedFiles = document.getElementById('form-selected-files');
            const formFileList = document.getElementById('form-file-list');
            const MAX_FILES = 3;
            const MIN_FILES = 2;
            let selectedFilesState = [];

            function wireUploadArea(areaEl, textEl, listContainerEl, listEl) {
                if (!areaEl || !photosInput) return;

                areaEl.addEventListener('click', function() {
                    photosInput.click();
                });

                // Drag and drop functionality
                areaEl.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    areaEl.style.borderColor = '#2B9D8D';
                    areaEl.style.backgroundColor = 'rgba(43, 157, 141, 0.1)';
                });

                areaEl.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    areaEl.style.borderColor = '';
                    areaEl.style.backgroundColor = '';
                });

                areaEl.addEventListener('drop', function(e) {
                    e.preventDefault();
                    areaEl.style.borderColor = '';
                    areaEl.style.backgroundColor = '';
                    
                    let newFiles = e.dataTransfer.files;
                    if (newFiles.length > 0) {
                        selectedFilesState = limitFiles(mergeFiles(selectedFilesState, newFiles));
                        setInputFiles(photosInput, selectedFilesState);
                        handleFileSelection(selectedFilesState, textEl, listContainerEl, listEl);
                    }
                });

                photosInput.addEventListener('change', function(e) {
                    let newFiles = e.target.files;
                    if (newFiles.length > 0) {
                        selectedFilesState = limitFiles(mergeFiles(selectedFilesState, newFiles));
                        setInputFiles(photosInput, selectedFilesState);
                        handleFileSelection(selectedFilesState, textEl, listContainerEl, listEl);
                    }
                });

                function handleFileSelection(files, textTarget, containerTarget, listTarget) {
                    // Always render from selectedFilesState to keep indices stable
                    renderSelectedFiles(textTarget, containerTarget, listTarget);
                }

                function renderSelectedFiles(textTarget, containerTarget, listTarget) {
                    const count = selectedFilesState.length;
                    let message = `${count} photo(s) selected`;
                    if (count > MAX_FILES) {
                        message = `You can upload up to ${MAX_FILES} photos.`;
                    } else if (count > 0 && count < MIN_FILES) {
                        message = `Please select at least ${MIN_FILES} photos.`;
                    }
                    if (textTarget) {
                        textTarget.textContent = message;
                        textTarget.classList.add('text-green-600', 'dark:text-green-400');
                    }

                    if (containerTarget) containerTarget.classList.toggle('hidden', count === 0);
                    if (listTarget) listTarget.innerHTML = '';

                    selectedFilesState.slice(0, MAX_FILES).forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'relative group overflow-hidden rounded border border-gray-200 dark:border-gray-700';
                        const objectUrl = URL.createObjectURL(file);
                        fileItem.innerHTML = `
                            <img src="${objectUrl}" alt="preview" class="w-full h-24 object-cover" />
                            <button type="button" aria-label="Remove image" data-index="${index}" class="absolute top-1 right-1 bg-black/60 hover:bg-black/80 text-white text-xs leading-none rounded px-2 py-1">×</button>
                            <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[10px] px-1 truncate">${file.name}</div>
                        `;
                        if (listTarget) listTarget.appendChild(fileItem);
                    });

                    // Wire remove buttons
                    if (listTarget) {
                        listTarget.querySelectorAll('button[data-index]').forEach(btn => {
                            btn.addEventListener('click', () => {
                                const idx = parseInt(btn.getAttribute('data-index'));
                                if (!isNaN(idx)) {
                                    selectedFilesState.splice(idx, 1);
                                    setInputFiles(photosInput, selectedFilesState);
                                    renderSelectedFiles(textTarget, containerTarget, listTarget);
                                }
                            });
                        });
                    }
                }

                function limitFiles(fileList) {
                    // Accept Array<File> or FileList; return Array<File> limited to MAX_FILES
                    const files = Array.isArray(fileList) ? fileList : Array.from(fileList);
                    if (files.length > MAX_FILES) {
                        showAlertModal(`You can upload up to ${MAX_FILES} photos.`, 'Upload Limit', 'warning');
                    }
                    return files.slice(0, MAX_FILES);
                }

                function setInputFiles(input, filesArray) {
                    // Create a new DataTransfer to set limited files on input
                    const dataTransfer = new DataTransfer();
                    filesArray.forEach(file => dataTransfer.items.add(file));
                    input.files = dataTransfer.files;
                }

                function mergeFiles(existingList, newList) {
                    const existing = Array.isArray(existingList) ? existingList : Array.from(existingList || []);
                    const incoming = Array.from(newList || []);
                    // Merge while avoiding exact duplicates (name+size)
                    const signature = (f) => `${f.name}|${f.size}`;
                    const seen = new Set(existing.map(signature));
                    const merged = [...existing];
                    for (const f of incoming) {
                        if (seen.has(signature(f))) continue;
                        merged.push(f);
                        seen.add(signature(f));
                        if (merged.length >= MAX_FILES) break;
                    }
                    return merged;
                }
            }

            // Only wire the scoped form area to keep a single uploader experience
            wireUploadArea(formUploadArea, formUploadText, formSelectedFiles, formFileList);

            // Prevent submit if files are not between 2 and 3
            const submitForm = document.getElementById('task-submit-form');
            if (submitForm && photosInput) {
                submitForm.addEventListener('submit', function(e) {
                    // Ensure the input reflects our state before submit
                    if (selectedFilesState.length > 0) {
                        setInputFiles(photosInput, selectedFilesState);
                    }
                    const count = selectedFilesState.length || (photosInput.files ? photosInput.files.length : 0);
                    if (count < MIN_FILES || count > MAX_FILES) {
                        e.preventDefault();
                        showAlertModal(`Please upload between ${MIN_FILES} and ${MAX_FILES} photos.`, 'Upload Required', 'warning');
                    }
                });
            }
        });
    </script>
    
    <!-- Image Review Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4" style="display: none;">
        <div class="relative max-w-7xl max-h-full w-full h-full flex items-center justify-center">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <button id="prevButton" onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-3 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button id="nextButton" onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-3 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
            <div class="flex items-center justify-center w-full h-full">
                <img id="modalImage" src="" alt="Task completion proof" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>
            <div id="imageCounter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm">
                <span id="currentImageIndex">1</span> / <span id="totalImages">1</span>
            </div>
            <button id="downloadButton" onclick="downloadImage()" class="absolute bottom-4 right-4 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </button>
        </div>
    </div>
    <script>
        let currentImageIndex = 0;
        let imageSources = [];
        document.addEventListener('DOMContentLoaded', function() {
            const imageElements = document.querySelectorAll('img[data-photo-url]');
            imageSources = Array.from(imageElements).map(img => img.getAttribute('data-photo-url'));
        });
        function openImageModal(imageSrc) {
            if (imageSources.length === 0) return;
            currentImageIndex = imageSources.indexOf(imageSrc);
            if (currentImageIndex === -1) currentImageIndex = 0;
            updateModalImage();
            const modal = document.getElementById('imageModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        function nextImage() { if (imageSources.length > 1) { currentImageIndex = (currentImageIndex + 1) % imageSources.length; updateModalImage(); } }
        function previousImage() { if (imageSources.length > 1) { currentImageIndex = (currentImageIndex - 1 + imageSources.length) % imageSources.length; updateModalImage(); } }
        function updateModalImage() {
            const modalImage = document.getElementById('modalImage');
            const currentIndexSpan = document.getElementById('currentImageIndex');
            const totalImagesSpan = document.getElementById('totalImages');
            const prevButton = document.getElementById('prevButton');
            const nextButton = document.getElementById('nextButton');
            if (imageSources.length > 0) {
                modalImage.src = imageSources[currentImageIndex];
                currentIndexSpan.textContent = currentImageIndex + 1;
                totalImagesSpan.textContent = imageSources.length;
                if (imageSources.length === 1) { prevButton.style.display = 'none'; nextButton.style.display = 'none'; }
                else { prevButton.style.display = 'block'; nextButton.style.display = 'block'; }
            }
        }
        function downloadImage() { 
            if (imageSources.length > 0) { 
                const link = document.createElement('a'); 
                link.href = imageSources[currentImageIndex]; 
                link.download = `task-proof-${currentImageIndex + 1}.jpg`; 
                document.body.appendChild(link); 
                link.click(); 
                document.body.removeChild(link); 
            } 
        }
        document.addEventListener('click', function(e) { const modal = document.getElementById('imageModal'); if (e.target === modal) { closeImageModal(); } });
        document.addEventListener('keydown', function(e) { const modal = document.getElementById('imageModal'); if (modal && !modal.classList.contains('hidden')) { if (e.key === 'Escape') closeImageModal(); if (e.key === 'ArrowLeft') previousImage(); if (e.key === 'ArrowRight') nextImage(); } });
    </script>
    
    <!-- Participants Modal for Regular User Layout -->
    <div id="participants-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true" style="backdrop-filter: blur(4px);">
        <div class="absolute inset-0 bg-black/50" onclick="closeParticipantsModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-orange-300 transform transition-all max-h-[90vh] flex flex-col">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-users text-white text-xl"></i>
                        <h3 class="text-xl font-bold text-white">All Participants</h3>
                        <span class="px-3 py-1 bg-white/20 text-white text-sm font-bold rounded-full">
                            @if($task->max_participants !== null)
                                {{ $task->assignments->count() }} / {{ $task->max_participants }}
                            @else
                                {{ $task->assignments->count() }}
                            @endif
                        </span>
                    </div>
                    <button type="button" class="text-white hover:text-gray-200 transition-colors" onclick="closeParticipantsModal()" aria-label="Close modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Content -->
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    @if($task->assignments->count() > 0)
                        <div class="space-y-3">
                            @foreach($task->assignments as $assignment)
                                @php
                                    $firstName = $assignment->user->firstName ?? '';
                                    $lastName = $assignment->user->lastName ?? '';
                                    $initials = strtoupper(
                                        (!empty($firstName) ? substr($firstName, 0, 1) : '') . 
                                        (!empty($lastName) ? substr($lastName, 0, 1) : '')
                                    );
                                    if (empty($initials)) {
                                        $initials = strtoupper(substr($assignment->user->name ?? 'U', 0, 1));
                                    }
                                @endphp
                                <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        <div class="h-12 w-12 rounded-full flex items-center justify-center flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                                            <span class="text-base font-bold text-white">
                                                {{ $initials }}
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $assignment->user->name ?? 'Unknown User' }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $assignment->user->email ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 flex-shrink-0 ml-3 flex-wrap justify-end gap-2">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full
                                            @if($assignment->status === 'assigned') bg-orange-100 text-orange-800
                                            @elseif($assignment->status === 'submitted') bg-yellow-100 text-yellow-800
                                            @elseif($assignment->status === 'completed') bg-teal-100 text-teal-800
                                            @endif">
                                            <i class="fas 
                                                @if($assignment->status === 'assigned') fa-user-check
                                                @elseif($assignment->status === 'submitted') fa-paper-plane
                                                @elseif($assignment->status === 'completed') fa-check-circle
                                                @endif"></i>
                                            {{ ucfirst($assignment->status) }}
                                        </span>
                                        @if($assignment->user->userId !== Auth::id())
                                            <a href="{{ route('incident-reports.create', ['reported_user' => $assignment->user->userId, 'task' => $task->taskId]) }}" 
                                               class="text-red-600 hover:text-red-800 text-xs font-medium"
                                               title="Report this user">
                                                <i class="fas fa-exclamation-triangle"></i> Report
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-users-slash text-gray-300 text-4xl mb-4"></i>
                            <p class="text-sm text-gray-500 font-medium">No users have joined this task yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Participants Modal -->
    <script>
        function openParticipantsModal() {
            const modal = document.getElementById('participants-modal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeParticipantsModal() {
            const modal = document.getElementById('participants-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const participantsModal = document.getElementById('participants-modal');
                if (participantsModal && !participantsModal.classList.contains('hidden')) {
                    closeParticipantsModal();
                }
            }
        });
    </script>
    @endif

    <!-- Tap & Pass Nomination Modal -->
    <div id="nominationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col transform transition-all overflow-hidden">
            <div id="nominationModalContent" class="flex flex-col">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>

    <!-- Nomination Confirmation Modal -->
    <div id="nominationConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-[60] hidden flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                        <i class="fas fa-question-circle text-white text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-xl font-bold text-gray-900">Confirm Nomination</h3>
                </div>
                <p class="text-gray-700 mb-6">
                    Send this nomination? You will earn <span class="font-bold" style="color: #F3A261;">1 point</span> for using Tap & Pass.
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeNominationConfirmModal()" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold rounded-xl border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmNominationSubmit()" class="inline-flex items-center justify-center gap-2 px-8 py-2.5 text-sm font-bold rounded-xl text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 brand-primary-btn">
                        <i class="fas fa-paper-plane"></i>
                        Confirm & Send
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openNominationModal(taskId) {
            const modal = document.getElementById('nominationModal');
            const modalContent = document.getElementById('nominationModalContent');
            
            // Show loading state
            modalContent.innerHTML = `
                <div class="px-6 py-12 text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-orange-600"></div>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">Loading...</p>
                </div>
            `;
            
            // Show modal
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Load modal content via AJAX
            fetch(`/tap-nominations/create/${taskId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                modalContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading nomination form:', error);
                modalContent.innerHTML = `
                    <div class="px-6 py-12 text-center">
                        <div class="dark:text-red-400 mb-4" style="color: #2B9D8D;">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-gray-900 dark:text-white font-semibold mb-2">Error loading form</p>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Unable to load the nomination form. Please try again.</p>
                        <button onclick="closeNominationModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors" style="color: white !important;">
                            Close
                        </button>
                    </div>
                `;
            });
        }
        
        function closeNominationModal() {
            const modal = document.getElementById('nominationModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Close modal on backdrop click
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('nominationModal');
            if (e.target === modal) {
                closeNominationModal();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('nominationModal');
            const confirmModal = document.getElementById('nominationConfirmModal');
            if (e.key === 'Escape') {
                if (confirmModal && !confirmModal.classList.contains('hidden')) {
                    closeNominationConfirmModal();
                } else if (modal && !modal.classList.contains('hidden')) {
                    closeNominationModal();
                }
            }
        });

        // Confirmation modal functions
        function showNominationConfirmModal() {
            const confirmModal = document.getElementById('nominationConfirmModal');
            confirmModal.classList.remove('hidden');
            confirmModal.style.display = 'flex';
        }

        function closeNominationConfirmModal() {
            const confirmModal = document.getElementById('nominationConfirmModal');
            confirmModal.classList.add('hidden');
            confirmModal.style.display = 'none';
        }

        function confirmNominationSubmit() {
            const form = document.getElementById('nomination-form');
            if (form) {
                closeNominationConfirmModal();
                // Submit the form
                form.submit();
            }
        }

        // Close confirmation modal on backdrop click
        document.addEventListener('click', function(e) {
            const confirmModal = document.getElementById('nominationConfirmModal');
            if (e.target === confirmModal) {
                closeNominationConfirmModal();
            }
        });

    </script>

    <!-- Custom Scrollbar Styling -->
    <style>
        /* Brand Primary Button */
        .brand-primary-btn {
            background-color: #F3A261;
            box-shadow: 0 4px 15px rgba(243, 162, 97, 0.3);
        }
        .brand-primary-btn:hover {
            background-color: #E8944F;
            box-shadow: 0 6px 20px rgba(243, 162, 97, 0.4);
            transform: translateY(-1px);
        }
        
        /* Custom Scrollbar for Participants Section */
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background-color: #F3A261;
            border-radius: 10px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background-color: #E8944F;
        }
        
        /* Firefox Scrollbar */
        .overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: #F3A261 #f1f1f1;
        }
        
        /* Smooth transitions */
        .brand-primary-btn {
            transition: all 0.3s ease;
        }
    </style>
</x-app-layout>
