<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\TaskSubmissionController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\TapNominationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'user'])
    ->name('dashboard');

Route::get('/progress', [App\Http\Controllers\DashboardController::class, 'progress'])
    ->middleware(['auth', 'verified', 'user'])
    ->name('progress');

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Task management routes for regular users
    // Place custom routes BEFORE resource to avoid being captured by /tasks/{task}
    Route::get('/tasks/my-uploads', [TaskController::class, 'myUploads'])->name('tasks.my-uploads');
    // Creator review of submissions for their user-uploaded tasks
    Route::get('/tasks/creator/submissions', [TaskController::class, 'creatorSubmissions'])->name('tasks.creator.submissions');
    Route::get('/tasks/submissions/{submission}', [TaskController::class, 'creatorShow'])->name('tasks.creator.show');
    Route::post('/tasks/submissions/{submission}/approve', [TaskController::class, 'creatorApprove'])->name('tasks.creator.approve');
    Route::post('/tasks/submissions/{submission}/reject', [TaskController::class, 'creatorReject'])->name('tasks.creator.reject');
    Route::post('/tasks/{task}/join', [TaskController::class, 'join'])->name('tasks.join');
    Route::post('/tasks/{task}/submit', [TaskController::class, 'submit'])->name('tasks.submit');
    Route::patch('/tasks/{task}/progress', [TaskController::class, 'updateProgress'])->name('tasks.progress');
    Route::resource('tasks', TaskController::class);
    
    // Feedback routes for regular users
    Route::get('/feedback/{task}/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback/{task}/store', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/{feedback}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::patch('/feedback/{feedback}/update', [FeedbackController::class, 'update'])->name('feedback.update');
    Route::get('/feedback/{task}/show', [FeedbackController::class, 'show'])->name('feedback.show');
    
    // Tap & Pass nomination routes
    Route::get('/tap-nominations/create/{task}', [TapNominationController::class, 'create'])->name('tap-nominations.create');
    Route::post('/tap-nominations', [TapNominationController::class, 'store'])->name('tap-nominations.store');
    Route::get('/tap-nominations', [TapNominationController::class, 'index'])->name('tap-nominations.index');
    Route::post('/tap-nominations/{nomination}/accept', [TapNominationController::class, 'accept'])->name('tap-nominations.accept');
    Route::post('/tap-nominations/{nomination}/decline', [TapNominationController::class, 'decline'])->name('tap-nominations.decline');
    
    // Debug route for testing nominations
    Route::get('/debug-nominations', function() {
        $user = Auth::user();
        $allNominations = \App\Models\TapNomination::all();
        $userNominations = $user->nominationsReceived()->get();
        
        // Check database structure
        $tableStructure = \Illuminate\Support\Facades\DB::select("DESCRIBE tap_nominations");
        
        return response()->json([
            'user_id' => $user->userId,
            'total_nominations' => $allNominations->count(),
            'user_nominations' => $userNominations->count(),
            'all_nominations' => $allNominations,
            'user_nominations_data' => $userNominations,
            'table_structure' => $tableStructure,
            'nominations_for_user_5' => \App\Models\TapNomination::where('FK3_nomineeId', 5)->get(),
            'nominations_from_user_6' => \App\Models\TapNomination::where('FK2_nominatorId', 6)->get(),
            'manual_check_current_user' => \App\Models\TapNomination::where('FK3_nomineeId', $user->userId)->get()
        ]);
    })->name('debug.nominations');
    
    // Test route to manually create a nomination
    Route::get('/test-create-nomination', function() {
        $user = Auth::user();
        $task = \App\Models\Task::where('task_type', 'daily')->first();
        $nominee = \App\Models\User::where('userId', '!=', $user->userId)->first();
        
        if (!$task || !$nominee) {
            return response()->json(['error' => 'No task or nominee found']);
        }
        
        try {
            $nomination = \App\Models\TapNomination::create([
                'FK1_taskId' => $task->taskId,
                'FK2_nominatorId' => $user->userId,
                'FK3_nomineeId' => $nominee->userId,
                'nomination_date' => now(),
                'status' => 'pending'
            ]);
            
            return response()->json([
                'success' => true,
                'nomination_id' => $nomination->nominationId,
                'task_id' => $task->taskId,
                'nominator_id' => $user->userId,
                'nominee_id' => $nominee->userId,
                'nominee_nominations_count' => \App\Models\TapNomination::where('FK3_nomineeId', $nominee->userId)->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    })->name('test.create.nomination');
    
    // Simple test route to check foreign key issues
    Route::get('/test-foreign-keys', function() {
        $user = Auth::user();
        
        // Check what the actual foreign key values are in the database
        $nominations = \App\Models\TapNomination::all();
        $userNominations = [];
        
        foreach ($nominations as $nomination) {
            $userNominations[] = [
                'nomination_id' => $nomination->nominationId,
                'FK2_nominatorId' => $nomination->FK2_nominatorId,
                'FK3_nomineeId' => $nomination->FK3_nomineeId,
                'current_user_id' => $user->userId,
                'matches_nominator' => $nomination->FK2_nominatorId == $user->userId,
                'matches_nominee' => $nomination->FK3_nomineeId == $user->userId
            ];
        }
        
        return response()->json([
            'current_user_id' => $user->userId,
            'total_nominations' => $nominations->count(),
            'nominations' => $userNominations,
            'user_primary_key' => $user->getKeyName(),
            'user_key_value' => $user->getKey()
        ]);
    })->name('test.foreign.keys');
    
    // Test route to create a nomination for current user
    Route::get('/test-create-nomination-for-me', function() {
        $user = Auth::user();
        $task = \App\Models\Task::where('task_type', 'daily')->first();
        
        if (!$task) {
            return response()->json(['error' => 'No daily task found']);
        }
        
        try {
            // Create a self-nomination for testing
            $nomination = \App\Models\TapNomination::create([
                'FK1_taskId' => $task->taskId,
                'FK2_nominatorId' => $user->userId,
                'FK3_nomineeId' => $user->userId, // Self-nomination
                'nomination_date' => now(),
                'status' => 'pending'
            ]);
            
            // Check if it shows up in relationships
            $nominationsReceived = $user->nominationsReceived()->count();
            $nominationsMade = $user->nominationsMade()->count();
            
            return response()->json([
                'success' => true,
                'nomination_id' => $nomination->nominationId,
                'task_id' => $task->taskId,
                'nominator_id' => $user->userId,
                'nominee_id' => $user->userId,
                'nominations_received_count' => $nominationsReceived,
                'nominations_made_count' => $nominationsMade,
                'message' => 'Test nomination created! Check /tap-nominations to see it.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    })->name('test.create.nomination.for.me');
    
    // Debug route to check task and user assignment details
    Route::get('/debug-task-assignments', function() {
        $user = Auth::user();
        
        // Get task 10 details
        $task10 = \App\Models\Task::find(10);
        
        // Get user's completed tasks
        $userAssignments = \App\Models\TaskAssignment::where('userId', $user->userId)
            ->where('status', 'completed')
            ->with('task')
            ->get();
        
        // Check if user has completed task 10
        $task10Assignment = \App\Models\TaskAssignment::where('userId', $user->userId)
            ->where('taskId', 10)
            ->where('status', 'completed')
            ->first();
        
        return response()->json([
            'current_user_id' => $user->userId,
            'task_10_details' => $task10 ? $task10->toArray() : null,
            'user_completed_tasks' => $userAssignments->toArray(),
            'task_10_assignment' => $task10Assignment ? $task10Assignment->toArray() : null,
            'has_completed_task_10' => $task10Assignment ? true : false
        ]);
    })->name('debug.task.assignments');
    
    // Debug route to check admin status and navigation
    Route::get('/debug-admin-status', function() {
        $user = Auth::user();
        
        return response()->json([
            'user_id' => $user->userId,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'is_admin_method_result' => method_exists($user, 'isAdmin') ? $user->isAdmin() : 'method not found',
            'current_url' => request()->url(),
            'is_admin_page' => request()->is('admin*'),
            'navigation_should_show' => $user->isAdmin() ? 'YES' : 'NO'
        ]);
    })->name('debug.admin.status');
    
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::patch('/users/{user}/reactivate', [AdminUserController::class, 'reactivate'])->name('users.reactivate');
    
    // Admin task management routes
    Route::get('/tasks/filter', [AdminTaskController::class, 'filter'])->name('tasks.filter');
    Route::resource('tasks', AdminTaskController::class);
    Route::post('/tasks/{task}/approve', [AdminTaskController::class, 'approve'])->name('tasks.approve');
    Route::post('/tasks/{task}/reject', [AdminTaskController::class, 'reject'])->name('tasks.reject');
    Route::post('/tasks/{task}/publish', [AdminTaskController::class, 'publish'])->name('tasks.publish');
    Route::post('/tasks/{task}/complete', [AdminTaskController::class, 'complete'])->name('tasks.complete');
    
    // Task submission verification routes
    Route::get('/task-submissions', [TaskSubmissionController::class, 'index'])->name('admin.task-submissions.index');
    Route::get('/task-submissions/{submission}', [TaskSubmissionController::class, 'show'])->name('admin.task-submissions.show');
    Route::post('/task-submissions/{submission}/approve', [TaskSubmissionController::class, 'approve'])->name('admin.task-submissions.approve');
    Route::post('/task-submissions/{submission}/reject', [TaskSubmissionController::class, 'reject'])->name('admin.task-submissions.reject');
    
    // Admin feedback routes
    Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/{task}/create', [AdminFeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback/{task}/store', [AdminFeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/{feedback}/edit', [AdminFeedbackController::class, 'edit'])->name('feedback.edit');
    Route::patch('/feedback/{feedback}/update', [AdminFeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}/destroy', [AdminFeedbackController::class, 'destroy'])->name('feedback.destroy');
    Route::get('/feedback/{task}/show', [AdminFeedbackController::class, 'show'])->name('feedback.show');
    
    Route::get('/reports', function () {
        return view('admin.reports.index');
    })->name('reports.index');

    // Newly added simple pages to support admin navigation
    Route::get('/rewards', function () {
        return view('admin.rewards.index');
    })->name('rewards.index');

    Route::get('/notifications', function () {
        return view('admin.notifications.index');
    })->name('notifications.index');

    Route::get('/feedbacks', function () {
        return view('admin.feedbacks.index');
    })->name('feedbacks.index');
    
    // Admin Tap & Pass Task Chain Tracking
    Route::get('/tap-nominations/task-chain', [TapNominationController::class, 'taskChain'])->name('tap-nominations.task-chain');
});

require __DIR__.'/auth.php';
