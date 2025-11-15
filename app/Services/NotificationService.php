<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Create a notification for a specific user.
     *
     * @param  \App\Models\User|int  $user
     * @param  string  $type
     * @param  string  $message
     * @param  array<string, mixed>  $data
     */
    public function notify(User|int $user, string $type, string $message, array $data = []): Notification
    {
        $userId = $user instanceof User ? $user->userId : $user;

        return Notification::create([
            'FK1_userId' => $userId,
            'type' => $type,
            'message' => $message,
            'data' => $data ?: null,
        ]);
    }

    /**
     * Send a notification to multiple users.
     *
     * @param  iterable<int, \App\Models\User|int>  $users
     * @return \Illuminate\Support\Collection<int, \App\Models\Notification>
     */
    public function notifyMany(iterable $users, string $type, string $message, array $data = []): Collection
    {
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = $this->notify($user, $type, $message, $data);
        }

        return collect($notifications);
    }

    /**
     * Mark all notifications for a user as read.
     */
    public function markAllRead(User|int $user): void
    {
        $userId = $user instanceof User ? $user->userId : $user;

        Notification::where('FK1_userId', $userId)
            ->where('status', 'unread')
            ->update(['status' => 'read']);
    }
}

