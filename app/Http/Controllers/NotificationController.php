<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
        $this->middleware(['auth', 'active']);
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $notifications = Notification::where('FK1_userId', $user->userId)
            ->latest('created_at')
            ->paginate(15);

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $user->systemUnreadNotifications()->count(),
        ]);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $this->notificationService->markAllRead($request->user());

        return redirect()->back()->with('status', 'All notifications marked as read.');
    }

    public function markAsRead(Notification $notification, Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($notification->FK1_userId !== $user->userId) {
            abort(403);
        }

        $notification->markAsRead();

        $redirectUrl = $notification->data['url'] ?? route('notifications.index');

        return redirect($redirectUrl)->with('status', 'Notification marked as read.');
    }
}

