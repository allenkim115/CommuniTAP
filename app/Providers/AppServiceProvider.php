<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function ($view): void {
            $user = Auth::user();

            if (!$user || $user->isAdmin()) {
                $view->with('notificationSummary', [
                    'unreadCount' => 0,
                    'recentNotifications' => collect(),
                ]);

                return;
            }

            $userNotificationTypes = [
                'task_assigned',
                'task_participant_joined',
                'task_submission_pending',
                'task_submission_approved',
                'task_submission_rejected',
                'task_assignment_completed',
                'task_progress_updated',
                'tap_nomination_received',
                'tap_nomination_accepted',
                'tap_nomination_declined',
                'reward_redeemed',
                'incident_report_update',
                'task_proposal_approved',
                'task_proposal_rejected',
                'task_proposal_published',
                'task_proposal_reactivated',
                'task_published',
            ];

            $view->with('notificationSummary', [
                'unreadCount' => $user->systemUnreadNotifications()->whereIn('type', $userNotificationTypes)->count(),
                'recentNotifications' => $user->systemNotifications()->whereIn('type', $userNotificationTypes)->limit(5)->get(),
            ]);
        });

        View::composer('layouts.partials.admin-navigation', function ($view): void {
            $user = Auth::user();

            if (!$user || !$user->isAdmin()) {
                $view->with('adminNotificationSummary', [
                    'unreadCount' => 0,
                    'recentNotifications' => collect(),
                ]);

                return;
            }

            $adminNotificationTypes = [
                'task_proposal_submitted',
                'task_submission_admin_review',
                'reward_claim_submitted',
                'incident_report_submitted',
            ];

            $view->with('adminNotificationSummary', [
                'unreadCount' => $user->systemUnreadNotifications()->whereIn('type', $adminNotificationTypes)->count(),
                'recentNotifications' => $user->systemNotifications()->whereIn('type', $adminNotificationTypes)->limit(5)->get(),
            ]);
        });
    }
}
