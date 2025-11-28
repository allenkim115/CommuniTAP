<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class TaskSubmissionController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    /**
     * Display a listing of task submissions
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $taskType = $request->get('task_type');
        
        // Exclude user-uploaded tasks; those are approved/rejected by the creator
        // Also exclude closed submissions (completed or 3+ rejections)
        $query = TaskAssignment::with(['task', 'user'])
            ->where('status', 'submitted')
            ->where(function ($q) {
                $q->where('rejection_count', '<', 3)
                  ->orWhereNull('rejection_count');
            })
            ->whereHas('task', function ($q) {
                $q->where('task_type', '!=', 'user_uploaded');
            });

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('task', function ($taskQuery) use ($search) {
                    $taskQuery->where('title', 'LIKE', '%' . $search . '%')
                              ->orWhere('description', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', '%' . $search . '%')
                              ->orWhere('email', 'LIKE', '%' . $search . '%');
                });
            });
        }

        // Apply task type filter
        if ($taskType && $taskType !== 'all') {
            $query->whereHas('task', function ($q) use ($taskType) {
                $q->where('task_type', $taskType);
            });
        }

        $submissions = $query->orderBy('submitted_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

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
        // Block admin from approving user-uploaded tasks; only the creator may approve
        $task = $submission->task;
        if ($task && $task->task_type === 'user_uploaded' && !is_null($task->FK1_userId)) {
            return redirect()->back()->with('error', 'Only the task creator can approve user-uploaded task submissions.');
        }

        // Prevent approving already completed submissions
        if ($submission->status === 'completed') {
            return redirect()->back()->with('error', 'This submission has already been approved and is closed.');
        }

        // Prevent approving submissions that have reached maximum rejection attempts
        if ($submission->rejection_count >= 3) {
            return redirect()->back()->with('error', 'This submission has reached the maximum number of rejection attempts (3) and is closed.');
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        // Preserve user's original completion notes
        $userNotes = $submission->completion_notes ?? '';
        $adminNotes = $request->admin_notes;
        
        // If admin provided notes, append them to user's notes; otherwise keep user's notes
        if ($adminNotes) {
            $finalNotes = $userNotes 
                ? $userNotes . "\n\n--- Admin Notes ---\n" . $adminNotes
                : $adminNotes;
        } else {
            // Keep user's notes, or set default if empty
            $finalNotes = $userNotes ?: 'Approved by admin';
        }

        $submission->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $finalNotes
        ]);

        // Award points to user (respecting points cap)
        $user = $submission->user;
        $pointsResult = $user->addPoints($submission->task->points_awarded);
        
        $pointsMessage = $pointsResult['added'] > 0 
            ? "{$pointsResult['added']} points have been added to your balance."
            : 'You have reached the points cap (500 points). No points were added.';
        
        if ($pointsResult['capped'] && $pointsResult['added'] > 0) {
            $pointsMessage .= " You reached the maximum points limit, so only {$pointsResult['added']} of {$submission->task->points_awarded} points were added.";
        }

        $this->notificationService->notify(
            $user,
            'task_submission_approved',
            "Your submission for \"{$submission->task->title}\" was approved!",
            [
                'url' => route('tasks.show', $submission->task),
                'description' => $pointsMessage,
            ]
        );

        $statusMessage = $pointsResult['added'] > 0
            ? "Task submission approved successfully. User has been awarded {$pointsResult['added']} points."
            : "Task submission approved successfully. User has reached the points cap (500 points), so no points were added.";

        $userName = $user->firstName . ' ' . $user->lastName;
        $taskTitle = $submission->task->title;
        $pointsAwarded = $pointsResult['added'] > 0 ? $pointsResult['added'] : 0;
        $statusMessage = "Submission from {$userName} for '{$taskTitle}' has been approved";
        if ($pointsAwarded > 0) {
            $statusMessage .= " and {$pointsAwarded} points have been awarded to their account";
        } elseif ($pointsResult['capped']) {
            $statusMessage .= ". Note: User has reached the 500 point cap, so no additional points were awarded";
        }
        $statusMessage .= '.';
        return redirect()->route('admin.task-submissions.index')->with('status', $statusMessage);
    }

    /**
     * Reject a task submission
     */
    public function reject(Request $request, TaskAssignment $submission)
    {
        // Block admin from rejecting user-uploaded tasks; only the creator may reject
        $task = $submission->task;
        if ($task && $task->task_type === 'user_uploaded' && !is_null($task->FK1_userId)) {
            return redirect()->back()->with('error', 'Only the task creator can reject user-uploaded task submissions.');
        }
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        // Prevent rejecting already completed submissions
        if ($submission->status === 'completed') {
            return redirect()->back()->with('error', 'This submission has already been approved and is closed.');
        }

        // Check if user has exceeded maximum rejection attempts (3)
        if ($submission->rejection_count >= 3) {
            return redirect()->back()
                ->with('error', 'This user has already reached the maximum number of rejection attempts (3). Cannot reject again.');
        }

        // Increment rejection count
        $newRejectionCount = $submission->rejection_count + 1;
        
        // If this is the 3rd rejection, mark as uncompleted (closed) instead of assigned
        $newStatus = $newRejectionCount >= 3 ? 'uncompleted' : 'assigned';
        
        $submission->update([
            'status' => $newStatus, // Set to uncompleted if 3rd rejection, otherwise assigned so user can resubmit
            'submitted_at' => $newRejectionCount >= 3 ? $submission->submitted_at : null, // Keep submitted_at if closing
            'rejection_count' => $newRejectionCount,
            'rejection_reason' => $request->rejection_reason,
            'completion_notes' => 'Rejected (Attempt ' . $newRejectionCount . '/3): ' . $request->rejection_reason
        ]);

        $remainingAttempts = 3 - $newRejectionCount;
        $message = $remainingAttempts > 0 
            ? "Task submission rejected. User has {$remainingAttempts} remaining attempts to resubmit."
            : "Task submission rejected. User has reached the maximum number of attempts (3).";

        $this->notificationService->notify(
            $submission->user,
            'task_submission_rejected',
            "Your submission for \"{$submission->task->title}\" needs more work.",
            [
                'url' => route('tasks.show', $submission->task),
                'description' => "{$request->rejection_reason} {$message}",
            ]
        );

        $userName = $submission->user->firstName . ' ' . $submission->user->lastName;
        $taskTitle = $submission->task->title;
        $message = "Submission from {$userName} for '{$taskTitle}' has been rejected";
        if ($remainingAttempts > 0) {
            $message .= ". They have {$remainingAttempts} remaining attempt" . ($remainingAttempts > 1 ? 's' : '') . " to resubmit with improvements.";
        } else {
            $message .= ". Maximum attempts (3) reached - this submission is now closed.";
        }
        return redirect()->route('admin.task-submissions.index')->with('status', $message);
    }

    /**
     * Display history of completed and rejected submissions
     */
    public function history(Request $request)
    {
        $type = $request->get('type', 'completed'); // 'completed' or 'rejected'
        $search = $request->get('search');
        $taskType = $request->get('task_type');
        
        // Exclude user-uploaded tasks; those are approved/rejected by the creator
        $query = TaskAssignment::with(['task', 'user'])
            ->whereHas('task', function ($q) {
                $q->where('task_type', '!=', 'user_uploaded');
            });

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('task', function ($taskQuery) use ($search) {
                    $taskQuery->where('title', 'LIKE', '%' . $search . '%')
                              ->orWhere('description', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', '%' . $search . '%')
                              ->orWhere('email', 'LIKE', '%' . $search . '%');
                });
            });
        }

        // Apply task type filter
        if ($taskType && $taskType !== 'all') {
            $query->whereHas('task', function ($q) use ($taskType) {
                $q->where('task_type', $taskType);
            });
        }

        if ($type === 'completed') {
            $submissions = $query->where('status', 'completed')
                ->orderBy('completed_at', 'desc')
                ->paginate(15)
                ->appends($request->query());
        } else {
            // Rejected submissions: status is 'uncompleted' with 3+ rejections, or 'assigned' with rejection_count >= 3
            $submissions = $query->where(function ($q) {
                    $q->where(function ($q2) {
                        $q2->where('status', 'uncompleted')
                           ->where('rejection_count', '>=', 3);
                    })->orWhere(function ($q2) {
                        $q2->where('status', 'assigned')
                           ->where('rejection_count', '>=', 3);
                    });
                })
                ->whereNotNull('rejection_reason')
                ->orderBy('updated_at', 'desc')
                ->paginate(15)
                ->appends($request->query());
        }

        // Get statistics
        $stats = [
            'total_completed' => TaskAssignment::where('status', 'completed')
                ->whereHas('task', function ($q) {
                    $q->where('task_type', '!=', 'user_uploaded');
                })->count(),
            'total_rejected' => TaskAssignment::where(function ($q) {
                    $q->where(function ($q2) {
                        $q2->where('status', 'uncompleted')
                           ->where('rejection_count', '>=', 3);
                    })->orWhere(function ($q2) {
                        $q2->where('status', 'assigned')
                           ->where('rejection_count', '>=', 3);
                    });
                })
                ->whereNotNull('rejection_reason')
                ->whereHas('task', function ($q) {
                    $q->where('task_type', '!=', 'user_uploaded');
                })->count(),
        ];

        return view('admin.task-submissions.history', compact('submissions', 'type', 'stats'));
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