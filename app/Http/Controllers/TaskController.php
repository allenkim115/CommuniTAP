<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
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
            return redirect()->back()->with('error', 'Submit proof using the submit action.');
        }

        // Enforce STRICT forward-only, one-step-at-a-time progress
        $order = ['accepted','on_the_way','working','done'];
        $currentProgress = $assignment->progress ?? 'accepted';
        $currentIndex = array_search($currentProgress, $order);
        $requestedIndex = array_search($request->progress, $order);

        if ($currentIndex === false || $requestedIndex === false) {
            return redirect()->back()->with('error', 'Invalid progress transition.');
        }

        // No backtracking
        if ($requestedIndex < $currentIndex) {
            return redirect()->back()->with('error', 'You cannot move progress backward.');
        }

        // No skipping steps; must move exactly to the next step
        if ($requestedIndex !== $currentIndex + 1) {
            return redirect()->back()->with('error', 'Please follow the sequence step-by-step.');
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

        return redirect()->route('tasks.show', $task)->with('status', 'Progress updated.');
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
                $filteredTasks = $userTasks->where('pivot.status', 'completed');
                break;
            case 'all':
                $filteredTasks = $userTasks->merge($availableTasks);
                break;
            default:
                $filteredTasks = $availableTasks;
        }

        // Task statistics for display
        $taskStats = [
            'available' => $availableTasks->count(),
            'assigned' => $userTasks->where('pivot.status', 'assigned')->count(),
            'submitted' => $userTasks->where('pivot.status', 'submitted')->count(),
            'completed' => $userTasks->where('pivot.status', 'completed')->count(),
            'all' => $userTasks->count() + $availableTasks->count()
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
        // Exclude inactive tasks (they should be shown as draft/cancelled instead)
        $query = Task::where('task_type', 'user_uploaded')
            ->where('FK1_userId', $user->userId)
            ->where('status', '!=', 'inactive') // Exclude inactive tasks
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
        // Exclude inactive tasks
        $allForStats = Task::where('task_type', 'user_uploaded')
            ->where('FK1_userId', $user->userId)
            ->where('status', '!=', 'inactive')
            ->get();

        $stats = [
            'pending' => $allForStats->where('status', 'pending')->count(),
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
        $submissions = TaskAssignment::with(['task', 'user'])
            ->where('status', 'submitted')
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

        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        $submission->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $request->notes ?? 'Approved by creator'
        ]);

        // Refresh the task relationship to get updated assignments
        $task->refresh();

        // Check if all participants have completed and auto-complete the task if so
        $taskMarkedCompleted = $task->markAsCompletedIfAllParticipantsDone();

        // Award points to the assignee
        $assignee = $submission->user;
        if ($assignee) {
            $assignee->increment('points', $submission->task->points_awarded);

            $this->notificationService->notify(
                $assignee,
                'task_submission_approved',
                "Your submission for \"{$submission->task->title}\" was approved!",
                [
                    'url' => route('tasks.show', $submission->task),
                    'description' => 'Points have been added to your balance.',
                ]
            );
        }

        $statusMessage = 'Submission approved.';
        if ($taskMarkedCompleted) {
            $statusMessage .= ' Task automatically marked as completed since all participants have finished.';
        }

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

        if ($submission->rejection_count >= 3) {
            return redirect()->back()->with('error', 'Maximum rejection attempts reached.');
        }

        $newRejectionCount = $submission->rejection_count + 1;
        $submission->update([
            'status' => 'assigned',
            'submitted_at' => null,
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

        return redirect()->route('tasks.creator.submissions')->with('status', 'Submission rejected.');
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

        return redirect()->route('tasks.my-uploads')->with('status', 'Task proposal submitted');
    }

    /**
     * Display the specified task
     */
    public function show(Task $task)
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

        return view('tasks.show', compact('task', 'isCreator'));
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
            return redirect()->back()->with('error', 'Cannot edit approved, published, or completed tasks.');
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
            return redirect()->back()->with('error', 'Cannot edit approved, published, or completed tasks.');
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

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'points_awarded' => $request->points_awarded,
            'due_date' => $request->due_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'status' => 'pending', // Reset to pending after edit
        ]);

        return redirect()->route('tasks.my-uploads')->with('status', 'Task updated successfully and resubmitted for approval.');
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
            return redirect()->back()->with('error', 'Cannot cancel approved, published, or completed tasks.');
        }

        // Set to 'draft' status to remove from approval queue but keep it editable
        $task->update(['status' => 'draft']);

        return redirect()->route('tasks.my-uploads')->with('status', 'Task proposal cancelled. You can edit and resubmit it.');
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
            return redirect()->back()->with('error', 'Only deactivated tasks can be reactivated.');
        }

        // Send back to pending for admin review
        $task->update(['status' => 'pending']);
        return redirect()->route('tasks.my-uploads')->with('status', 'Task reactivated and sent for approval.');
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
            return redirect()->back()->with('error', 'This task has been deactivated and is no longer available.');
        }
        if ($task->status !== 'published') {
            return redirect()->back()->with('error', 'This task is not available for joining.');
        }

        // Prevent the creator from joining their own task
        if (!is_null($task->FK1_userId) && $task->FK1_userId === $user->userId) {
            return redirect()->back()->with('error', 'You cannot join a task you created.');
        }

        // Check if user is already assigned to this task
        if ($task->isAssignedTo($user->userId)) {
            return redirect()->back()->with('error', 'You are already assigned to this task.');
        }

        // Check if task can accept more users
        if (!$task->canAcceptMoreUsers()) {
            return redirect()->back()->with('error', 'This task has reached its participant limit.');
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

        return redirect()->route('tasks.show', $task)->with('status', 'Successfully joined the task.');
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
            ->with('status', 'Task completion submitted for review');
    }
}
