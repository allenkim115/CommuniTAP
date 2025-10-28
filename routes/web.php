<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\TapNominationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\TaskSubmissionController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;

// =====================
// Public Routes
// =====================
Route::get('/', fn() => view('welcome'));

// =====================
// User Dashboard
// =====================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/progress', [DashboardController::class, 'progress'])
    ->middleware(['auth', 'verified'])
    ->name('progress');

// =====================
// Authenticated User Routes
// =====================
Route::middleware('auth')->group(function () {

    // ----- PROFILE -----
    Route::resource('profile', ProfileController::class)
        ->only(['edit', 'update', 'destroy']);

    // ----- TASKS -----
    Route::resource('tasks', TaskController::class);
    Route::post('/tasks/{task}/join', [TaskController::class, 'join'])->name('tasks.join');
    Route::post('/tasks/{task}/submit', [TaskController::class, 'submit'])->name('tasks.submit');

    // ----- FEEDBACK -----
    Route::resource('feedback', FeedbackController::class)
        ->only(['create', 'store', 'edit', 'update', 'show']);

    // ----- TAP & PASS NOMINATIONS -----
    Route::resource('tap-nominations', TapNominationController::class)
        ->only(['index', 'create', 'store']);
    Route::post('/tap-nominations/{nomination}/accept', [TapNominationController::class, 'accept'])
        ->name('tap-nominations.accept');
    Route::post('/tap-nominations/{nomination}/decline', [TapNominationController::class, 'decline'])
        ->name('tap-nominations.decline');

    // ----- DEBUG & TEST ROUTES (For Dev) -----
    Route::get('/debug-nominations', function () {
        $user = Auth::user();
        $allNominations = \App\Models\TapNomination::all();
        $userNominations = $user->nominationsReceived()->get();
        $tableStructure = \Illuminate\Support\Facades\DB::select("DESCRIBE tap_nominations");

        return response()->json([
            'user_id' => $user->userId,
            'total_nominations' => $allNominations->count(),
            'user_nominations' => $userNominations->count(),
            'table_structure' => $tableStructure,
        ]);
    })->name('debug.nominations');

    Route::get('/debug-task-assignments', function () {
        $user = Auth::user();
        $task10 = \App\Models\Task::find(10);
        $assignments = \App\Models\TaskAssignment::where('userId', $user->userId)
            ->where('status', 'completed')
            ->with('task')
            ->get();

        return response()->json([
            'user_id' => $user->userId,
            'task_10' => $task10,
            'completed_tasks' => $assignments,
        ]);
    })->name('debug.task.assignments');

    Route::get('/debug-admin-status', function () {
        $user = Auth::user();
        return response()->json([
            'user_id' => $user->userId,
            'role' => $user->role,
            'is_admin' => method_exists($user, 'isAdmin') ? $user->isAdmin() : false,
        ]);
    })->name('debug.admin.status');
});

// =====================
// ADMIN ROUTES
// =====================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ----- DASHBOARD -----
        Route::get('/', [DashboardController::class, 'adminDashboard'])
            ->name('dashboard');

        // ----- USERS -----
        Route::resource('users', AdminUserController::class);
        Route::patch('/users/{user}/suspend', [AdminUserController::class, 'suspend'])
            ->name('users.suspend');
        Route::patch('/users/{user}/reactivate', [AdminUserController::class, 'reactivate'])
            ->name('users.reactivate');

        // ----- TASKS -----
        Route::get('/tasks/filter', [AdminTaskController::class, 'filter'])
            ->name('tasks.filter');
        Route::resource('tasks', AdminTaskController::class);
        Route::post('/tasks/{task}/approve', [AdminTaskController::class, 'approve'])
            ->name('tasks.approve');
        Route::post('/tasks/{task}/reject', [AdminTaskController::class, 'reject'])
            ->name('tasks.reject');
        Route::post('/tasks/{task}/publish', [AdminTaskController::class, 'publish'])
            ->name('tasks.publish');
        Route::post('/tasks/{task}/complete', [AdminTaskController::class, 'complete'])
            ->name('tasks.complete');

        // ----- TASK SUBMISSIONS -----
        Route::resource('task-submissions', TaskSubmissionController::class)
            ->only(['index', 'show', 'destroy']);
        Route::post('/task-submissions/{submission}/approve', [TaskSubmissionController::class, 'approve'])
            ->name('task-submissions.approve');
        Route::post('/task-submissions/{submission}/reject', [TaskSubmissionController::class, 'reject'])
            ->name('task-submissions.reject');

        // ----- FEEDBACK -----
        Route::resource('feedback', AdminFeedbackController::class);

        // ----- REPORTS, REWARDS, NOTIFICATIONS -----
        Route::view('/reports', 'admin.reports.index')->name('reports.index');
        Route::view('/rewards', 'admin.rewards.index')->name('rewards.index');
        Route::view('/notifications', 'admin.notifications.index')->name('notifications.index');
        Route::view('/feedbacks', 'admin.feedbacks.index')->name('feedbacks.index');

        // ----- TAP & PASS -----
        Route::get('/tap-nominations/task-chain', [TapNominationController::class, 'taskChain'])
            ->name('tap-nominations.task-chain');

        Route::resource('incident-reports', App\Http\Controllers\Admin\IncidentReportController::class);

    });

// =====================
// AUTH ROUTES
// =====================
require __DIR__ . '/auth.php';
