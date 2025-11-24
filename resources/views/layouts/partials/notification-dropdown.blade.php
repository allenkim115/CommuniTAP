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
        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
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
            $message = strtolower($notification->message ?? '');
            $iconColor = $accentColor;
            $iconType = 'default';

            if (str_contains($message, 'reward') || str_contains($message, 'redeem')) {
                $iconType = 'reward';
            } elseif (str_contains($message, 'task') || str_contains($message, 'assign')) {
                $iconType = 'task';
            } elseif (str_contains($message, 'point') || str_contains($message, 'earn')) {
                $iconType = 'points';
            } elseif (str_contains($message, 'feedback')) {
                $iconType = 'feedback';
            }

            $iconPaths = match ($iconType) {
                'reward' => [
                    'M20 12v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8',
                    'M4 12h16',
                    'M12 12v10',
                    'M12 12V6a2 2 0 00-2-2H7a2 2 0 00-2 2c0 1.657 1.343 3 3 3h3',
                    'M12 6a2 2 0 012-2h3a2 2 0 012 2c0 1.657-1.343 3-3 3h-3',
                ],
                'task' => [
                    'M9 3h6a2 2 0 012 2v12a2 2 0 01-2 2H9a2 2 0 01-2-2V5a2 2 0 012-2z',
                    'M9 5h6',
                    'M9 12l2 2 4-4',
                ],
                'points' => [
                    'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2',
                    'M12 4v4',
                    'M12 14v4',
                ],
                'feedback' => [
                    'M21 11.5c0 4.142-3.806 7.5-8.5 7.5-.964 0-1.897-.128-2.769-.362L6 21v-3.612C4.182 15.938 3 13.861 3 11.5 3 7.358 6.806 4 11.5 4S21 7.358 21 11.5z',
                    'M8.5 11.5h.01',
                    'M11.5 11.5h.01',
                    'M14.5 11.5h.01',
                ],
                default => [
                    'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                ],
            };
        @endphp
        @php
            $targetUrl = $notification->data['url'] ?? route('notifications.index');
            if ($notification->type === 'reward_claim_submitted') {
                $targetUrl = route('admin.redemptions.index', ['status' => 'pending']);
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
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ is_string($iconColor) ? $iconColor : '#2B9D8D' }};">
                            @foreach($iconPaths as $path)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"></path>
                            @endforeach
                        </svg>
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

