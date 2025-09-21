<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks for the authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'available'); // Default to available tasks
        
        // Get tasks assigned to the user through task_assignments table with pivot data
        $userTasks = $user->assignedTasks()
            ->withPivot('status', 'assigned_at', 'submitted_at', 'completed_at', 'photos', 'completion_notes', 'rejection_count', 'rejection_reason')
            ->with('assignments.user')
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
            
        // Get available tasks that user can join (published tasks)
        $availableTasks = Task::where('status', 'published')
            ->whereDoesntHave('assignments', function($query) use ($user) {
                $query->where('userId', $user->userId);
            })
            ->with('assignments.user')
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
            'task_type' => 'required|in:daily,one_time,user_uploaded',
            'points_awarded' => 'required|integer|min:1',
            'due_date' => 'nullable|date|after:today',
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

        $user = Auth::user();
        
        // Only allow user-uploaded tasks for regular users
        if ($request->task_type !== 'user_uploaded') {
            return redirect()->back()->with('error', 'You can only create user-uploaded tasks.');
        }

        Task::create([
            'FK1_userId' => $user->userId,
            'title' => $request->title,
            'description' => $request->description,
            'task_type' => $request->task_type,
            'points_awarded' => $request->points_awarded,
            'status' => 'pending', // User-uploaded tasks need admin approval
            'creation_date' => now(),
            'due_date' => $request->due_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
        ]);

        return redirect()->route('tasks.index')->with('status', 'Task proposal submitted');
    }

    /**
     * Display the specified task
     */
    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Check if user can view this task
        // Users can view tasks they're assigned to, or published tasks, or if they're admin
        $canView = $user->isAdmin() || 
                   $task->isAssignedTo($user->userId) || 
                   $task->status === 'published';
        
        if (!$canView) {
            abort(403, 'Unauthorized access to task.');
        }

        // Load necessary relationships
        $task->load(['assignments.user', 'assignedUser']);

        return view('tasks.show', compact('task'));
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

        // Don't allow editing if task is already approved
        if ($task->status === 'approved' || $task->status === 'published') {
            return redirect()->back()->with('error', 'Cannot edit approved or published tasks.');
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

        // Don't allow editing if task is already approved
        if ($task->status === 'approved' || $task->status === 'published') {
            return redirect()->back()->with('error', 'Cannot edit approved or published tasks.');
        }

        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'points_awarded' => 'required|integer|min:1',
            'due_date' => 'nullable|date|after:today',
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

        return redirect()->route('tasks.index')->with('status', 'Task updated successfully and resubmitted for approval.');
    }

    /**
     * Remove the specified task
     */
    public function destroy(Task $task)
    {
        // Only allow deleting user-uploaded tasks by the creator
        if ($task->FK1_userId !== Auth::id() || $task->task_type !== 'user_uploaded') {
            abort(403, 'You can only delete your own user-uploaded tasks.');
        }

        // Don't allow deleting if task is already approved
        if ($task->status === 'approved' || $task->status === 'published') {
            return redirect()->back()->with('error', 'Cannot delete approved or published tasks.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('status', 'Task deleted successfully.');
    }

    /**
     * Join an available task
     */
    public function join(Task $task)
    {
        $user = Auth::user();
        
        // Check if task is available for joining
        if ($task->status !== 'published') {
            return redirect()->back()->with('error', 'This task is not available for joining.');
        }

        // Check if user is already assigned to this task
        if ($task->isAssignedTo($user->userId)) {
            return redirect()->back()->with('error', 'You are already assigned to this task.');
        }

        // Check if task can accept more users
        if (!$task->canAcceptMoreUsers()) {
            return redirect()->back()->with('error', 'This task cannot accept more users.');
        }

        // Create task assignment
        TaskAssignment::create([
            'taskId' => $task->taskId,
            'userId' => $user->userId,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        return redirect()->route('tasks.index')->with('status', 'Successfully joined the task.');
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
            'submitted_at' => now(),
            'photos' => $photos,
            'completion_notes' => $request->completion_notes
        ]);

        return redirect()->route('tasks.show', $task)
            ->with('status', 'Task completion submitted for review');
    }
}
