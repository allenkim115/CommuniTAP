<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;
use App\Models\Task;
use App\Models\User;

class FeedbackController extends Controller
{
    /**
     * Display a listing of all feedback
     */
    public function index()
    {
        $userFeedback = Feedback::with(['task', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $adminFeedback = collect(); // No separate admin feedback in this structure
        
        return view('admin.feedback.index', compact('userFeedback', 'adminFeedback'));
    }
    
    // Admins are not allowed to create task feedback
    public function create(Task $task)
    {
        return redirect()->route('admin.feedback.index')
            ->with('error', 'Admins cannot create task feedback.');
    }
    
    // Admins are not allowed to store task feedback
    public function store(Request $request, Task $task)
    {
        return redirect()->route('admin.feedback.index')
            ->with('error', 'Admins cannot create task feedback.');
    }
    
    // Admins are not allowed to edit feedback
    public function edit(Feedback $feedback)
    {
        return redirect()->route('admin.feedback.index')
            ->with('error', 'Admins cannot edit task feedback.');
    }
    
    // Admins are not allowed to update feedback
    public function update(Request $request, Feedback $feedback)
    {
        return redirect()->route('admin.feedback.index')
            ->with('error', 'Admins cannot edit task feedback.');
    }
    
    // Admins are not allowed to delete feedback
    public function destroy(Feedback $feedback)
    {
        return redirect()->route('admin.feedback.index')
            ->with('error', 'Admins cannot delete task feedback.');
    }
    
    /**
     * Show all feedback for a specific task (admin view)
     */
    public function show(Task $task)
    {
        $userFeedback = Feedback::where('FK2_taskId', $task->taskId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $adminFeedback = collect(); // No separate admin feedback in this structure
        
        return view('admin.feedback.show', compact('task', 'userFeedback', 'adminFeedback'));
    }
}
