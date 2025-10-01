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

        // Prevent backtracking progress
        $order = ['accepted','on_the_way','working','done'];
        $currentProgress = $assignment->progress ?? 'accepted';
        $currentIndex = array_search($currentProgress, $order);
        $requestedIndex = array_search($request->progress, $order);
        if ($currentIndex !== false && $requestedIndex !== false && $requestedIndex < $currentIndex) {
            return redirect()->back()->with('error', 'You cannot move progress backward.');
        }

        $assignment->update([
            'progress' => $request->progress,
        ]);

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
        $userTasks = $user->assignedTasks()
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
        $availableTasks = Task::where('status', 'published')->notExpired()
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
        $query = Task::where('task_type', 'user_uploaded')
            ->where('FK1_userId', $user->userId)
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
        $allForStats = Task::where('task_type', 'user_uploaded')
            ->where('FK1_userId', $user->userId)
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

        // Award points to the assignee
        $assignee = $submission->user;
        if ($assignee) {
            $assignee->increment('points', $submission->task->points_awarded);
        }

        return redirect()->route('tasks.creator.submissions')->with('status', 'Submission approved.');
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
            'due_date' => 'required|date|after:today',
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

        $user = Auth::user();

        Task::create([
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

        return redirect()->route('tasks.index')->with('status', 'Task proposal submitted');
    }

    /**
     * Display the specified task
     */
    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Check if user can view this task
        // Users can view:
        // - tasks they created (user-uploaded), regardless of status
        // - tasks they're assigned to
        // - published tasks
        // - admins can view all
        $isCreator = !is_null($task->FK1_userId) && $task->FK1_userId === $user->userId;
        $canView = $user->isAdmin() || 
                   $isCreator ||
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

        // Don't allow editing if task is already approved/published/completed/inactive
        if (in_array($task->status, ['approved', 'published', 'completed', 'inactive'])) {
            return redirect()->back()->with('error', 'Cannot edit approved, published, completed, or inactive tasks.');
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

        // Don't allow editing if task is already approved/published/completed/inactive
        if (in_array($task->status, ['approved', 'published', 'completed', 'inactive'])) {
            return redirect()->back()->with('error', 'Cannot edit approved, published, completed, or inactive tasks.');
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
     * Deactivate the specified task instead of deleting
     */
    public function destroy(Task $task)
    {
        // Only allow deactivation of user-uploaded tasks by the creator
        if ($task->FK1_userId !== Auth::id() || $task->task_type !== 'user_uploaded') {
            abort(403, 'You can only deactivate your own user-uploaded tasks.');
        }

        // Don't allow deactivation if task is already approved/published/completed
        if (in_array($task->status, ['approved', 'published', 'completed'])) {
            return redirect()->back()->with('error', 'Cannot deactivate approved, published, or completed tasks.');
        }

        $task->update(['status' => 'inactive']);

        return redirect()->route('tasks.index')->with('status', 'Task deactivated successfully.');
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

        return redirect()->route('tasks.show', $task)
            ->with('status', 'Task completion submitted for review');
    }
}
