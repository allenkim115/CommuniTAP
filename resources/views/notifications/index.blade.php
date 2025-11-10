@php
    $user = Auth::user();
    $isAdmin = $user && method_exists($user, 'isAdmin') ? $user->isAdmin() : false;
    $layoutComponent = $isAdmin ? 'admin-layout' : 'app-layout';
@endphp

<x-dynamic-component :component="$layoutComponent">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-orange-600 dark:text-orange-400 hover:underline">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    @if($notifications->count() === 0)
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            You have no notifications yet.
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($notifications as $notification)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $notification->status === 'unread' ? 'bg-orange-50 dark:bg-gray-700/60' : 'bg-white dark:bg-gray-800' }}">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $notification->message }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                {{ optional($notification->created_at)->diffForHumans() ?? optional($notification->created_date)->diffForHumans() }}
                                            </p>
                                            @if(!empty($notification->data['description']))
                                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                    {{ $notification->data['description'] }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($notification->status === 'unread')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold text-white bg-orange-500 rounded-full">New</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center space-x-3">
                                        <a href="{{ $notification->data['url'] ?? '#' }}" class="text-sm font-semibold text-orange-600 dark:text-orange-400 hover:underline">
                                            View details
                                        </a>
                                        @if($notification->status === 'unread')
                                            <form method="POST" action="{{ route('notifications.mark-read', $notification) }}">
                                                @csrf
                                                <button type="submit" class="text-sm text-gray-500 dark:text-gray-300 hover:underline">
                                                    Mark as read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>

