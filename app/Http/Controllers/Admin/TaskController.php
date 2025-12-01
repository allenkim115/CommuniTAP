<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;
use App\Services\NotificationService;

class TaskController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    /**
     * Display a listing of all tasks for admin
     */
    public function index()
    {
        // Auto-complete expired tasks before listing
        \App\Models\Task::completeExpiredTasks();
        
        // Fix incorrectly marked completed tasks (tasks with no participants or no completed participants)
        \App\Models\Task::fixIncorrectlyCompletedTasks();
        
        // Auto-publish any approved user-uploaded tasks (for tasks approved before the auto-publish feature)
        // and notify the creator + all eligible users, just like the normal approve flow.
        $autoPublishUserUploaded = Task::where('task_type', 'user_uploaded')
            ->where('status', 'approved')
            ->whereNull('published_date')
            ->get();

        foreach ($autoPublishUserUploaded as $task) {
            $task->update([
                'status' => 'published',
                'published_date' => now(),
            ]);

            // Refresh to ensure we have the latest relations
            $task->refresh();

            // Notify the task creator (if present)
            if ($task->FK1_userId && $task->assignedUser) {
                $this->notificationService->notify(
                    $task->assignedUser,
                    'task_proposal_published',
                    "Your task proposal \"{$task->title}\" was approved and is now live!",
                    [
                        'url' => route('tasks.show', $task),
                        'description' => 'Participants can now join. Monitor submissions regularly.',
                    ]
                );
            }

            // Notify all non-admin users (excluding the creator) that a new task is available
            $activeUsers = User::where('role', '!=', 'admin')
                ->when(!is_null($task->FK1_userId), fn ($query) => $query->where('userId', '!=', $task->FK1_userId))
                ->get(['userId', 'firstName', 'lastName']);

            if ($activeUsers->isNotEmpty()) {
                $this->notificationService->notifyMany(
                    $activeUsers,
                    'new_task_available',
                    "New task available: \"{$task->title}\"",
                    [
                        'url' => route('tasks.show', $task),
                        'description' => 'Join now to earn points!',
                    ]
                );
            }
        }
        
        // Auto-publish any approved tasks that already have a published_date (should be published)
        // and notify eligible users if they weren't previously notified.
        $autoPublishRegular = Task::where('status', 'approved')
            ->whereNotNull('published_date')
            ->get();

        foreach ($autoPublishRegular as $task) {
            // If it's already published, skip
            if ($task->status === 'published') {
                continue;
            }

            $task->update([
                'status' => 'published',
            ]);

            // For user-uploaded tasks we already handled above; avoid double notifying
            if ($task->task_type === 'user_uploaded') {
                continue;
            }

            // Notify all non-admin users about this task being available
            $activeUsers = User::where('role', '!=', 'admin')->get(['userId', 'firstName', 'lastName']);

            if ($activeUsers->isNotEmpty()) {
                $this->notificationService->notifyMany(
                    $activeUsers,
                    'task_published',
                    "New task available: \"{$task->title}\"",
                    [
                        'url' => route('tasks.show', $task),
                        'description' => 'Join now while slots are open.',
                    ]
                );
            }
        }

        $tasks = Task::with(['assignments.user', 'assignedUser'])
            ->whereNotIn('status', ['draft']) // Exclude cancelled user proposals, but show inactive tasks
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $taskStats = [
            'total' => Task::whereNotIn('status', ['draft'])->count(),
            'pending' => Task::where('status', 'pending')->count(),
            'approved' => Task::where('status', 'approved')->count(),
            'published' => Task::where('status', 'published')->count(),
            'assigned' => Task::where('status', 'assigned')->count(),
            'submitted' => Task::where('status', 'submitted')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'uncompleted' => Task::where('status', 'uncompleted')->count(),
            'inactive' => Task::where('status', 'inactive')->count(),
        ];

        return view('admin.tasks.index', compact('tasks', 'taskStats'));
    }

    /**
     * Show the form for creating a new task (Daily and One-Time tasks)
     */
    public function create()
    {
        return view('admin.tasks.create');
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:10|max:100',
            'description' => 'required|string|min:10|max:1000',
            'task_type' => 'required|in:daily,one_time',
            'points_awarded' => 'required|integer|min:1',
            'due_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'action' => 'required|in:create,create_and_publish',
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
                    ->withErrors(['start_time' => 'The start time must be in the future when the due date is today.'])
                    ->withInput();
            }
            
            // Check if end_time is in the past
            if (strtotime($request->end_time) <= strtotime($currentTime)) {
                return redirect()->back()
                    ->withErrors(['end_time' => 'The end time must be in the future when the due date is today.'])
                    ->withInput();
            }
        }

        $publishImmediately = $request->action === 'create_and_publish';

        $taskData = [
            'title' => $request->title,
            'description' => $request->description,
            'task_type' => $request->task_type,
            'points_awarded' => $request->points_awarded,
            'creation_date' => now(),
            'due_date' => $request->due_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'max_participants' => $request->max_participants,
            'FK1_userId' => null, // Tasks are not assigned initially - users join them
            'status' => $publishImmediately ? 'published' : 'pending',
        ];

        // Set published date if publishing immediately
        if ($publishImmediately) {
            $taskData['published_date'] = now();
        }

        $task = Task::create($taskData);

        // If published immediately, notify all active users about the new task
        if ($publishImmediately) {
            $activeUsers = User::where('status', 'active')
                ->where('role', '!=', 'admin')
                ->when(!is_null($task->FK1_userId), fn ($query) => $query->where('userId', '!=', $task->FK1_userId))
                ->get(['userId', 'firstName', 'lastName']);
            $this->notificationService->notifyMany(
                $activeUsers,
                'task_published',
                "New task available: \"{$task->title}\"",
                [
                    'url' => route('tasks.show', $task),
                    'description' => 'Join now while slots are open.',
                ]
            );
        }

        return redirect()->route('admin.tasks.index')->with('status', "Task '{$request->title}' has been created successfully" . ($publishImmediately ? ' and published immediately. All active users have been notified.' : '. It is now pending and can be published when ready.'));
    }

    /**
     * Display the specified task
     */
    public function show(Task $task)
    {
        // Prevent viewing cancelled (draft) tasks - they are considered deleted
        if ($task->status === 'draft') {
            return redirect()->route('admin.tasks.index')->with('error', "This task proposal has been cancelled and is no longer available.");
        }
        
        $task->load(['assignedUser', 'assignments.user']);
        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit(Task $task)
    {
        // Admin cannot edit user-uploaded tasks
        if ($task->task_type === 'user_uploaded') {
            return redirect()->route('admin.tasks.show', $task)->with('error', "Cannot edit '{$task->title}': This is a user-uploaded task. Use the Approve, Reject, or Publish actions in the task details page instead.");
        }
        // Do not allow editing published tasks
        if ($task->status === 'published') {
            return redirect()->route('admin.tasks.show', $task)->with('error', "Cannot edit '{$task->title}': Published tasks cannot be modified as they may have active participants. Deactivate the task first if changes are needed.");
        }
        // Do not allow editing completed tasks
        if ($task->status === 'completed') {
            return redirect()->route('admin.tasks.show', $task)->with('error', "Cannot edit '{$task->title}': Completed tasks cannot be modified. Create a new task if needed.");
        }
        return view('admin.tasks.edit', compact('task'));
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, Task $task)
    {
        // Admin cannot update user-uploaded tasks
        if ($task->task_type === 'user_uploaded') {
            return redirect()->route('admin.tasks.show', $task)->with('error', "Cannot edit '{$task->title}': User-uploaded tasks must be managed through the approval workflow, not direct editing.");
        }
        // Do not allow updating published tasks
        if ($task->status === 'published') {
            return redirect()->route('admin.tasks.show', $task)->with('error', "Cannot update '{$task->title}': Published tasks are live and may have active participants. Deactivate the task first to make changes.");
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
            'title' => 'required|string|min:10|max:100',
            'description' => 'required|string|min:10|max:1000',
            'points_awarded' => 'required|integer|min:1',
            'due_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'publish_after_update' => 'boolean',
            // Status is not editable through the form - it's managed through specific actions
        ]);

        // Do not allow updating completed tasks
        if ($task->status === 'completed') {
            return redirect()->route('admin.tasks.show', $task)->with('error', 'Completed tasks cannot be updated.');
        }

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
                    ->withErrors(['start_time' => 'The start time must be in the future when the due date is today.'])
                    ->withInput();
            }
            
            // Check if end_time is in the past
            if (strtotime($request->end_time) <= strtotime($currentTime)) {
                return redirect()->back()
                    ->withErrors(['end_time' => 'The end time must be in the future when the due date is today.'])
                    ->withInput();
            }
        }

        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'points_awarded' => $request->points_awarded,
            'due_date' => $request->due_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'max_participants' => $request->max_participants,
        ];

        // Determine status based on action for uncompleted or inactive tasks
        $isUncompleted = !in_array($task->status, ['completed']) && !in_array($task->status, ['approved', 'published']);
        $isInactive = $task->status === 'inactive';
        $action = $request->input('action', 'update');
        
        // If task is inactive and publish_after_update is set, publish it (legacy support)
        $shouldPublish = $isInactive && $request->boolean('publish_after_update');
        
        // For uncompleted or inactive tasks, allow choosing between pending and published
        if (($isUncompleted || $isInactive) && $action === 'publish') {
            $updateData['status'] = 'published';
            $updateData['published_date'] = now();
            $updateData['deactivated_at'] = null; // Clear deactivated_at on publishing
            $shouldPublish = true;
        } elseif (($isUncompleted || $isInactive) && $action === 'update') {
            $updateData['status'] = 'pending';
        } elseif ($shouldPublish) {
            // Legacy support for inactive tasks
            $updateData['status'] = 'published';
            $updateData['published_date'] = now();
            $updateData['deactivated_at'] = null; // Clear deactivated_at on publishing
        }

        $task->update($updateData);
        
        // Refresh the model to ensure status is updated
        $task->refresh();

        $message = "Task '{$task->title}' has been updated successfully. All changes have been saved.";
        
        if (($isUncompleted || $isInactive) && $action === 'update') {
            $message = "Task '{$task->title}' has been updated and set to pending status.";
        } elseif ($shouldPublish) {
            // Notify task creator if it's a user-uploaded task
            if ($task->task_type === 'user_uploaded' && $task->FK1_userId && $task->assignedUser) {
                $this->notificationService->notify(
                    $task->assignedUser,
                    'task_proposal_reactivated',
                    "Your task \"{$task->title}\" was updated and is now live!",
                    [
                        'url' => route('tasks.show', $task),
                        'description' => 'The task is available for users to join again.',
                    ]
                );
            }

            // Notify all active users that the task is available
            $activeUsers = User::where('status', 'active')
                ->where('role', '!=', 'admin')
                ->when(!is_null($task->FK1_userId), fn ($query) => $query->where('userId', '!=', $task->FK1_userId))
                ->get(['userId', 'firstName', 'lastName']);
            $this->notificationService->notifyMany(
                $activeUsers,
                'task_reactivated',
                "Task \"{$task->title}\" is available again!",
                [
                    'url' => route('tasks.show', $task),
                    'description' => 'Join now while slots are open.',
                ]
            );

            $message .= " The task has been published and all active users have been notified.";
        } elseif ($task->status === 'inactive') {
            $message .= " You can now reactivate this task.";
        }

        return redirect()->route('admin.tasks.index')->with('status', $message);
    }

    /**
     * Deactivate the specified task instead of deleting
     */
    public function destroy(Task $task)
    {
        // Admin cannot deactivate user-uploaded tasks from here; manage via approval flow
        if ($task->task_type === 'user_uploaded') {
            return redirect()->route('admin.tasks.show', $task)->with('error', "Cannot deactivate '{$task->title}': User-uploaded tasks should be managed through the Reject action in the approval workflow.");
        }
        // Do not allow deactivation of completed tasks
        if ($task->status === 'completed') {
            return redirect()->route('admin.tasks.show', $task)->with('error', "Cannot deactivate '{$task->title}': Completed tasks are final and cannot be deactivated.");
        }
        // Only allow deactivation of published/approved tasks (live tasks visible to users)
        if (!in_array($task->status, ['published', 'approved'])) {
            return redirect()->route('admin.tasks.show', $task)->with('error', "Cannot deactivate '{$task->title}': Only published or approved tasks can be deactivated. Tasks in '{$task->status}' status should be managed through other actions (approve, reject, publish, etc.).");
        }
        // Do not allow deactivation of already inactive tasks
        if ($task->status === 'inactive') {
            return redirect()->route('admin.tasks.show', $task)->with('error', "Cannot deactivate '{$task->title}': This task is already inactive.");
        }

        $task->update([
            'status' => 'inactive',
            'deactivated_at' => now(),
        ]);
        return redirect()->route('admin.tasks.index')->with('status', "Task '{$task->title}' has been deactivated successfully. It is no longer visible to users and cannot be joined. Please edit the task before reactivating it.");
    }

    /**
     * Reactivate a deactivated task (admin)
     */
    public function reactivate(Task $task)
    {
        if ($task->status !== 'inactive') {
            return redirect()->back()->with('error', "Cannot reactivate '{$task->title}': Only tasks that have been deactivated can be reactivated. This task is currently {$task->status}.");
        }
        
        // Check if task has been edited since deactivation
        // For tasks without deactivated_at (old inactive tasks), set it to updated_at to enforce editing requirement
        if (!$task->deactivated_at) {
            $task->update(['deactivated_at' => $task->updated_at]);
            $task->refresh();
        }
        
        // Check if task has been edited since deactivation
        if ($task->updated_at <= $task->deactivated_at) {
            return redirect()->back()->with('error', "Cannot reactivate '{$task->title}': This task must be edited before it can be reactivated. Please edit the task first to make any necessary changes.");
        }
        
        // Automatically publish reactivated tasks
        $task->update([
            'status' => 'published',
            'published_date' => now(),
            'deactivated_at' => null, // Clear deactivated_at on reactivation
        ]);
        
        // Refresh the model to ensure status is updated
        $task->refresh();

        // Notify task creator if it's a user-uploaded task
        if ($task->task_type === 'user_uploaded' && $task->FK1_userId && $task->assignedUser) {
            $this->notificationService->notify(
                $task->assignedUser,
                'task_proposal_reactivated',
                "Your task \"{$task->title}\" was reactivated and is now live!",
                [
                    'url' => route('tasks.show', $task),
                    'description' => 'The task is available for users to join again.',
                ]
            );
        }

        // Notify all active users that a reactivated task is available
        $activeUsers = User::where('status', 'active')
            ->where('role', '!=', 'admin')
            ->when(!is_null($task->FK1_userId), fn ($query) => $query->where('userId', '!=', $task->FK1_userId))
            ->get(['userId', 'firstName', 'lastName']);
        $this->notificationService->notifyMany(
            $activeUsers,
            'task_reactivated',
            "Task \"{$task->title}\" is available again!",
            [
                'url' => route('tasks.show', $task),
                'description' => 'Join now while slots are open.',
            ]
        );

        return redirect()->back()->with('status', "Task '{$task->title}' has been reactivated and published successfully! All active users have been notified that this task is available again.");
    }

    /**
     * Approve a pending task
     * For user-uploaded tasks (proposals), automatically publish them
     */
    public function approve(Task $task)
    {
        // For user-uploaded proposals, approve and publish automatically
        if ($task->task_type === 'user_uploaded') {
            $task->update([
                'status' => 'published',
                'approval_date' => now(),
                'published_date' => now(),
            ]);
            
            // Refresh the model to ensure status is updated
            $task->refresh();

            // Notify the task creator
            if ($task->FK1_userId && $task->assignedUser) {
                $this->notificationService->notify(
                    $task->assignedUser,
                    'task_proposal_published',
                    "Your task proposal \"{$task->title}\" was approved and is now live!",
                    [
                        'url' => route('tasks.show', $task),
                        'description' => 'Participants can now join. Monitor submissions regularly.',
                    ]
                );
            }

            // Notify all active users that a new task is available to join
            $activeUsers = User::where('status', 'active')
                ->where('role', '!=', 'admin')
                ->when(!is_null($task->FK1_userId), fn ($query) => $query->where('userId', '!=', $task->FK1_userId))
                ->get(['userId', 'firstName', 'lastName']);
            $this->notificationService->notifyMany(
                $activeUsers,
                'new_task_available',
                "New task available: \"{$task->title}\"",
                [
                    'url' => route('tasks.show', $task),
                    'description' => 'Join now to earn points!',
                ]
            );

            return redirect()->back()->with('status', "Task proposal '{$task->title}' has been approved and published successfully! The creator and all active users have been notified.");
        } else {
            // For admin-created tasks, just approve (publishing is a separate step)
            $task->update([
                'status' => 'approved',
                'approval_date' => now(),
            ]);

            return redirect()->back()->with('status', "Task '{$task->title}' has been approved successfully. You can now publish it when ready to make it available to users.");
        }
    }

    /**
     * Reject a pending task
     */
    public function reject(Task $task)
    {
        $task->update([
            'status' => 'rejected',
        ]);

        if ($task->task_type === 'user_uploaded' && $task->FK1_userId && $task->assignedUser) {
            $this->notificationService->notify(
                $task->assignedUser,
                'task_proposal_rejected',
                "Your task proposal \"{$task->title}\" was rejected.",
                [
                    'url' => route('tasks.my-uploads'),
                    'description' => 'Review admin feedback and resubmit if needed.',
                ]
            );
        }

        return redirect()->back()->with('status', "Task proposal '{$task->title}' has been rejected. The creator has been notified and can resubmit with changes if needed.");
    }

    /**
     * Publish a task (can be pending for admin-created tasks or approved for user-uploaded tasks)
     */
    public function publish(Task $task)
    {
        $task->update([
            'status' => 'published',
            'published_date' => now(),
        ]);

        if ($task->task_type === 'user_uploaded' && $task->FK1_userId && $task->assignedUser) {
            $this->notificationService->notify(
                $task->assignedUser,
                'task_proposal_published',
                "Your task \"{$task->title}\" is live!",
                [
                    'url' => route('tasks.show', $task),
                    'description' => 'Participants can now join. Monitor submissions regularly.',
                ]
            );
        }

        // Notify all active users that a new task is available to join
        $activeUsers = User::where('status', 'active')
            ->where('role', '!=', 'admin')
            ->when(!is_null($task->FK1_userId), fn ($query) => $query->where('userId', '!=', $task->FK1_userId))
            ->get(['userId', 'firstName', 'lastName']);
        $this->notificationService->notifyMany(
            $activeUsers,
            'task_published',
            "New task available: \"{$task->title}\"",
            [
                'url' => route('tasks.show', $task),
                'description' => 'Join now while slots are open.',
            ]
        );

        return redirect()->back()->with('status', "Task '{$task->title}' has been published successfully! All active users have been notified and can now join this task.");
    }

    /**
     * Complete a submitted task assignment
     */
    public function complete(Task $task)
    {
        $assignmentId = request('assignment_id');
        
        if (!$assignmentId) {
            return redirect()->back()->with('error', 'Cannot complete assignment: Assignment ID is missing. Please try again from the task submissions page.');
        }

        $assignment = TaskAssignment::where('assignmentId', $assignmentId)
            ->where('taskId', $task->taskId)
            ->where('status', 'submitted')
            ->first();

        if (!$assignment) {
            return redirect()->back()->with('error', 'Cannot complete assignment: The submission was not found or has not been submitted yet. Please verify the assignment status.');
        }

        // Update assignment status to completed
        $assignment->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Award points to the user (respecting points cap)
        $pointsResult = $assignment->user->addPoints($task->points_awarded);
        
        $pointsMessage = $pointsResult['added'] > 0 
            ? "{$pointsResult['added']} points have been added to your balance."
            : 'You have reached the points cap (500 points). No points were added.';
        
        if ($pointsResult['capped'] && $pointsResult['added'] > 0) {
            $pointsMessage .= " You reached the maximum points limit, so only {$pointsResult['added']} of {$task->points_awarded} points were added.";
        }

        if ($assignment->user) {
            $this->notificationService->notify(
                $assignment->user,
                'task_assignment_completed',
                "Great job! \"{$task->title}\" was verified by an admin.",
                [
                    'url' => route('tasks.show', $task),
                    'description' => $pointsMessage,
                ]
            );
        }

        $statusMessage = $pointsResult['added'] > 0
            ? "Task assignment completed. {$pointsResult['added']} points awarded."
            : "Task assignment completed. User has reached the points cap (500 points), so no points were added.";

        return redirect()->back()->with('status', $statusMessage);
    }

    /**
     * Filter tasks by status
     */
    public function filter(Request $request)
    {
        // Auto-complete expired tasks before filtering
        \App\Models\Task::completeExpiredTasks();
        
        // Fix incorrectly marked completed tasks (tasks with no participants or no completed participants)
        \App\Models\Task::fixIncorrectlyCompletedTasks();

        $status = $request->get('status');
        $taskType = $request->get('task_type');
        $assignmentProgress = $request->get('assignment_progress');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $search = $request->get('search');
        
        $query = Task::with(['assignments.user', 'assignedUser']);
        
        // Exclude cancelled user proposals (draft) - they are considered deleted
        // Inactive tasks should still be visible
        $query->whereNotIn('status', ['draft']);
        
        // Search by task title
        if ($search) {
            $query->where('title', 'LIKE', '%' . $search . '%');
        }
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($taskType && $taskType !== 'all') {
            $query->where('task_type', $taskType);
        }
        if ($assignmentProgress && $assignmentProgress !== 'all') {
            $query->whereHas('assignments', function($q) use ($assignmentProgress) {
                $q->where('progress', $assignmentProgress);
            });
        }
        
        // Date filtering based on creation_date
        if ($dateFrom) {
            $query->whereDate('creation_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('creation_date', '<=', $dateTo);
        }
        
        $tasks = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
        
        $taskStats = [
            'total' => Task::whereNotIn('status', ['draft'])->count(),
            'pending' => Task::where('status', 'pending')->count(),
            'approved' => Task::where('status', 'approved')->count(),
            'published' => Task::where('status', 'published')->count(),
            'assigned' => Task::where('status', 'assigned')->count(),
            'submitted' => Task::where('status', 'submitted')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'uncompleted' => Task::where('status', 'uncompleted')->count(),
            'inactive' => Task::where('status', 'inactive')->count(),
        ];

        return view('admin.tasks.index', compact('tasks', 'taskStats', 'status', 'taskType', 'assignmentProgress', 'dateFrom', 'dateTo', 'search'));
    }
}
