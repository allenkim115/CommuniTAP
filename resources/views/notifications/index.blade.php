@php
    $user = Auth::user();
    $isAdmin = $user && method_exists($user, 'isAdmin') ? $user->isAdmin() : false;
    $layoutComponent = $isAdmin ? 'admin-layout' : 'app-layout';

    // Brand/system colors (aligned with notification dropdown)
    $accentColor = '#2B9D8D';
    $accentHoverColor = '#237C71';
    $highlightBackground = 'rgba(43, 157, 141, 0.06)';

    // Simple local filters (no backend changes) – used only for UI state
    $activeFilter = request()->query('filter', 'all');
@endphp

<x-dynamic-component :component="$layoutComponent">
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-2xl bg-white shadow-sm border border-gray-200">
                    <i class="fa-solid fa-bell text-sm" style="color: {{ $accentColor }};"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                        {{ __('Notifications') }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Stay up to date with your tasks, rewards, TAP nominations and more.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-3 sm:mt-0">
                @if($unreadCount > 0)
                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-xs font-medium text-gray-700 border border-gray-200">
                        <span class="inline-flex h-2 w-2 rounded-full" style="background-color: {{ $accentColor }};"></span>
                        {{ $unreadCount }} unread
                    </span>
                @endif

                <form method="POST" action="{{ route('notifications.mark-all-read') }}" novalidate>
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-semibold transition-colors bg-white disabled:opacity-40 disabled:cursor-not-allowed"
                            style="border-color: {{ $accentColor }}; color: {{ $accentColor }};"
                            @if($unreadCount === 0) disabled @endif>
                        <i class="fa-solid fa-check-double text-[0.7rem]"></i>
                        <span>Mark all as read</span>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                <div class="border-b border-gray-100 px-4 sm:px-6 py-3.5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="inline-flex h-2 w-2 rounded-full" style="background-color: {{ $accentColor }};"></span>
                        <span>
                            {{ $notifications->total() ?? $notifications->count() }} total
                            @if($unreadCount > 0)
                                · <span class="font-medium">{{ $unreadCount }} unread</span>
                            @else
                                · <span class="font-medium text-emerald-600">Inbox zero</span>
                            @endif
                        </span>
                    </div>

                    {{-- Simple client-side filter pills (no query changes needed) --}}
                    <div class="inline-flex items-center gap-1 rounded-full bg-gray-50 px-1.5 py-1 text-xs text-gray-600">
                        <span class="hidden sm:inline-block mr-1 text-[0.7rem] uppercase tracking-wide text-gray-400">Filter</span>
                        <button type="button"
                                class="notification-filter-pill px-2.5 py-1 rounded-full font-medium transition-colors"
                                data-filter="all"
                                @if($activeFilter === 'all') data-active="true" @endif>
                            All
                        </button>
                        <button type="button"
                                class="notification-filter-pill px-2.5 py-1 rounded-full font-medium transition-colors"
                                data-filter="unread"
                                @if($activeFilter === 'unread') data-active="true" @endif>
                            Unread
                        </button>
                        <button type="button"
                                class="notification-filter-pill px-2.5 py-1 rounded-full font-medium transition-colors"
                                data-filter="read"
                                @if($activeFilter === 'read') data-active="true" @endif>
                            Read
                        </button>
                    </div>
                </div>

                <div class="p-4 sm:p-6 space-y-4">
                    @if($notifications->count() === 0)
                        <div class="text-center py-12">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-dashed border-gray-300 bg-gray-50">
                                <i class="fa-regular fa-bell text-xl text-gray-400"></i>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900">
                                You're all caught up
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                You don’t have any notifications yet. New updates will appear here.
                            </p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @php
                                // Pre-calc group headings (Today, Yesterday, Earlier this week, Older)
                                $today = now()->startOfDay();
                                $yesterday = now()->subDay()->startOfDay();
                            @endphp

                            @foreach($notifications as $notification)
                                @php
                                    $type = $notification->type ?? 'default';

                                    // Map notification types to Font Awesome icon classes and colors
                                    $iconClass = match (true) {
                                        // Task-related notifications (user + admin)
                                        str_starts_with($type, 'task_'),
                                        $type === 'new_task_available' => 'fa-solid fa-list-check text-blue-500',

                                        // Tap nomination notifications
                                        str_starts_with($type, 'tap_nomination_') => 'fa-solid fa-hand-holding-heart text-emerald-500',

                                        // Reward notifications
                                        str_starts_with($type, 'reward_') => 'fa-solid fa-gift text-amber-500',

                                        // Incident report notifications
                                        str_starts_with($type, 'incident_') => 'fa-solid fa-triangle-exclamation text-red-500',

                                        // Fallback
                                        default => 'fa-solid fa-bell text-slate-500',
                                    };

                                    // Determine target URL with sensible fallbacks
                                    $targetUrl = $notification->data['url'] ?? null;

                                    // Special handling for known notification types
                                    if (($notification->type ?? null) === 'reward_claim_submitted') {
                                        $targetUrl = route('admin.redemptions.index', ['status' => 'pending']);
                                    } elseif (in_array($notification->type, ['task_deadline_reminder', 'task_marked_uncompleted'], true)) {
                                        // For task-related reminders/uncompleted, fall back to the specific task page when possible
                                        $taskId = $notification->data['taskId'] ?? null;
                                        if ($taskId) {
                                            $targetUrl = route('tasks.show', ['task' => $taskId]);
                                        } elseif (!$targetUrl) {
                                            // Final fallback to tasks list for safety
                                            $targetUrl = route('tasks.index');
                                        }
                                    }

                                    // Global fallback if still missing
                                    if (!$targetUrl) {
                                        $targetUrl = route('notifications.index');
                                    }
                                @endphp

                                @php
                                    $createdAt = $notification->created_at ?? $notification->created_date ?? null;
                                    $groupLabel = null;
                                    if ($createdAt) {
                                        $date = $createdAt->copy()->startOfDay();
                                        $groupLabel = match (true) {
                                            $date->equalTo($today) => 'Today',
                                            $date->equalTo($yesterday) => 'Yesterday',
                                            $date->greaterThan($today->copy()->subDays(6)) => 'Earlier this week',
                                            default => $createdAt->format('F Y'),
                                        };
                                    }
                                @endphp

                                @if(!empty($groupLabel))
                                    @php
                                        // Emit a heading the first time we encounter this label
                                        static $seenGroups = [];
                                    @endphp
                                    @if(!in_array($groupLabel, $seenGroups, true))
                                        @php $seenGroups[] = $groupLabel; @endphp
                                        <div class="mt-2 text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                            {{ $groupLabel }}
                                        </div>
                                    @endif
                                @endif

                                <a href="{{ $targetUrl }}" 
                                   class="group relative block rounded-xl border border-gray-200 px-4 py-3.5 transition-all duration-150 notification-row notification-link
                                    {{ $notification->status === 'unread'
                                        ? 'bg-gray-50 border-l-4 hover:shadow-md'
                                        : 'bg-white hover:bg-gray-50 hover:shadow-sm' }}"
                                     data-status="{{ $notification->status }}"
                                     data-notification-status="{{ $notification->status }}"
                                     data-mark-read-url="{{ route('notifications.mark-read', $notification) }}"
                                     data-target-url="{{ $targetUrl }}"
                                     @if($notification->status === 'unread')
                                         style="border-left-color: {{ $accentColor }}; background-color: {{ $highlightBackground }};"
                                     @endif>
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100">
                                                <i class="{{ $iconClass }} text-sm" aria-hidden="true"></i>
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-wrap items-start gap-2 justify-between">
                                                <p class="text-sm font-medium text-gray-900 leading-snug">
                                                    {{ $notification->message }}
                                                </p>
                                                <div class="flex items-center gap-2">
                                                    <p class="flex items-center gap-1 text-xs text-gray-500">
                                                        <i class="fa-regular fa-clock text-[0.7rem]"></i>
                                                        <span>
                                                            {{ optional($notification->created_at)->diffForHumans() ?? optional($notification->created_date)->diffForHumans() }}
                                                        </span>
                                                    </p>
                                                    @if($notification->status === 'unread')
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-white px-2 py-0.5 text-[0.65rem] font-semibold text-gray-700 border border-gray-200">
                                                            <span class="inline-flex h-1.5 w-1.5 rounded-full" style="background-color: {{ $accentColor }};"></span>
                                                            New
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if(!empty($notification->data['description']))
                                                <p class="mt-1.5 text-sm text-gray-600">
                                                    {{ $notification->data['description'] }}
                                                </p>
                                            @endif

                                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold transition-colors opacity-0 group-hover:opacity-100"
                                                      style="color: {{ $accentColor }};">
                                                    <span>View details</span>
                                                    <i class="fa-solid fa-arrow-right-long text-[0.7rem]"></i>
                                                </span>

                                                @if($notification->status === 'unread')
                                                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" 
                                                          onclick="event.stopPropagation();" 
                                                          novalidate>
                                                        @csrf
                                                        <button type="submit"
                                                                class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 hover:text-gray-700">
                                                            <i class="fa-regular fa-circle-check text-[0.7rem]"></i>
                                                            <span>Mark as read</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if($notifications->hasPages())
                    <div class="border-t border-gray-100 bg-gray-50 px-4 py-3 sm:px-6 flex items-center justify-between gap-4">
                        <p class="text-xs text-gray-500">
                            Showing
                            <span class="font-semibold text-gray-700">{{ $notifications->firstItem() }}</span>
                            to
                            <span class="font-semibold text-gray-700">{{ $notifications->lastItem() }}</span>
                            of
                            <span class="font-semibold text-gray-700">{{ $notifications->total() }}</span>
                            notifications
                        </p>
                        <div class="text-sm">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dynamic-component>

@push('scripts')
    <script>
        (function () {
            const ACCENT = @json($accentColor);
            const ACCENT_HOVER = @json($accentHoverColor);

            function applyPillStyles(pill, active) {
                if (!pill) return;
                pill.style.transition = 'background-color 150ms, color 150ms';
                if (active) {
                    pill.style.backgroundColor = ACCENT;
                    pill.style.color = '#ffffff';
                } else {
                    pill.style.backgroundColor = 'transparent';
                    pill.style.color = '#4b5563';
                }
            }

            function updateFilter(filter) {
                const rows = document.querySelectorAll('.notification-row');
                rows.forEach(row => {
                    const status = row.dataset.status || 'unread';
                    if (filter === 'all') {
                        row.classList.remove('hidden');
                    } else {
                        const shouldShow = filter === status;
                        row.classList.toggle('hidden', !shouldShow);
                    }
                });

                const pills = document.querySelectorAll('.notification-filter-pill');
                pills.forEach(pill => {
                    const pillFilter = pill.dataset.filter;
                    const isActive = pillFilter === filter;
                    applyPillStyles(pill, isActive);
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                const pills = document.querySelectorAll('.notification-filter-pill');
                const initialPill = document.querySelector('.notification-filter-pill[data-active="true"]');
                const initialFilter = (initialPill && initialPill.dataset.filter) || 'all';

                pills.forEach(pill => {
                    pill.addEventListener('click', () => {
                        const filter = pill.dataset.filter || 'all';
                        updateFilter(filter);
                    });
                });

                // Auto-mark notifications as read when clicked
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                document.querySelectorAll('.notification-link').forEach(link => {
                    if (link.dataset.autoReadBound === 'true') {
                        return;
                    }

                    link.dataset.autoReadBound = 'true';

                    link.addEventListener('click', event => {
                        // Don't interfere with the "Mark as read" button
                        if (event.target.closest('form, button')) {
                            return;
                        }

                        const markUrl = link.dataset.markReadUrl;
                        const isUnread = link.dataset.notificationStatus === 'unread';
                        const targetUrl = link.dataset.targetUrl || link.href;

                        if (!csrfToken || !markUrl || !isUnread) {
                            return;
                        }

                        event.preventDefault();

                        const formData = new URLSearchParams();
                        formData.append('_token', csrfToken);

                        fetch(markUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: formData,
                            credentials: 'same-origin',
                        }).catch(() => {
                            // Swallow errors; navigation continues regardless.
                        }).finally(() => {
                            window.location.href = targetUrl;
                        });
                    });
                });

                // Initial state
                updateFilter(initialFilter);
            });
        })();
    </script>
@endpush
