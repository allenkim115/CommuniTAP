<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\TaskSubmissionController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/progress', [App\Http\Controllers\DashboardController::class, 'progress'])
    ->middleware(['auth', 'verified'])
    ->name('progress');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Task management routes for regular users
    Route::resource('tasks', TaskController::class);
    Route::post('/tasks/{task}/join', [TaskController::class, 'join'])->name('tasks.join');
    Route::post('/tasks/{task}/submit', [TaskController::class, 'submit'])->name('tasks.submit');
    
    // Feedback routes for regular users
    Route::get('/feedback/{task}/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback/{task}/store', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/{feedback}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::patch('/feedback/{feedback}/update', [FeedbackController::class, 'update'])->name('feedback.update');
    Route::get('/feedback/{task}/show', [FeedbackController::class, 'show'])->name('feedback.show');
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
});

require __DIR__.'/auth.php';
