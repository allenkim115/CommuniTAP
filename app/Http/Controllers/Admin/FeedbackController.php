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
    
    /**
     * Show the form for creating admin feedback for a specific task
     */
    public function create(Task $task)
    {
        return view('admin.feedback.create', compact('task'));
    }
    
    /**
     * Store admin feedback
     */
    public function store(Request $request, Task $task)
    {
        $admin = Auth::user();
        
        $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        Feedback::create([
            'FK2_taskId' => $task->taskId,
            'FK1_userId' => $admin->userId,
            'comment' => $request->comment,
            'rating' => $request->rating,
            'feedback_date' => now(),
        ]);
        
        // Award points for submitting feedback
        $admin->increment('points', 1);
        
        return redirect()->route('admin.feedback.index')
            ->with('success', 'Admin feedback submitted successfully! You earned 1 point.');
    }
    
    /**
     * Show the form for editing admin feedback
     */
    public function edit(Feedback $feedback)
    {
        $admin = Auth::user();
        
        // Check if admin owns this feedback
        if ($feedback->FK1_userId !== $admin->userId) {
            return redirect()->route('admin.feedback.index')
                ->with('error', 'You can only edit your own feedback.');
        }
        
        return view('admin.feedback.edit', compact('feedback'));
    }
    
    /**
     * Update admin feedback
     */
    public function update(Request $request, Feedback $feedback)
    {
        $admin = Auth::user();
        
        // Check if admin owns this feedback
        if ($feedback->FK1_userId !== $admin->userId) {
            return redirect()->route('admin.feedback.index')
                ->with('error', 'You can only edit your own feedback.');
        }
        
        $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        $feedback->update([
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);
        
        return redirect()->route('admin.feedback.index')
            ->with('success', 'Admin feedback updated successfully!');
    }
    
    /**
     * Remove admin feedback
     */
    public function destroy(Feedback $feedback)
    {
        $admin = Auth::user();
        
        // Check if admin owns this feedback
        if ($feedback->FK1_userId !== $admin->userId) {
            return redirect()->route('admin.feedback.index')
                ->with('error', 'You can only delete your own feedback.');
        }
        
        $feedback->delete();
        
        return redirect()->route('admin.feedback.index')
            ->with('success', 'Admin feedback deleted successfully!');
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
