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
     * List the current user's feedback across tasks
     */
    public function index()
    {
        $user = Auth::user();
        $feedback = Feedback::where('FK1_userId', $user->userId)
            ->with('task')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('feedback.index', compact('feedback'));
    }

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
        
        // Award points for submitting feedback (respecting points cap)
        $pointsResult = $user->addPoints(1);
        
        $successMessage = $pointsResult['added'] > 0
            ? 'Feedback submitted successfully! You earned 1 point.'
            : 'Feedback submitted successfully! However, you have reached the points cap (500 points), so no points were added.';
        
        $pointsEarned = $pointsResult['added'] > 0 ? $pointsResult['added'] : 0;
        $successMessage = "Feedback for '{$task->title}' submitted successfully";
        if ($pointsEarned > 0) {
            $successMessage .= "! You earned {$pointsEarned} point" . ($pointsEarned > 1 ? 's' : '') . " for providing feedback.";
        } else {
            $successMessage .= ". Note: You've reached the points cap (500 points), so no additional points were awarded.";
        }
        return redirect()->route('tasks.show', $task)->with('success', $successMessage);
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
            ->with('success', "Your feedback for '{$feedback->task->title}' has been updated successfully!");
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
