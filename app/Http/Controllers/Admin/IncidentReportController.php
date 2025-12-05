<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserIncidentReport;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentReportController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    /**
     * Display a listing of all incident reports for admin
     */
    public function index(Request $request)
    {
        $query = UserIncidentReport::with(['reporter', 'reportedUser', 'task', 'moderator']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by incident type
        if ($request->filled('incident_type')) {
            $query->where('incident_type', $request->incident_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('report_date', '<=', $request->date_to);
        }

        // Search by reporter or reported user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('reporter', function ($q2) use ($search) {
                    $q2->where('firstName', 'like', "%{$search}%")
                    ->orWhere('lastName', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('reportedUser', function ($q2) use ($search) {
                    $q2->where('firstName', 'like', "%{$search}%")
                    ->orWhere('lastName', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }
        $reports = $query->orderBy('report_date', 'desc')->paginate(15);

        $statuses = UserIncidentReport::getStatuses();
        $incidentTypes = UserIncidentReport::getIncidentTypes();

        // Statistics
        $stats = [
            'total' => UserIncidentReport::count(),
            'pending' => UserIncidentReport::pending()->count(),
            'under_review' => UserIncidentReport::underReview()->count(),
            'resolved' => UserIncidentReport::resolved()->count(),
            'dismissed' => UserIncidentReport::dismissed()->count(),
        ];

        return view('admin.incident-reports.index', compact('reports', 'statuses', 'incidentTypes', 'stats'));
    }

    /**
     * Display the specified incident report
     */
    public function show(UserIncidentReport $incidentReport)
    {
        $incidentReport->load(['reporter', 'reportedUser', 'task', 'moderator']);
        
        return view('admin.incident-reports.show', compact('incidentReport'));
    }

    /**
     * Show the form for editing the specified incident report
     */
    public function edit(UserIncidentReport $incidentReport)
    {
        $incidentReport->load(['reporter', 'reportedUser', 'task']);

        // Prevent editing of finalized reports
        if ($incidentReport->isResolved() || $incidentReport->isDismissed()) {
            return redirect()
                ->route('admin.incident-reports.show', $incidentReport)
                ->with('error', 'Resolved or dismissed reports can no longer be edited.');
        }

        $statuses = UserIncidentReport::getStatuses();
        $actionsTaken = UserIncidentReport::getActionsTaken();
        
        return view('admin.incident-reports.edit', compact('incidentReport', 'statuses', 'actionsTaken'));
    }

    /**
     * Update the specified incident report
     */
    public function update(Request $request, UserIncidentReport $incidentReport)
    {
        // Prevent updates if already finalized
        if ($incidentReport->isResolved() || $incidentReport->isDismissed()) {
            return redirect()
                ->route('admin.incident-reports.show', $incidentReport)
                ->with('error', 'Resolved or dismissed reports can no longer be edited.');
        }

        $request->validate([
            'status' => 'required|string|in:pending,under_review,resolved,dismissed',
            'moderator_notes' => 'nullable|string|max:1000',
            'action_taken' => 'nullable|string|in:warning,suspension,no_action',
        ]);

        try {
            $incidentReport->update([
                'status' => $request->status,
                'moderator_notes' => $request->moderator_notes,
                'action_taken' => $request->action_taken,
                'FK4_moderatorId' => Auth::id(),
                'moderation_date' => now(),
            ]);

            // If action is suspension, suspend the reported user
            if ($request->action_taken === 'suspension') {
                $reportedUser = User::find($incidentReport->FK2_reportedUserId);
                if ($reportedUser) {
                    $reportedUser->update(['status' => 'suspended']);

                    $this->notificationService->notify(
                        $reportedUser,
                        'account_suspension',
                        'Your account has been suspended following an incident review.',
                        [
                            'url' => route('profile.edit'),
                            'description' => 'Please contact support for more details.',
                        ]
                    );
                }
            }

            if ($incidentReport->reporter) {
                $description = $request->moderator_notes
                    ? "Moderator notes: {$request->moderator_notes}"
                    : null;

                $this->notificationService->notify(
                    $incidentReport->reporter,
                    'incident_report_update',
                    "Your incident report #{$incidentReport->reportId} is now {$incidentReport->status}.",
                    [
                        'url' => route('incident-reports.show', $incidentReport),
                        'description' => $description,
                    ]
                );
            }

            // Notify the reported user when a warning is issued and deduct points
            if ($request->action_taken === 'warning' && $incidentReport->reportedUser) {
                $reportedUser = $incidentReport->reportedUser;
                
                // Deduct points for warning (default: 10 points)
                $pointsToDeduct = 10;
                $previousPoints = $reportedUser->points;
                $reportedUser->points = max(0, $reportedUser->points - $pointsToDeduct);
                $reportedUser->save();
                $pointsDeducted = $previousPoints - $reportedUser->points;
                
                $warningMessage = 'You have received a warning. Repeated violations may result in account suspension.';
                $warningDescription = $request->moderator_notes
                    ? "Moderator notes: {$request->moderator_notes} • Please review the community guidelines. Repeated violations may result in account suspension."
                    : 'Please review the community guidelines. Repeated violations may result in account suspension.';
                
                if ($pointsDeducted > 0) {
                    $warningDescription .= " {$pointsDeducted} point" . ($pointsDeducted > 1 ? 's have' : ' has') . " been deducted from your account.";
                }

                $this->notificationService->notify(
                    $reportedUser,
                    'incident_warning',
                    $warningMessage,
                    [
                        'url' => route('profile.edit', ['notice' => 'incident_warning']),
                        'description' => $warningDescription,
                    ]
                );
            }

            $actionTaken = $request->action_taken ? ucfirst(str_replace('_', ' ', $request->action_taken)) : 'No action';
            return redirect()->route('admin.incident-reports.index')
                ->with('success', "Incident report #{$incidentReport->reportId} has been updated successfully. Status: {$request->status}. Action taken: {$actionTaken}. All relevant parties have been notified.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update incident report. Please try again.');
        }
    }

    /**
     * Remove the specified incident report from storage
     */
    public function destroy(UserIncidentReport $incidentReport)
    {
        try {
            $incidentReport->delete();
            return redirect()->route('admin.incident-reports.index')
                ->with('success', "Incident report #{$incidentReport->reportId} has been permanently deleted from the system.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete incident report. Please try again.');
        }
    }

    /**
     * Bulk update incident reports
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:user_incident_reports,reportId',
            'action' => 'required|string|in:mark_pending,mark_under_review,mark_resolved,mark_dismissed,delete',
        ]);

        $reportIds = $request->report_ids;
        $action = $request->action;

        try {
            switch ($action) {
                case 'mark_pending':
                    UserIncidentReport::whereIn('reportId', $reportIds)
                        ->update(['status' => 'pending', 'FK4_moderatorId' => Auth::id(), 'moderation_date' => now()]);
                    break;
                case 'mark_under_review':
                    UserIncidentReport::whereIn('reportId', $reportIds)
                        ->update(['status' => 'under_review', 'FK4_moderatorId' => Auth::id(), 'moderation_date' => now()]);
                    break;
                case 'mark_resolved':
                    UserIncidentReport::whereIn('reportId', $reportIds)
                        ->update(['status' => 'resolved', 'FK4_moderatorId' => Auth::id(), 'moderation_date' => now()]);
                    break;
                case 'mark_dismissed':
                    UserIncidentReport::whereIn('reportId', $reportIds)
                        ->update(['status' => 'dismissed', 'FK4_moderatorId' => Auth::id(), 'moderation_date' => now()]);
                    break;
                case 'delete':
                    UserIncidentReport::whereIn('reportId', $reportIds)->delete();
                    break;
            }

            $actionLabel = ucfirst(str_replace('_', ' ', $action));
            $count = count($reportIds);
            return redirect()->route('admin.incident-reports.index')
                ->with('success', "Bulk action completed successfully: {$actionLabel} applied to {$count} incident report" . ($count > 1 ? 's' : '') . ".");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to perform bulk action. Please try again.');
        }
    }

    /**
     * Get incident report statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_reports' => UserIncidentReport::count(),
            'pending_reports' => UserIncidentReport::pending()->count(),
            'resolved_reports' => UserIncidentReport::resolved()->count(),
            'reports_this_week' => UserIncidentReport::where('report_date', '>=', now()->subWeek())->count(),
            'reports_this_month' => UserIncidentReport::where('report_date', '>=', now()->subMonth())->count(),
        ];

        return response()->json($stats);
    }
}
