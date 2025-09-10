<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;

class TaskController extends Controller
{
    /**
     * Display a listing of all tasks for admin
     */
    public function index()
    {
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
            'due_date' => 'nullable|date|after:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
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
            'FK1_userId' => null, // Tasks are not assigned initially - users join them
            'status' => $request->publish_immediately ? 'published' : 'approved',
        ];

        // Set published date if publishing immediately
        if ($request->publish_immediately) {
            $taskData['published_date'] = now();
        }

        Task::create($taskData);

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
        return view('admin.tasks.edit', compact('task'));
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'points_awarded' => 'required|integer|min:1',
            'due_date' => 'nullable|date|after:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,published,assigned,submitted,completed',
        ]);

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
            'status' => $request->status,
        ]);

        // Set published date if status is published and not already set
        if ($request->status === 'published' && !$task->published_date) {
            $task->update(['published_date' => now()]);
        }

        return redirect()->route('admin.tasks.index')->with('status', 'Task updated successfully.');
    }

    /**
     * Remove the specified task
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('status', 'Task deleted successfully.');
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

        // Award points to the user
        $assignment->user->increment('points', $task->points_awarded);

        return redirect()->back()->with('status', 'Task assignment completed and points awarded.');
    }

    /**
     * Filter tasks by status
     */
    public function filter(Request $request)
    {
        $status = $request->get('status');
        $taskType = $request->get('task_type');
        
        $query = Task::with(['assignments.user', 'assignedUser']);
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($taskType && $taskType !== 'all') {
            $query->where('task_type', $taskType);
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

        return view('admin.tasks.index', compact('tasks', 'taskStats', 'status', 'taskType'));
    }
}
