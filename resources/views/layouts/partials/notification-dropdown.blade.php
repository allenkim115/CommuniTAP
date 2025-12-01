@php
    $unreadCount = $unreadCount ?? 0;
    $notifications = $notifications ?? collect();
    $accentColor = $accentColor ?? '#2B9D8D';
    $accentHoverColor = $accentHoverColor ?? '#237C71';
    $highlightBackground = $highlightBackground ?? 'rgba(43, 157, 141, 0.12)';
@endphp

<div class="px-4 py-3 flex items-center justify-between border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <span class="text-sm font-bold text-gray-900 dark:text-white">Notifications</span>
        @if($unreadCount > 0)
            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white rounded-full" style="background-color: {{ $accentColor }};">
                {{ $unreadCount }}
            </span>
        @endif
    </div>
    @if($unreadCount > 0)
        <form method="POST" action="{{ route('notifications.mark-all-read') }}" novalidate>
            @csrf
            <button type="submit" class="text-xs font-semibold transition-colors"
                    style="color: {{ $accentColor }};"
                    onmouseover="this.style.color='{{ $accentHoverColor }}';"
                    onmouseout="this.style.color='{{ $accentColor }}';">
                Mark all read
            </button>
        </form>
    @endif
</div>
<div class="max-h-96 overflow-y-auto">
    @forelse($notifications as $notification)
        @php
            $type = $notification->type ?? 'default';

            // Map notification types to Font Awesome icon classes
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
        @endphp
        @php
            // Determine target URL with sensible fallbacks
            $targetUrl = $notification->data['url'] ?? null;

            // Special handling for known notification types
            if ($notification->type === 'reward_claim_submitted') {
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
        <a href="{{ $targetUrl }}" 
           class="notification-link block px-4 py-3 border-t border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $notification->status === 'unread' ? 'border-l-4' : '' }}"
           data-notification-status="{{ $notification->status }}"
           data-mark-read-url="{{ route('notifications.mark-read', $notification) }}"
           data-target-url="{{ $targetUrl }}"
           @if($notification->status === 'unread') style="background-color: {{ $highlightBackground }}; border-color: {{ $accentColor }};" @endif>
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    <div class="p-1.5 rounded-lg bg-gray-100 dark:bg-gray-700"
                         @if($notification->status === 'unread') style="background-color: {{ $highlightBackground }};" @endif>
                        <i class="{{ $iconClass }} text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 leading-snug line-clamp-2">
                            {{ $notification->message }}
                        </p>
                        @if($notification->status === 'unread')
                            <span class="flex-shrink-0 w-2 h-2 rounded-full mt-1" style="background-color: {{ $accentColor }};"></span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ optional($notification->created_at)->diffForHumans() ?? optional($notification->created_date)->diffForHumans() }}
                    </p>
                </div>
            </div>
        </a>
    @empty
        <div class="px-4 py-8 text-center">
            <svg class="mx-auto w-12 h-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <p class="text-sm text-gray-500 dark:text-gray-400">No notifications yet.</p>
        </div>
    @endforelse
</div>
<div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
    <a href="{{ route('notifications.index') }}" 
       class="inline-flex items-center gap-2 text-sm font-semibold transition-colors"
       style="color: {{ $accentColor }};"
       onmouseover="this.style.color='{{ $accentHoverColor }}';"
       onmouseout="this.style.color='{{ $accentColor }}';">
        <span>View all notifications</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>

<script>
    (function () {
        if (window.__notificationAutoReadInitialized) {
            return;
        }
        window.__notificationAutoReadInitialized = true;

        function attachHandlers() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            document.querySelectorAll('.notification-link').forEach(link => {
                if (link.dataset.autoReadBound === 'true') {
                    return;
                }

                link.dataset.autoReadBound = 'true';

                link.addEventListener('click', event => {
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
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', attachHandlers);
        } else {
            attachHandlers();
        }
    })();
</script>

