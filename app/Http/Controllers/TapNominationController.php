<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\TapNomination;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TapNominationController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    /**
     * Show the nomination form for daily tasks
     */
    public function create(Task $task)
    {
        $user = Auth::user();
        
        // Check if task is a daily task
        if (!$task->isDailyTask()) {
            return redirect()->back()->with('error', 'Tap & Pass nominations are only available for daily tasks.');
        }

        // Check if user has completed ANY daily task TODAY
        $userDailyTaskAssignment = TaskAssignment::whereHas('task', function($query) {
                $query->where('task_type', 'daily');
            })
            ->where('userId', $user->userId)
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->first();
            
        if (!$userDailyTaskAssignment) {
            return redirect()->back()->with('error', 'You must complete a daily task TODAY before nominating someone. Tap & Pass is only available for users who have completed a daily task on the same day.');
        }

        // Get available users for nomination (excluding current user and users who have nominated them)
        $availableUsers = User::where('userId', '!=', $user->userId)
            ->where('status', 'active')
            ->whereDoesntHave('nominationsMade', function($query) use ($user) {
                $query->where('FK3_nomineeId', $user->userId)
                      ->where('status', 'pending');
            })
            ->orderBy('firstName')
            ->get();
            

        // Get available daily tasks for nomination
        $availableDailyTasks = Task::where('task_type', 'daily')
            ->where('status', 'published')
            ->whereDoesntHave('assignments', function($query) {
                $query->where('status', 'completed');
            })
            ->orderBy('title')
            ->get();
            

        return view('tap-nominations.create', compact('task', 'availableUsers', 'availableDailyTasks', 'userDailyTaskAssignment'));
    }

    /**
     * Store a new nomination
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Debug: Log the incoming request
        \Log::info('=== NOMINATION STORE REQUEST ===', [
            'user_id' => $user->userId,
            'user_email' => $user->email,
            'request_all' => $request->all(),
            'timestamp' => now()->toDateTimeString()
        ]);
        
        try {
            $request->validate([
                'task_id' => 'required|exists:tasks,taskId',
                'nominee_id' => 'required|exists:users,userId|different:' . $user->userId,
            ]);
            \Log::info('Validation passed successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors()
            ]);
            throw $e;
        }

        $task = Task::findOrFail($request->task_id);
        
        \Log::info('Task found for nomination', [
            'task_id' => $task->taskId,
            'task_title' => $task->title,
            'task_type' => $task->task_type
        ]);
        
        // Debug: Check all user assignments
        $allUserAssignments = TaskAssignment::where('userId', $user->userId)->get();
        \Log::info('All user assignments', [
            'user_id' => $user->userId,
            'assignments_count' => $allUserAssignments->count(),
            'assignments' => $allUserAssignments->toArray()
        ]);
        
        // Check if task is a daily task
        if (!$task->isDailyTask()) {
            return redirect()->back()->with('error', 'Tap & Pass nominations are only available for daily tasks.');
        }

        // Check if user has completed ANY daily task TODAY
        $userDailyTaskAssignment = TaskAssignment::whereHas('task', function($query) {
                $query->where('task_type', 'daily');
            })
            ->where('userId', $user->userId)
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->first();
            
        \Log::info('User daily task assignment check', [
            'user_id' => $user->userId,
            'user_daily_task_found' => $userDailyTaskAssignment ? true : false,
            'user_daily_task' => $userDailyTaskAssignment ? $userDailyTaskAssignment->toArray() : null
        ]);
            
        if (!$userDailyTaskAssignment) {
            \Log::info('User has not completed any daily task today, redirecting back');
            return redirect()->back()->with('error', 'You must complete a daily task TODAY before nominating someone. Tap & Pass is only available for users who have completed a daily task on the same day.');
        }

        \Log::info('User has completed a daily task today, proceeding with nomination creation');

        // Check if nomination already exists
        $existingNomination = TapNomination::where('FK1_taskId', $request->task_id)
            ->where('FK2_nominatorId', $user->userId)
            ->where('FK3_nomineeId', $request->nominee_id)
            ->where('status', 'pending')
            ->first();

        if ($existingNomination) {
            return redirect()->back()->with('error', 'You have already nominated this user for this task.');
        }

        // Check if the nominee has already nominated this user (prevent reciprocal nominations)
        $reciprocalNomination = TapNomination::where('FK1_taskId', $request->task_id)
            ->where('FK2_nominatorId', $request->nominee_id)
            ->where('FK3_nomineeId', $user->userId)
            ->where('status', 'pending')
            ->first();


        if ($reciprocalNomination) {
            return redirect()->back()->with('error', 'This user has already nominated you for this task. You cannot nominate them back to prevent circular nominations.');
        }

        // Create the nomination
        try {
            \Log::info('Attempting to create nomination', [
                'FK1_taskId' => $request->task_id,
                'FK2_nominatorId' => $user->userId,
                'FK3_nomineeId' => $request->nominee_id,
                'nomination_date' => now(),
                'status' => 'pending'
            ]);
            
            $nomination = TapNomination::create([
                'FK1_taskId' => $request->task_id,
                'FK2_nominatorId' => $user->userId,
                'FK3_nomineeId' => $request->nominee_id,
                'nomination_date' => now(),
                'status' => 'pending'
            ]);

            // Debug: Log successful creation
            \Log::info('Nomination created successfully', [
                'nomination_id' => $nomination->nominationId,
                'task_id' => $request->task_id,
                'nominator_id' => $user->userId,
                'nominee_id' => $request->nominee_id,
                'status' => 'pending'
            ]);

            // Award 1 point to the nominator for using the Tap & Pass system
            $user->increment('points', 1);

            if ($nomination->nominee) {
                $this->notificationService->notify(
                    $nomination->nominee,
                    'tap_nomination_received',
                    "{$user->firstName} nominated you for \"{$task->title}\".",
                    [
                        'url' => route('tap-nominations.index'),
                        'description' => 'Accept the nomination to join the task and earn extra points.',
                    ]
                );
            }

            // Debug: Verify the nomination was created and can be retrieved
            $createdNomination = TapNomination::find($nomination->nominationId);
            $nomineeNominations = TapNomination::where('FK3_nomineeId', $request->nominee_id)->get();
            
            \Log::info('=== NOMINATION CREATION VERIFICATION ===', [
                'created_nomination' => $createdNomination ? $createdNomination->toArray() : 'NOT FOUND',
                'nominee_id' => $request->nominee_id,
                'nominations_for_nominee' => $nomineeNominations->toArray()
            ]);

            return redirect()->route('tap-nominations.index')->with('success', 'Nomination sent successfully! You earned 1 point for using Tap & Pass. The nominated user will be notified.');
        } catch (\Exception $e) {
            \Log::error('Error creating nomination: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'There was an error creating the nomination. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show nominations received by the current user
     */
    public function index()
    {
        $user = Auth::user();
        
        $nominations = $user->nominationsReceived()
            ->with(['task', 'nominator'])
            ->orderBy('nomination_date', 'desc')
            ->paginate(10);

        return view('tap-nominations.index', compact('nominations'));
    }

    /**
     * Show nominations made by the current user
     */
    public function myNominations()
    {
        $user = Auth::user();
        
        $nominations = $user->nominationsMade()
            ->with(['task', 'nominee'])
            ->orderBy('nomination_date', 'desc')
            ->paginate(10);

        return view('tap-nominations.my-nominations', compact('nominations'));
    }

    /**
     * Accept a nomination
     */
    public function accept(TapNomination $nomination)
    {
        $user = Auth::user();
        
        // Check if the nomination is for the current user
        if ($nomination->FK3_nomineeId !== $user->userId) {
            return redirect()->back()->with('error', 'You can only accept nominations sent to you.');
        }

        // Check if nomination is still pending
        if (!$nomination->isPending()) {
            return redirect()->back()->with('error', 'This nomination has already been processed.');
        }

        // Check if the task is still available
        $task = $nomination->task;
        if ($task->status !== 'published') {
            return redirect()->back()->with('error', 'This task is no longer available.');
        }

        // Check if user is already assigned to this task
        if ($task->isAssignedTo($user->userId)) {
            return redirect()->back()->with('error', 'You are already assigned to this task.');
        }

        // Create task assignment
        TaskAssignment::create([
            'taskId' => $task->taskId,
            'userId' => $user->userId,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        // Update nomination status
        $nomination->update(['status' => 'accepted']);

        // Award 1 point to the nominee for accepting the nomination
        $user->increment('points', 1);

        if ($nomination->nominator) {
            $this->notificationService->notify(
                $nomination->nominator,
                'tap_nomination_accepted',
                "{$user->firstName} accepted your nomination for \"{$task->title}\".",
                [
                    'url' => route('tap-nominations.index'),
                    'description' => 'Keep cheering them on!',
                ]
            );
        }

        return redirect()->route('tasks.index')->with('status', 'Nomination accepted! You have been assigned to the task and earned 1 point.');
    }

    /**
     * Decline a nomination
     */
    public function decline(TapNomination $nomination)
    {
        $user = Auth::user();
        
        // Check if the nomination is for the current user
        if ($nomination->FK3_nomineeId !== $user->userId) {
            return redirect()->back()->with('error', 'You can only decline nominations sent to you.');
        }

        // Check if nomination is still pending
        if (!$nomination->isPending()) {
            return redirect()->back()->with('error', 'This nomination has already been processed.');
        }

        // Update nomination status
        $nomination->update(['status' => 'declined']);

        if ($nomination->nominator) {
            $this->notificationService->notify(
                $nomination->nominator,
                'tap_nomination_declined',
                "{$user->firstName} declined your nomination for \"{$nomination->task->title}\".",
                [
                    'url' => route('tap-nominations.index'),
                    'description' => 'You can nominate another teammate for this task.',
                ]
            );
        }

        return redirect()->route('tap-nominations.index')->with('status', 'Nomination declined.');
    }

    /**
     * Show the task chain (tracking feature)
     */
    public function taskChain()
    {
        $taskChain = TapNomination::with(['task', 'nominator', 'nominee'])
            ->accepted()
            ->forDailyTasks()
            ->orderBy('nomination_date', 'desc')
            ->paginate(20);

        return view('tap-nominations.task-chain', compact('taskChain'));
    }

    /**
     * Get user's most recently completed daily task today
     */
    private function getUserCompletedDailyTaskToday($user)
    {
        return TaskAssignment::whereHas('task', function($query) {
                $query->where('task_type', 'daily');
            })
            ->where('userId', $user->userId)
            ->where('status', 'completed')
            ->whereDate('completed_at', today()) // Only today
            ->orderBy('completed_at', 'desc')
            ->first();
    }

    /**
     * Get user's most recently completed daily task (within 7 days)
     */
    private function getUserCompletedDailyTask($user)
    {
        return TaskAssignment::whereHas('task', function($query) {
                $query->where('task_type', 'daily');
            })
            ->where('userId', $user->userId)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(7)) // Within last 7 days
            ->orderBy('completed_at', 'desc')
            ->first();
    }
}
