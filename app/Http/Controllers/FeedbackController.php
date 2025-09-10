<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;
use App\Models\Task;
use App\Models\User;

class FeedbackController extends Controller
{
    /**
     * Show the feedback form for a specific task
     */
    public function create(Task $task)
    {
        $user = Auth::user();
        
        // Check if user is assigned to this task
        if (!$task->isAssignedTo($user->userId)) {
            return redirect()->route('tasks.show', $task)
                ->with('error', 'You can only provide feedback for tasks you are assigned to.');
        }
        
        // Check if user has already submitted feedback for this task
        $existingFeedback = Feedback::where('FK2_taskId', $task->taskId)
            ->where('FK1_userId', $user->userId)
            ->first();
            
        if ($existingFeedback) {
            return redirect()->route('feedback.edit', $existingFeedback)
                ->with('info', 'You have already submitted feedback for this task. You can edit it below.');
        }
        
        return view('feedback.create', compact('task'));
    }
    
    /**
     * Store user feedback
     */
    public function store(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Check if user is assigned to this task
        if (!$task->isAssignedTo($user->userId)) {
            return redirect()->route('tasks.show', $task)
                ->with('error', 'You can only provide feedback for tasks you are assigned to.');
        }
        
        $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        // Check if user has already submitted feedback for this task
        $existingFeedback = Feedback::where('FK2_taskId', $task->taskId)
            ->where('FK1_userId', $user->userId)
            ->first();
            
        if ($existingFeedback) {
            return redirect()->route('feedback.edit', $existingFeedback)
                ->with('error', 'You have already submitted feedback for this task.');
        }
        
        Feedback::create([
            'FK2_taskId' => $task->taskId,
            'FK1_userId' => $user->userId,
            'comment' => $request->comment,
            'rating' => $request->rating,
            'feedback_date' => now(),
        ]);
        
        // Award points for submitting feedback
        $user->increment('points', 1);
        
        return redirect()->route('tasks.show', $task)
            ->with('success', 'Feedback submitted successfully! You earned 1 point.');
    }
    
    /**
     * Show the edit form for user feedback
     */
    public function edit(Feedback $feedback)
    {
        $user = Auth::user();
        
        // Check if user owns this feedback
        if ($feedback->FK1_userId !== $user->userId) {
            return redirect()->route('tasks.show', $feedback->task)
                ->with('error', 'You can only edit your own feedback.');
        }
        
        return view('feedback.edit', compact('feedback'));
    }
    
    /**
     * Update user feedback
     */
    public function update(Request $request, Feedback $feedback)
    {
        $user = Auth::user();
        
        // Check if user owns this feedback
        if ($feedback->FK1_userId !== $user->userId) {
            return redirect()->route('tasks.show', $feedback->task)
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
        
        return redirect()->route('tasks.show', $feedback->task)
            ->with('success', 'Feedback updated successfully!');
    }
    
    /**
     * Show all feedback for a specific task
     */
    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Check if user is assigned to this task
        if (!$task->isAssignedTo($user->userId)) {
            return redirect()->route('tasks.show', $task)
                ->with('error', 'You can only view feedback for tasks you are assigned to.');
        }
        
        $userFeedback = Feedback::where('FK2_taskId', $task->taskId)
            ->with('user')
            ->get();
            
        $adminFeedback = collect(); // No separate admin feedback in this structure
        
        return view('feedback.show', compact('task', 'userFeedback', 'adminFeedback'));
    }
}
