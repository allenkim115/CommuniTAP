<?php

namespace App\Http\Controllers;

use App\Models\UserIncidentReport;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncidentReportController extends Controller
{
    /**
     * Display a listing of incident reports for regular users (their own reports)
     */
    public function index()
    {
        $user = Auth::user();
        
        $reports = UserIncidentReport::where('FK1_reporterId', $user->userId)
            ->with(['reportedUser', 'task', 'moderator'])
            ->orderBy('report_date', 'desc')
            ->paginate(10);

        return view('incident-reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new incident report
     */
    public function create(Request $request)
    {
        $reportedUserId = $request->get('reported_user');
        $taskId = $request->get('task');
        
        $reportedUser = null;
        $task = null;
        
        if ($reportedUserId) {
            $reportedUser = User::find($reportedUserId);
        }
        
        if ($taskId) {
            $task = Task::find($taskId);
        }

        // Get all active users (excluding current user)
        $users = User::where('userId', '!=', Auth::user()->userId)
            ->where('status', 'active')
            ->orderBy('firstName')
            ->orderBy('lastName')
            ->get(['userId', 'firstName', 'lastName', 'email']);

        // Get all active/published tasks
        $tasks = Task::whereIn('status', ['published', 'approved', 'completed'])
            ->orderBy('title')
            ->get(['taskId', 'title', 'description']);

        $incidentTypes = UserIncidentReport::getIncidentTypes();
        
        return view('incident-reports.create', compact('reportedUser', 'task', 'incidentTypes', 'users', 'tasks'));
    }

    /**
     * Store a newly created incident report
     */
    public function store(Request $request)
    {
        $request->validate([
            'reported_user_id' => 'required|exists:users,userId',
            'incident_type' => 'required|string|max:50',
            'description' => 'required|string|min:10|max:1000',
            'evidence' => 'nullable|string|max:1000',
            'task_id' => 'nullable|exists:tasks,taskId',
        ]);

        // Prevent users from reporting themselves
        if ($request->reported_user_id == Auth::user()->userId) {
            return back()->withErrors(['reported_user_id' => 'You cannot report yourself.']);
        }

        // Check if user has already reported this user for the same incident type recently
        $existingReport = UserIncidentReport::where('FK1_reporterId', Auth::user()->userId)
            ->where('FK2_reportedUserId', $request->reported_user_id)
            ->where('incident_type', $request->incident_type)
            ->where('created_at', '>=', now()->subDays(7))
            ->first();

        if ($existingReport) {
            return back()->withErrors(['incident_type' => 'You have already reported this user for this type of incident within the last 7 days.']);
        }

        try {
            UserIncidentReport::create([
                'FK1_reporterId' => Auth::user()->userId,
                'FK2_reportedUserId' => $request->reported_user_id,
                'FK3_taskId' => $request->task_id ?: null,
                'incident_type' => $request->incident_type,
                'description' => $request->description,
                'evidence' => $request->evidence,
                'status' => 'pending',
                'report_date' => now(),
            ]);

            return redirect()->route('incident-reports.index')
                ->with('success', 'Incident report submitted successfully. Our moderation team will review it shortly.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to submit incident report. Please try again.']);
        }
    }

    /**
     * Display the specified incident report
     */
    public function show(UserIncidentReport $incidentReport)
    {
        $user = Auth::user();
        
        // Users can only view their own reports unless they're admin
        if (!$user->isAdmin() && $incidentReport->FK1_reporterId !== $user->userId) {
            abort(403, 'Unauthorized access to incident report.');
        }

        $incidentReport->load(['reporter', 'reportedUser', 'task', 'moderator']);
        
        return view('incident-reports.show', compact('incidentReport'));
    }

    /**
     * Show the form for editing the specified incident report (admin only)
     */
    public function edit(UserIncidentReport $incidentReport)
    {
        $this->authorize('update', $incidentReport);
        
        $incidentReport->load(['reporter', 'reportedUser', 'task']);
        $statuses = UserIncidentReport::getStatuses();
        $actionsTaken = UserIncidentReport::getActionsTaken();
        
        return view('incident-reports.edit', compact('incidentReport', 'statuses', 'actionsTaken'));
    }

    /**
     * Update the specified incident report (admin only)
     */
    public function update(Request $request, UserIncidentReport $incidentReport)
    {
        $this->authorize('update', $incidentReport);
        
        $request->validate([
            'status' => 'required|string|in:pending,under_review,resolved,dismissed',
            'moderator_notes' => 'nullable|string|max:1000',
            'action_taken' => 'nullable|string|in:warning,suspension,no_action,dismissed',
        ]);

        try {
            DB::beginTransaction();

            $incidentReport->update([
                'status' => $request->status,
                'moderator_notes' => $request->moderator_notes,
                'action_taken' => $request->action_taken,
                'FK4_moderatorId' => Auth::user()->userId,
                'moderation_date' => now(),
            ]);

            // If action is suspension, suspend the reported user
            if ($request->action_taken === 'suspension') {
                $reportedUser = User::find($incidentReport->FK2_reportedUserId);
                if ($reportedUser) {
                    $reportedUser->update(['status' => 'suspended']);
                }
            }

            DB::commit();

            return redirect()->route('admin.incident-reports.index')
                ->with('success', 'Incident report updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update incident report. Please try again.']);
        }
    }

    /**
     * Remove the specified incident report from storage
     */
    public function destroy(UserIncidentReport $incidentReport)
    {
        $this->authorize('delete', $incidentReport);
        
        try {
            $incidentReport->delete();
            return redirect()->route('admin.incident-reports.index')
                ->with('success', 'Incident report deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete incident report. Please try again.']);
        }
    }

    /**
     * Get users for reporting (AJAX endpoint)
     */
    public function getUsers(Request $request)
    {
        \Log::info('getUsers method called', [
            'query' => $request->get('q'),
            'user_id' => Auth::id(),
            'headers' => $request->headers->all()
        ]);
        
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $users = User::where('userId', '!=', Auth::user()->userId) // Exclude current user
                ->where(function ($q) use ($query) {
                    $q->where('firstName', 'like', "%{$query}%")
                      ->orWhere('lastName', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get(['userId', 'firstName', 'lastName', 'email']);

            \Log::info('Users found', ['count' => $users->count(), 'users' => $users->toArray()]);

            return response()->json($users);
        } catch (\Exception $e) {
            \Log::error('Error in getUsers', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Search failed'], 500);
        }
    }

    /**
     * Get tasks for reporting (AJAX endpoint)
     */
    public function getTasks(Request $request)
    {
        \Log::info('getTasks method called', [
            'query' => $request->get('q'),
            'user_id' => Auth::id()
        ]);
        
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $tasks = Task::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(10)
                ->get(['taskId', 'title', 'description']);

            \Log::info('Tasks found', ['count' => $tasks->count(), 'tasks' => $tasks->toArray()]);

            return response()->json($tasks);
        } catch (\Exception $e) {
            \Log::error('Error in getTasks', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Search failed'], 500);
        }
    }
}
