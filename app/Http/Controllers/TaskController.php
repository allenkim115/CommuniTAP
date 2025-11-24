<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Feedback;
use App\Models\TaskAssignment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    /**
     * Update progress for the authenticated user's assignment on a task
     */
    public function updateProgress(Request $request, Task $task)
    {
        $user = Auth::user();
        $request->validate([
            'progress' => 'required|in:accepted,on_the_way,working,done,submitted_proof'
        ]);

        $assignment = TaskAssignment::where('taskId', $task->taskId)
            ->where('userId', $user->userId)
            ->first();

        if (!$assignment) {
            abort(403, 'You can only update progress for tasks assigned to you.');
        }

        // Prevent moving to submitted_proof via this endpoint; that happens on submit
        if ($request->progress === 'submitted_proof') {
            return redirect()->back()->with('error', 'To submit proof, please use the "Submit Task" button with your photos and completion notes.');
        }

        // Enforce STRICT forward-only, one-step-at-a-time progress
        $order = ['accepted','on_the_way','working','done'];
        $currentProgress = $assignment->progress ?? 'accepted';
        $currentIndex = array_search($currentProgress, $order);
        $requestedIndex = array_search($request->progress, $order);

        if ($currentIndex === false || $requestedIndex === false) {
            return redirect()->back()->with('error', 'Invalid progress state. Please refresh the page and try again.');
        }

        // No backtracking
        if ($requestedIndex < $currentIndex) {
            $currentState = ucfirst(str_replace('_', ' ', $currentProgress));
            $requestedState = ucfirst(str_replace('_', ' ', $request->progress));
            return redirect()->back()->with('error', "Cannot go back from '{$currentState}' to '{$requestedState}'. Progress must move forward only.");
        }

        // No skipping steps; must move exactly to the next step
        if ($requestedIndex !== $currentIndex + 1) {
            $nextState = ucfirst(str_replace('_', ' ', $order[$currentIndex + 1] ?? 'done'));
            return redirect()->back()->with('error', "Please complete the current step first. The next step is: '{$nextState}'.");
        }

        $assignment->update([
            'progress' => $request->progress,
        ]);

        // Notify task creator about progress update if applicable
        if (!is_null($task->FK1_userId) && $task->FK1_userId !== $user->userId && $task->assignedUser) {
            $this->notificationService->notify(
                $task->assignedUser,
                'task_progress_updated',
                "{$user->firstName} updated progress on \"{$task->title}\" to " . str_replace('_', ' ', $request->progress) . ".",
                [
                    'url' => route('tasks.show', $task),
                    'description' => 'Review the task progress and provide guidance if needed.',
                ]
            );
        }

        $progressLabel = ucfirst(str_replace('_', ' ', $request->progress));
        return redirect()->route('tasks.show', $task)->with('status', "Task progress updated to '{$progressLabel}' for '{$task->title}'.");
    }
    /**
     * Display a listing of tasks for the authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'available'); // Default to available tasks
        
        // Get tasks assigned to the user through task_assignments table with pivot data
        // Exclude uncompleted tasks and inactive tasks
        $userTasks = $user->assignedTasks()
            ->wherePivot('status', '!=', 'uncompleted')
            ->where('tasks.status', '!=', 'inactive') // Exclude inactive/deactivated tasks (specify table to avoid ambiguity)
            ->withPivot('status', 'assigned_at', 'submitted_at', 'completed_at', 'photos', 'completion_notes', 'rejection_count', 'rejection_reason')
            ->with(['assignments.user', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Add completion date check for each user task
        $userTasks->each(function($task) {
            if ($task->pivot && $task->pivot->completed_at) {
                $task->pivot->completed_today = \Carbon\Carbon::parse($task->pivot->completed_at)->isToday();
            } else {
                $task->pivot->completed_today = false;
            }
        });
            
        // Get available tasks that user can join (published tasks) that are not expired
        // Published tasks cannot be inactive, but we exclude inactive for safety
        $availableTasks = Task::where('tasks.status', 'published')
            ->where('tasks.status', '!=', 'inactive') // Exclude inactive/deactivated tasks
            ->notExpired()
            ->whereDoesntHave('assignments', function($query) use ($user) {
                $query->where('userId', $user->userId);
            })
            // Exclude tasks created by the current user
            ->where(function($q) use ($user) {
                $q->whereNull('FK1_userId')
                  ->orWhere('FK1_userId', '!=', $user->userId);
            })
            ->with(['assignments.user', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Filter user tasks based on selected filter
        $filteredTasks = collect();
        $today = \Carbon\Carbon::today(); // Define once for use in filtering and stats
        
        switch($filter) {
            case 'available':
                $filteredTasks = $availableTasks;
                break;
            case 'assigned':
                $filteredTasks = $userTasks->where('pivot.status', 'assigned');
                break;
            case 'submitted':
                $filteredTasks = $userTasks->where('pivot.status', 'submitted');
                break;
            case 'completed':
                // Only show tasks completed today
                $filteredTasks = $userTasks->filter(function($task) use ($today) {
                    if ($task->pivot->status !== 'completed') {
                        return false;
                    }
                    if (!$task->pivot->completed_at) {
                        return false;
                    }
                    $completedDate = \Carbon\Carbon::parse($task->pivot->completed_at);
                    return $completedDate->isSameDay($today);
                });
                break;
            case 'all':
                // For "all" tab, only show completed tasks from today
                $filteredUserTasks = $userTasks->filter(function($task) use ($today) {
                    // If task is completed, only include if completed today
                    if ($task->pivot->status === 'completed') {
                        if (!$task->pivot->completed_at) {
                            return false;
                        }
                        $completedDate = \Carbon\Carbon::parse($task->pivot->completed_at);
                        return $completedDate->isSameDay($today);
                    }
                    // Include all other statuses (assigned, submitted, etc.)
                    return true;
                });
                $filteredTasks = $filteredUserTasks->merge($availableTasks);
                break;
            default:
                $filteredTasks = $availableTasks;
        }

        // Task statistics for display
        // For completed tasks, only count those completed today
        $completedTodayCount = $userTasks->filter(function($task) use ($today) {
            if ($task->pivot->status !== 'completed') {
                return false;
            }
            if (!$task->pivot->completed_at) {
                return false;
            }
            $completedDate = \Carbon\Carbon::parse($task->pivot->completed_at);
            return $completedDate->isSameDay($today);
        })->count();

        // Calculate "all" count: available + assigned + submitted + completed today
        $allCount = $availableTasks->count() 
            + $userTasks->where('pivot.status', 'assigned')->count()
            + $userTasks->where('pivot.status', 'submitted')->count()
            + $completedTodayCount;

        $taskStats = [
            'available' => $availableTasks->count(),
            'assigned' => $userTasks->where('pivot.status', 'assigned')->count(),
            'submitted' => $userTasks->where('pivot.status', 'submitted')->count(),
            'completed' => $completedTodayCount,
            'all' => $allCount
        ];

        return view('tasks.index', compact('userTasks', 'availableTasks', 'filteredTasks', 'filter', 'taskStats'));
    }

    /**
     * Display a listing of the current user's uploaded tasks
     */
    public function myUploads(Request $request)
    {
        $user = Auth::user();

        $status = $request->get('status'); // pending | live | rejected | completed | all
        $search = trim((string) $request->get('q', ''));

        // Base query for this user's uploaded tasks
        // Include inactive tasks that have been edited (they should be shown as pending/waiting for publishing)
        $query = Task::where('task_type', 'user_uploaded')
            ->where('FK1_userId', $user->userId)
            ->where(function($q) {
                // Include non-inactive tasks, or inactive tasks that have been edited (updated after deactivation)
                $q->where('status', '!=', 'inactive')
                  ->orWhere(function($subQ) {
                      $subQ->where('status', 'inactive')
                           ->where(function($inactiveQ) {
                               // Include inactive tasks that have been edited (updated_at > deactivated_at)
                               // or inactive tasks without deactivated_at (treated as edited)
                               $inactiveQ->whereColumn('updated_at', '>', 'deactivated_at')
                                        ->orWhereNull('deactivated_at');
                           });
                  });
            })
            ->with(['assignments.user', 'assignedUser']);

        // Apply status filter
        if ($status && $status !== 'all') {
            if ($status === 'live') {
                $query->whereIn('status', ['approved', 'published']);
            } else {
                $query->where('status', $status);
            }
        }

        // Apply text search across common fields
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $uploads = $query->orderByDesc('created_at')->get();

        // Stats based on all tasks (ignoring current filters), used for tiles
        // Include inactive tasks that have been edited (they count as pending)
        $allForStats = Task::where('task_type', 'user_uploaded')
            ->where('FK1_userId', $user->userId)
            ->where(function($q) {
                // Include non-inactive tasks, or inactive tasks that have been edited (updated after deactivation)
                $q->where('status', '!=', 'inactive')
                  ->orWhere(function($subQ) {
                      $subQ->where('status', 'inactive')
                           ->where(function($inactiveQ) {
                               // Include inactive tasks that have been edited (updated_at > deactivated_at)
                               // or inactive tasks without deactivated_at (treated as edited)
                               $inactiveQ->whereColumn('updated_at', '>', 'deactivated_at')
                                        ->orWhereNull('deactivated_at');
                           });
                  });
            })
            ->get();

        // Count tasks, treating edited inactive tasks as pending
        $pendingCount = $allForStats->filter(function($task) {
            if ($task->status === 'pending') return true;
            // Count inactive tasks that have been edited as pending
            if ($task->status === 'inactive') {
                if ($task->deactivated_at) {
                    return $task->updated_at > $task->deactivated_at;
                }
                return true; // No deactivated_at means treated as edited
            }
            return false;
        })->count();

        $stats = [
            'pending' => $pendingCount,
            'live' => $allForStats->whereIn('status', ['approved', 'published'])->count(),
            'rejected' => $allForStats->where('status', 'rejected')->count(),
            'completed' => $allForStats->where('status', 'completed')->count(),
            'all' => $allForStats->count(),
        ];

        return view('tasks.my_uploads', [
            'uploads' => $uploads,
            'stats' => $stats,
            'activeStatus' => $status ?: 'all',
            'search' => $search,
        ]);
    }

    /**
     * List submissions awaiting review for tasks created by current user (user_uploaded)
     */
    public function creatorSubmissions(Request $request)
    {
        $user = Auth::user();
        // Exclude closed submissions (completed or 3+ rejections)
        $submissions = TaskAssignment::with(['task', 'user'])
            ->where('status', 'submitted')
            ->where(function ($q) {
                $q->where('rejection_count', '<', 3)
                  ->orWhereNull('rejection_count');
            })
            ->whereHas('task', function ($q) use ($user) {
                $q->where('task_type', 'user_uploaded')
                  ->where('FK1_userId', $user->userId);
            })
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);

        return view('tasks.creator_submissions', compact('submissions'));
    }

    /**
     * Show a single submission detail for creator review
     */
    public function creatorShow(TaskAssignment $submission)
    {
        $user = Auth::user();
        $submission->load(['task', 'user']);
        if (!$submission->task || $submission->task->task_type !== 'user_uploaded' || $submission->task->FK1_userId !== $user->userId) {
            abort(403, 'Unauthorized');
        }
        return view('tasks.creator_submission_show', compact('submission'));
    }

    /**
     * Creator approves a submission on their user-uploaded task
     */
    public function creatorApprove(Request $request, TaskAssignment $submission)
    {
        $user = Auth::user();
        // Ensure this submission belongs to a task created by this user and is user_uploaded
        $task = $submission->task;
        if (!$task || $task->task_type !== 'user_uploaded' || $task->FK1_userId !== $user->userId) {
            abort(403, 'You can only approve submissions for your user-uploaded tasks.');
        }

        // Prevent approving already completed submissions
        if ($submission->status === 'completed') {
            $assigneeName = $submission->user ? $submission->user->firstName . ' ' . $submission->user->lastName : 'This user';
            return redirect()->back()->with('error', "This submission from {$assigneeName} for '{$task->title}' has already been approved and points have been awarded.");
        }

        // Prevent approving submissions that have reached maximum rejection attempts
        if ($submission->rejection_count >= 3) {
            $assigneeName = $submission->user ? $submission->user->firstName . ' ' . $submission->user->lastName : 'This user';
            return redirect()->back()->with('error', "This submission from {$assigneeName} has been rejected 3 times and is now closed. No further attempts are allowed.");
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        $submission->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $request->notes ?? 'Approved by creator'
        ]);

        // Award points to the assignee (respecting points cap)
        $assignee = $submission->user;
        if ($assignee) {
            $pointsResult = $assignee->addPoints($submission->task->points_awarded);
            
            $pointsMessage = $pointsResult['added'] > 0 
                ? "{$pointsResult['added']} points have been added to your balance."
                : 'You have reached the points cap (500 points). No points were added.';
            
            if ($pointsResult['capped'] && $pointsResult['added'] > 0) {
                $pointsMessage .= " You reached the maximum points limit, so only {$pointsResult['added']} of {$submission->task->points_awarded} points were added.";
            }

            $this->notificationService->notify(
                $assignee,
                'task_submission_approved',
                "Your submission for \"{$submission->task->title}\" was approved!",
                [
                    'url' => route('tasks.show', $submission->task),
                    'description' => $pointsMessage,
                ]
            );
        }

        $assigneeName = $assignee ? $assignee->firstName . ' ' . $assignee->lastName : 'the participant';
        $pointsAwarded = $pointsResult['added'] > 0 ? $pointsResult['added'] : 0;
        $statusMessage = "Submission from {$assigneeName} for '{$task->title}' has been approved";
        if ($pointsAwarded > 0) {
            $statusMessage .= " and {$pointsAwarded} points have been awarded";
        } elseif ($pointsResult['capped']) {
            $statusMessage .= ". Note: Participant has reached the 500 point cap, so no additional points were awarded";
        }
        $statusMessage .= '.';

        return redirect()->route('tasks.creator.submissions')->with('status', $statusMessage);
    }

    /**
     * Creator rejects a submission on their user-uploaded task
     */
    public function creatorReject(Request $request, TaskAssignment $submission)
    {
        $user = Auth::user();
        $task = $submission->task;
        if (!$task || $task->task_type !== 'user_uploaded' || $task->FK1_userId !== $user->userId) {
            abort(403, 'You can only reject submissions for your user-uploaded tasks.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        // Prevent rejecting already completed submissions
        if ($submission->status === 'completed') {
            $assigneeName = $submission->user ? $submission->user->firstName . ' ' . $submission->user->lastName : 'This user';
            return redirect()->back()->with('error', "Cannot reject: {$assigneeName}'s submission for '{$task->title}' has already been approved and points have been awarded.");
        }

        if ($submission->rejection_count >= 3) {
            $assigneeName = $submission->user ? $submission->user->firstName . ' ' . $submission->user->lastName : 'This user';
            return redirect()->back()->with('error', "Cannot reject: {$assigneeName} has already reached the maximum of 3 rejection attempts for this submission.");
        }

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

        $assignee = $submission->user;
        if ($assignee) {
            $remainingAttempts = 3 - $newRejectionCount;
            $description = $remainingAttempts > 0
                ? "You can resubmit proof. Remaining attempts: {$remainingAttempts}."
                : 'No more attempts remaining.';

            $this->notificationService->notify(
                $assignee,
                'task_submission_rejected',
                "Your submission for \"{$submission->task->title}\" needs changes.",
                [
                    'url' => route('tasks.show', $submission->task),
                    'description' => "{$request->rejection_reason} {$description}",
                ]
            );
        }

        $assigneeName = $assignee ? $assignee->firstName . ' ' . $assignee->lastName : 'the participant';
        $remainingAttempts = 3 - $newRejectionCount;
        $statusMessage = "Submission from {$assigneeName} for '{$task->title}' has been rejected";
        if ($remainingAttempts > 0) {
            $statusMessage .= ". They have {$remainingAttempts} remaining attempt" . ($remainingAttempts > 1 ? 's' : '') . " to resubmit with improvements.";
        } else {
            $statusMessage .= ". Maximum attempts (3) reached - this submission is now closed.";
        }
        return redirect()->route('tasks.creator.submissions')->with('status', $statusMessage);
    }

    /**
     * Display history of completed and rejected submissions for user-uploaded tasks
     */
    public function creatorHistory(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'completed'); // 'completed' or 'rejected'
        $search = $request->get('search');
        $taskType = $request->get('task_type');
        
        // Only show submissions for tasks created by this user and are user_uploaded
        $query = TaskAssignment::with(['task', 'user'])
            ->whereHas('task', function ($q) use ($user) {
                $q->where('task_type', 'user_uploaded')
                  ->where('FK1_userId', $user->userId);
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

        // Apply task type filter (note: user_uploaded tasks don't have different task_types, but keeping for consistency)
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
                ->whereHas('task', function ($q) use ($user) {
                    $q->where('task_type', 'user_uploaded')
                      ->where('FK1_userId', $user->userId);
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
                ->whereHas('task', function ($q) use ($user) {
                    $q->where('task_type', 'user_uploaded')
                      ->where('FK1_userId', $user->userId);
                })->count(),
        ];

        return view('tasks.creator_submissions_history', compact('submissions', 'type', 'stats'));
    }

    /**
     * Show the form for creating a new task (User-Uploaded tasks only)
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'points_awarded' => 'required|integer|min:1',
            'due_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
        ]);

        // Custom validation for end_time after start_time
        if ($request->start_time && $request->end_time) {
            if (strtotime($request->end_time) <= strtotime($request->start_time)) {
                return redirect()->back()
                    ->withErrors(['end_time' => 'The end time must be after the start time.'])
                    ->withInput();
            }
        }

        // If due_date is today, ensure times are in the future
        $dueDate = \Carbon\Carbon::parse($request->due_date);
        $now = now();
        
        if ($dueDate->isToday() && $request->start_time && $request->end_time) {
            $currentTime = $now->format('H:i');
            
            // Check if start_time is in the past
            if (strtotime($request->start_time) <= strtotime($currentTime)) {
                return redirect()->back()
                    ->withErrors(['start_time' => 'The start time cannot be in the past. Please select a time later than ' . $currentTime . '.'])
                    ->withInput();
            }
            
            // Check if end_time is in the past
            if (strtotime($request->end_time) <= strtotime($currentTime)) {
                return redirect()->back()
                    ->withErrors(['end_time' => 'The end time cannot be in the past. Please select a time later than ' . $currentTime . '.'])
                    ->withInput();
            }
        }

        $user = Auth::user();

        $task = Task::create([
            'FK1_userId' => $user->userId,
            'title' => $request->title,
            'description' => $request->description,
            'task_type' => 'user_uploaded',
            'points_awarded' => $request->points_awarded,
            'status' => 'pending', // User-uploaded tasks need admin approval
            'creation_date' => now(),
            'due_date' => $request->due_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'max_participants' => $request->max_participants,
        ]);

        // Notify admins about a new task proposal pending approval
        $admins = User::where('role', 'admin')->where('status', 'active')->get(['userId']);
        if ($admins->isNotEmpty()) {
            $this->notificationService->notifyMany(
                $admins,
                'task_proposal_submitted',
                "New task proposal submitted: \"{$request->title}\"",
                [
                    'url' => route('admin.tasks.show', $task),
                    'description' => 'Review and approve or reject the proposal.',
                ]
            );
        }

        return redirect()->route('tasks.my-uploads')->with('status', "Task proposal '{$request->title}' has been submitted successfully! It is now pending admin review and will be published once approved.");
    }

    /**
     * Display the specified task
     */
    public function show(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Block access to inactive/deactivated tasks for regular users (even if they joined)
        // Only admins and task creators can view inactive tasks
        if ($task->status === 'inactive' && !$user->isAdmin()) {
            $isCreator = !is_null($task->FK1_userId) && $task->FK1_userId === $user->userId;
            if (!$isCreator) {
                abort(404, 'Task not found or has been deactivated.');
            }
        }
        
        // Check if user can view this task
        // Users can view:
        // - tasks they created (user-uploaded), regardless of status
        // - tasks they're assigned to (but not if inactive)
        // - published tasks
        // - admins can view all
        $isCreator = !is_null($task->FK1_userId) && $task->FK1_userId === $user->userId;
        $canView = $user->isAdmin() || 
                   $isCreator ||
                   ($task->isAssignedTo($user->userId) && $task->status !== 'inactive') || 
                   $task->status === 'published';
        
        if (!$canView) {
            abort(403, 'Unauthorized access to task.');
        }

        // Load necessary relationships
        $task->load(['assignments.user', 'assignedUser']);

        $feedbackEntries = collect();
        $feedbackSummary = [
            'average_rating' => null,
            'total' => 0,
            'latest' => null,
        ];

        if ($isCreator) {
            $feedbackEntries = Feedback::where('FK2_taskId', $task->taskId)
                ->with('user')
                ->orderByDesc('feedback_date')
                ->orderByDesc('created_at')
                ->get();

            if ($feedbackEntries->isNotEmpty()) {
                $feedbackSummary['total'] = $feedbackEntries->count();
                $feedbackSummary['average_rating'] = round($feedbackEntries->avg('rating'), 1);
                $feedbackSummary['latest'] = $feedbackEntries->first();
            }
        }

        $requestedTab = $request->get('tab');
        $activeCreatorTab = match (true) {
            $requestedTab === 'overview' => 'overview',
            $requestedTab === 'feedback' => 'feedback',
            $feedbackEntries->isNotEmpty() => 'feedback',
            default => 'overview',
        };

        return view('tasks.show', compact('task', 'isCreator', 'feedbackEntries', 'feedbackSummary', 'activeCreatorTab'));
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit(Task $task)
    {
        // Only allow editing user-uploaded tasks by the creator
        if ($task->FK1_userId !== Auth::id() || $task->task_type !== 'user_uploaded') {
            abort(403, 'You can only edit your own user-uploaded tasks.');
        }

        // Don't allow editing if task is already approved/published/completed
        // Allow editing for pending, rejected, and draft (cancelled) tasks
        if (in_array($task->status, ['approved', 'published', 'completed'])) {
            return redirect()->back()->with('error', "Cannot edit '{$task->title}': This task has been {$task->status} and cannot be modified. Please create a new task proposal if needed.");
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, Task $task)
    {
        // Only allow editing user-uploaded tasks by the creator
        if ($task->FK1_userId !== Auth::id() || $task->task_type !== 'user_uploaded') {
            abort(403, 'You can only edit your own user-uploaded tasks.');
        }

        // Don't allow editing if task is already approved/published/completed
        // Allow editing for pending, rejected, and draft (cancelled) tasks
        if (in_array($task->status, ['approved', 'published', 'completed'])) {
            return redirect()->back()->with('error', "Cannot edit '{$task->title}': This task has been {$task->status} and cannot be modified. Please create a new task proposal if needed.");
        }

        // Normalize time inputs to 24-hour H:i format before validation
        foreach (['start_time', 'end_time'] as $timeField) {
            $value = $request->input($timeField);
            
            // Handle empty values - set to null
            if (empty($value) || trim($value) === '') {
                $request->merge([$timeField => null]);
                continue;
            }
            
            $trimmed = trim($value);
            
            // If already in H:i format (from type="time" input), use as is
            if (preg_match('/^\d{2}:\d{2}$/', $trimmed)) {
                // Already in correct format, keep it
                continue;
            }
            
            // Try to parse 12-hour format with AM/PM
            if (preg_match('/\b(am|pm)\b/i', $trimmed)) {
                try {
                    $normalized = \Carbon\Carbon::createFromFormat('g:i a', strtolower($trimmed))->format('H:i');
                    $request->merge([$timeField => $normalized]);
                } catch (\Exception $e) {
                    // Fall through to validator which will surface a helpful message
                }
            } else {
                // Try to parse as H:i:s and convert to H:i
                try {
                    $parsed = \Carbon\Carbon::createFromFormat('H:i:s', $trimmed);
                    $request->merge([$timeField => $parsed->format('H:i')]);
                } catch (\Exception $e) {
                    // Try H:i format
                    try {
                        $parsed = \Carbon\Carbon::createFromFormat('H:i', $trimmed);
                        $request->merge([$timeField => $parsed->format('H:i')]);
                    } catch (\Exception $e2) {
                        // Fall through to validator
                    }
                }
            }
        }

        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'points_awarded' => 'required|integer|min:1',
            'due_date' => 'nullable|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
        ]);

        // Custom validation for end_time after start_time
        if ($request->start_time && $request->end_time) {
            if (strtotime($request->end_time) <= strtotime($request->start_time)) {
                return redirect()->back()
                    ->withErrors(['end_time' => 'The end time must be after the start time.'])
                    ->withInput();
            }
        }

        // If due_date is today, ensure times are in the future
        if ($request->due_date) {
            $dueDate = \Carbon\Carbon::parse($request->due_date);
            $now = now();
            
            if ($dueDate->isToday() && $request->start_time && $request->end_time) {
                $currentTime = $now->format('H:i');
                
                // Check if start_time is in the past
                if (strtotime($request->start_time) <= strtotime($currentTime)) {
                    return redirect()->back()
                        ->withErrors(['start_time' => 'The start time cannot be in the past. Please select a time later than ' . $currentTime . '.'])
                        ->withInput();
                }
                
                // Check if end_time is in the past
                if (strtotime($request->end_time) <= strtotime($currentTime)) {
                    return redirect()->back()
                        ->withErrors(['end_time' => 'The end time cannot be in the past. Please select a time later than ' . $currentTime . '.'])
                        ->withInput();
                }
            }
        }

        // Determine status based on action
        $isUncompleted = !in_array($task->status, ['completed']) && !in_array($task->status, ['approved', 'published']);
        $isInactive = $task->status === 'inactive';
        $action = $request->input('action', 'update');
        
        // For uncompleted or inactive tasks, allow choosing between pending and published
        if (($isUncompleted || $isInactive) && $action === 'publish') {
            $newStatus = 'published';
            $message = "Task '{$task->title}' has been updated and published successfully.";
        } else {
            $newStatus = 'pending';
            $message = "Task '{$task->title}' has been updated and resubmitted for admin approval. Changes will be reviewed before publishing.";
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'points_awarded' => $request->points_awarded,
            'due_date' => $request->due_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'status' => $newStatus,
        ]);

        return redirect()->route('tasks.my-uploads')->with('status', $message);
    }

    /**
     * Cancel the task proposal (removes from approval queue, allows editing and resubmission)
     */
    public function destroy(Task $task)
    {
        // Only allow canceling user-uploaded tasks by the creator
        if ($task->FK1_userId !== Auth::id() || $task->task_type !== 'user_uploaded') {
            abort(403, 'You can only cancel your own user-uploaded tasks.');
        }

        // Don't allow canceling if task is already approved/published/completed
        if (in_array($task->status, ['approved', 'published', 'completed'])) {
            return redirect()->back()->with('error', "Cannot cancel '{$task->title}': This task is currently {$task->status} and cannot be cancelled. Only pending or rejected tasks can be cancelled.");
        }

        // Set to 'draft' status to remove from approval queue but keep it editable
        $task->update(['status' => 'draft']);

        return redirect()->route('tasks.my-uploads')->with('status', "Task proposal '{$task->title}' has been cancelled. You can now edit it and resubmit when ready.");
    }

    /**
     * Reactivate a deactivated user-uploaded task (creator only)
     */
    public function reactivate(Task $task)
    {
        // Only the creator can reactivate their user-uploaded task
        if ($task->FK1_userId !== Auth::id() || $task->task_type !== 'user_uploaded') {
            abort(403, 'You can only reactivate your own user-uploaded tasks.');
        }
        if ($task->status !== 'inactive') {
            return redirect()->back()->with('error', "Cannot reactivate '{$task->title}': Only tasks that have been deactivated can be reactivated. This task is currently {$task->status}.");
        }

        // Send back to pending for admin review
        $task->update(['status' => 'pending']);
        return redirect()->route('tasks.my-uploads')->with('status', "Task '{$task->title}' has been reactivated and sent for admin approval. It will be published once approved.");
    }

    /**
     * Join an available task
     */
    public function join(Task $task)
    {
        $user = Auth::user();
        
        // Check if task is available for joining
        // Block inactive/deactivated tasks
        if ($task->status === 'inactive') {
            return redirect()->back()->with('error', "Cannot join '{$task->title}': This task has been deactivated by an admin and is no longer available for participation.");
        }
        if ($task->status !== 'published') {
            $statusLabel = ucfirst($task->status);
            return redirect()->back()->with('error', "Cannot join '{$task->title}': This task is currently {$statusLabel} and not open for new participants.");
        }

        // Prevent the creator from joining their own task
        if (!is_null($task->FK1_userId) && $task->FK1_userId === $user->userId) {
            return redirect()->back()->with('error', "Cannot join '{$task->title}': You created this task and cannot participate in it. You can view submissions and approve them instead.");
        }

        // Check if user is already assigned to this task
        if ($task->isAssignedTo($user->userId)) {
            return redirect()->back()->with('error', "You are already assigned to '{$task->title}'. Check your task list to view your progress.");
        }

        // Check if task can accept more users
        if (!$task->canAcceptMoreUsers()) {
            $currentCount = $task->assignments()->count();
            $maxParticipants = $task->max_participants ?? 'unlimited';
            return redirect()->back()->with('error', "Cannot join '{$task->title}': This task has reached its participant limit ({$currentCount} participants).");
        }

        // Create task assignment
        TaskAssignment::create([
            'taskId' => $task->taskId,
            'userId' => $user->userId,
            'status' => 'assigned',
            'progress' => 'accepted',
            'assigned_at' => now(),
        ]);

        $this->notificationService->notify(
            $user,
            'task_assigned',
            "You're now assigned to \"{$task->title}\".",
            [
                'url' => route('tasks.show', $task),
                'description' => 'Track progress and submit proof when you are done.',
            ]
        );

        if ($task->task_type === 'user_uploaded' && $task->FK1_userId) {
            $creator = $task->assignedUser;
            if ($creator && $creator->userId !== $user->userId) {
                $this->notificationService->notify(
                    $creator,
                    'task_participant_joined',
                    "{$user->firstName} {$user->lastName} joined your task \"{$task->title}\".",
                    [
                        'url' => route('tasks.show', $task),
                        'description' => 'Review submissions to award points when the task is done.',
                    ]
                );
            }
        }

        return redirect()->route('tasks.show', $task)->with('status', "Successfully joined '{$task->title}'! You can now track your progress and submit proof when completed.");
    }

    /**
     * Submit task for completion
     */
    public function submit(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Check if task is assigned to current user
        $assignment = TaskAssignment::where('taskId', $task->taskId)
            ->where('userId', $user->userId)
            ->first();

        if (!$assignment) {
            abort(403, 'You can only submit tasks assigned to you.');
        }

        $request->validate([
            'photos' => 'required|array|min:2|max:3',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'completion_notes' => 'nullable|string|max:1000'
        ]);

        $photos = [];
        
        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('task-submissions', 'public');
                $photos[] = $path;
                \Log::info('Photo uploaded to: ' . $path);
            }
        }
        
        \Log::info('Photos array: ' . json_encode($photos));

        // Update assignment status with photos and notes
        $assignment->update([
            'status' => 'submitted',
            'progress' => 'submitted_proof',
            'submitted_at' => now(),
            'photos' => $photos,
            'completion_notes' => $request->completion_notes
        ]);

        if ($task->task_type === 'user_uploaded' && $task->FK1_userId) {
            $creator = $task->assignedUser;
            if ($creator) {
                $this->notificationService->notify(
                    $creator,
                    'task_submission_pending',
                    "{$user->firstName} {$user->lastName} submitted proof for \"{$task->title}\".",
                    [
                        'url' => route('tasks.creator.show', $assignment),
                        'description' => 'Review the submission and approve or reject it.',
                    ]
                );
            }
        }

        // Notify admins for tasks that require administrative review
        if ($task->task_type !== 'user_uploaded') {
            $admins = User::where('role', 'admin')->where('status', 'active')->get(['userId']);
            if ($admins->isNotEmpty()) {
                $this->notificationService->notifyMany(
                    $admins,
                    'task_submission_admin_review',
                    "{$user->firstName} {$user->lastName} submitted proof for \"{$task->title}\".",
                    [
                        'url' => route('admin.task-submissions.show', $assignment),
                        'description' => 'Review the submission and award points if approved.',
                    ]
                );
            }
        }

        return redirect()->route('tasks.show', $task)
            ->with('status', "Your completion proof for '{$task->title}' has been submitted successfully! " . ($task->task_type === 'user_uploaded' ? 'The task creator will review it shortly.' : 'An admin will review it and award points if approved.'));
    }
}
