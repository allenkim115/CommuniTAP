<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is admin
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }
        
        // Regular user dashboard
        return $this->userDashboard();
    }
    
    public function adminDashboard()
    {
        // Get admin statistics
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $totalTasks = Task::count();
        $totalPoints = User::sum('points');
        
        return view('admin-dashboard', compact('totalUsers', 'activeUsers', 'totalTasks', 'totalPoints'));
    }
    
    private function userDashboard()
    {
        // Get user-specific data
        $user = Auth::user();
        
        // Get tasks assigned to user through the new assignment system
        $userTasks = $user->assignedTasks()->get();
        
        // Get ongoing tasks (assigned, submitted statuses)
        $ongoingTasks = $user->ongoingTasks()->get();
        
        // Get completed tasks (completed status)
        $completedTasks = $user->completedTasks()->get();
        
        // Count tasks by status
        $ongoingTasksCount = $ongoingTasks->count();
        $completedTasksCount = $completedTasks->count();
        
        return view('dashboard', compact('userTasks', 'ongoingTasks', 'completedTasks', 'ongoingTasksCount', 'completedTasksCount'));
    }
    
    /**
     * Display the progress page for the authenticated user
     */
    public function progress(Request $request)
    {
        $user = Auth::user();
        
        // Get user statistics
        $userPoints = $user->points;
        $completedTasksCount = $user->completedTasks()->count();
        
        // Get claimed rewards count (placeholder - would need RewardRedemption model)
        $claimedRewardsCount = 0; // TODO: Implement when reward redemption is available
        
        // Get all completed tasks for history
        $completedTasks = $user->completedTasks()
            ->withPivot('status', 'assigned_at', 'submitted_at', 'completed_at', 'photos', 'completion_notes')
            ->orderBy('task_assignments.completed_at', 'desc')
            ->get();
        
        // Apply filters if provided
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $taskType = $request->get('task_type');
        
        if ($startDate || $endDate || $taskType) {
            $query = $user->completedTasks()
                ->withPivot('status', 'assigned_at', 'submitted_at', 'completed_at', 'photos', 'completion_notes');
            
            if ($startDate) {
                $query->where('task_assignments.completed_at', '>=', $startDate);
            }
            
            if ($endDate) {
                $query->where('task_assignments.completed_at', '<=', $endDate . ' 23:59:59');
            }
            
            if ($taskType && $taskType !== 'all') {
                $query->where('task_type', $taskType);
            }
            
            $completedTasks = $query->orderBy('task_assignments.completed_at', 'desc')->get();
        }
        
        return view('progress', compact(
            'userPoints', 
            'completedTasksCount', 
            'claimedRewardsCount', 
            'completedTasks',
            'startDate',
            'endDate', 
            'taskType'
        ));
    }
}
