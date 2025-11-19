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

        $tasks = Task::with(['assignments.user', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $taskStats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'approved' => Task::where('status', 'approved')->count(),
            'published' => Task::where('status', 'published')->count(),
            'assigned' => Task::where('status', 'assigned')->count(),
            'submitted' => Task::where('status', 'submitted')->count(),
            'completed' => Task::where('status', 'completed')->count(),
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
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'task_type' => 'required|in:daily,one_time',
            'points_awarded' => 'required|integer|min:1',
            'due_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'publish_immediately' => 'boolean',
        ]);

        // Custom validation for end_time after start_time
        if ($request->start_time && $request->end_time) {
            if (strtotime($request->end_time) <= strtotime($request->start_time)) {
                return redirect()->back()
                    ->withErrors(['end_time' => 'The end time must be after the start time.'])
                    ->withInput();
            }
        }

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
            'status' => $request->publish_immediately ? 'published' : 'approved',
        ];

        // Set published date if publishing immediately
        if ($request->publish_immediately) {
            $taskData['published_date'] = now();
        }

        $task = Task::create($taskData);

        // If published immediately, notify all active users about the new task
        if ($request->publish_immediately) {
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

        return redirect()->route('admin.tasks.index')->with('status', 'Task created successfully');
    }

    /**
     * Display the specified task
     */
    public function show(Task $task)
    {
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
            return redirect()->route('admin.tasks.show', $task)->with('error', 'Admins cannot edit user-uploaded tasks. Use Approve/Reject/Publish actions instead.');
        }
        // Do not allow editing published tasks
        if ($task->status === 'published') {
            return redirect()->route('admin.tasks.show', $task)->with('error', 'Published tasks cannot be edited.');
        }
        // Do not allow editing completed tasks
        if ($task->status === 'completed') {
            return redirect()->route('admin.tasks.show', $task)->with('error', 'Completed tasks cannot be edited.');
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
            return redirect()->route('admin.tasks.show', $task)->with('error', 'Admins cannot edit user-uploaded tasks.');
        }
        // Do not allow updating published tasks
        if ($task->status === 'published') {
            return redirect()->route('admin.tasks.show', $task)->with('error', 'Published tasks cannot be updated.');
        }
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'points_awarded' => 'required|integer|min:1',
            'due_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:pending,approved,published,assigned,submitted,completed,inactive',
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

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'points_awarded' => $request->points_awarded,
            'due_date' => $request->due_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'max_participants' => $request->max_participants,
            'status' => $request->status,
        ]);

        // Set published date if status is published and not already set
        if ($request->status === 'published' && !$task->published_date) {
            $task->update(['published_date' => now()]);
        }

        return redirect()->route('admin.tasks.index')->with('status', 'Task updated successfully.');
    }

    /**
     * Deactivate the specified task instead of deleting
     */
    public function destroy(Task $task)
    {
        // Admin cannot deactivate user-uploaded tasks from here; manage via approval flow
        if ($task->task_type === 'user_uploaded') {
            return redirect()->route('admin.tasks.show', $task)->with('error', 'Admins cannot deactivate user-uploaded tasks here.');
        }
        // Do not allow deactivation of completed tasks
        if ($task->status === 'completed') {
            return redirect()->route('admin.tasks.show', $task)->with('error', 'Completed tasks cannot be deactivated.');
        }

        $task->update(['status' => 'inactive']);
        return redirect()->route('admin.tasks.index')->with('status', 'Task deactivated successfully.');
    }

    /**
     * Reactivate a deactivated task (admin)
     */
    public function reactivate(Task $task)
    {
        if ($task->status !== 'inactive') {
            return redirect()->back()->with('error', 'Only deactivated tasks can be reactivated.');
        }
        // For admin-managed tasks, bring back to approved state; publishing is a separate step
        $task->update(['status' => 'approved']);

        if ($task->task_type === 'user_uploaded' && $task->FK1_userId && $task->assignedUser) {
            $this->notificationService->notify(
                $task->assignedUser,
                'task_proposal_reactivated',
                "Your task \"{$task->title}\" was reactivated and set to approved.",
                [
                    'url' => route('tasks.show', $task),
                    'description' => 'You can now proceed to request publishing again.',
                ]
            );
        }

        return redirect()->back()->with('status', 'Task reactivated to approved status.');
    }

    /**
     * Approve a pending task
     */
    public function approve(Task $task)
    {
        $task->update([
            'status' => 'approved',
            'approval_date' => now(),
        ]);

        if ($task->task_type === 'user_uploaded' && $task->FK1_userId && $task->assignedUser) {
            $this->notificationService->notify(
                $task->assignedUser,
                'task_proposal_approved',
                "Your task proposal \"{$task->title}\" was approved.",
                [
                    'url' => route('tasks.show', $task),
                    'description' => 'The task is ready to be reviewed for publishing.',
                ]
            );
        }

        return redirect()->back()->with('status', 'Task approved successfully.');
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

        return redirect()->back()->with('status', 'Task rejected successfully.');
    }

    /**
     * Publish an approved task
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

        return redirect()->back()->with('status', 'Task published successfully.');
    }

    /**
     * Complete a submitted task assignment
     */
    public function complete(Task $task)
    {
        $assignmentId = request('assignment_id');
        
        if (!$assignmentId) {
            return redirect()->back()->with('error', 'Assignment ID is required.');
        }

        $assignment = TaskAssignment::where('assignmentId', $assignmentId)
            ->where('taskId', $task->taskId)
            ->where('status', 'submitted')
            ->first();

        if (!$assignment) {
            return redirect()->back()->with('error', 'Assignment not found or not submitted.');
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

        $status = $request->get('status');
        $taskType = $request->get('task_type');
        $assignmentProgress = $request->get('assignment_progress');
        
        $query = Task::with(['assignments.user', 'assignedUser']);
        
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
        
        $tasks = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
        
        $taskStats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'approved' => Task::where('status', 'approved')->count(),
            'published' => Task::where('status', 'published')->count(),
            'assigned' => Task::where('status', 'assigned')->count(),
            'submitted' => Task::where('status', 'submitted')->count(),
            'completed' => Task::where('status', 'completed')->count(),
        ];

        return view('admin.tasks.index', compact('tasks', 'taskStats', 'status', 'taskType', 'assignmentProgress'));
    }
}
