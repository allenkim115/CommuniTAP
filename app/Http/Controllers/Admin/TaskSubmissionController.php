<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskAssignment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TaskSubmissionController extends Controller
{
    /**
     * Display a listing of task submissions
     */
    public function index()
    {
        $submissions = TaskAssignment::with(['task', 'user'])
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);

        return view('admin.task-submissions.index', compact('submissions'));
    }

    /**
     * Display the specified task submission
     */
    public function show(TaskAssignment $submission)
    {
        $submission->load(['task', 'user']);
        
        return view('admin.task-submissions.show', compact('submission'));
    }

    /**
     * Approve a task submission
     */
    public function approve(Request $request, TaskAssignment $submission)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $submission->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $request->admin_notes ?? 'Approved by admin'
        ]);

        // Award points to user
        $user = $submission->user;
        $user->increment('points', $submission->task->points_awarded);

        return redirect()->route('admin.admin.task-submissions.index')
            ->with('status', 'Task submission approved successfully. User has been awarded ' . $submission->task->points_awarded . ' points.');
    }

    /**
     * Reject a task submission
     */
    public function reject(Request $request, TaskAssignment $submission)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        // Check if user has exceeded maximum rejection attempts (3)
        if ($submission->rejection_count >= 3) {
            return redirect()->back()
                ->with('error', 'This user has already reached the maximum number of rejection attempts (3). Cannot reject again.');
        }

        // Increment rejection count
        $newRejectionCount = $submission->rejection_count + 1;
        
        $submission->update([
            'status' => 'assigned', // Reset to assigned so user can resubmit
            'submitted_at' => null,
            'rejection_count' => $newRejectionCount,
            'rejection_reason' => $request->rejection_reason,
            'completion_notes' => 'Rejected (Attempt ' . $newRejectionCount . '/3): ' . $request->rejection_reason
        ]);

        $remainingAttempts = 3 - $newRejectionCount;
        $message = $remainingAttempts > 0 
            ? "Task submission rejected. User has {$remainingAttempts} remaining attempts to resubmit."
            : "Task submission rejected. User has reached the maximum number of attempts (3).";

        return redirect()->route('admin.admin.task-submissions.index')
            ->with('status', $message);
    }

    /**
     * Get statistics for the dashboard
     */
    public function getStats()
    {
        $stats = [
            'pending_submissions' => TaskAssignment::where('status', 'submitted')->count(),
            'completed_today' => TaskAssignment::where('status', 'completed')
                ->whereDate('completed_at', today())->count(),
            'total_completed' => TaskAssignment::where('status', 'completed')->count(),
            'rejected_today' => TaskAssignment::where('status', 'assigned')
                ->whereNotNull('completion_notes')
                ->where('completion_notes', 'like', 'Rejected:%')
                ->whereDate('updated_at', today())->count(),
        ];

        return response()->json($stats);
    }
}