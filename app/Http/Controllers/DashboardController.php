<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect to correct dashboard
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }

        return $this->userDashboard();
    }

    public function adminDashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $totalTasks = Task::count();
        $totalPoints = User::sum('points');

        return view('admin-dashboard', compact('totalUsers', 'activeUsers', 'totalTasks', 'totalPoints'));
    }

    private function userDashboard()
    {
        $user = Auth::user();

        $userTasks = $user->assignedTasks()->get();
        $ongoingTasks = $user->ongoingTasks()->get();
        $completedTasks = $user->completedTasks()->get();

        $ongoingTasksCount = $ongoingTasks->count();
        $completedTasksCount = $completedTasks->count();

        return view('dashboard', compact(
            'userTasks', 
            'ongoingTasks', 
            'completedTasks', 
            'ongoingTasksCount', 
            'completedTasksCount'
        ));
    }

    public function progress(Request $request)
    {
        $user = Auth::user();
        $userPoints = $user->points;
        $completedTasksCount = $user->completedTasks()->count();
        $claimedRewardsCount = 0;

        $query = $user->completedTasks()
            ->withPivot('status', 'assigned_at', 'submitted_at', 'completed_at', 'photos', 'completion_notes')
            ->orderBy('task_assignments.completed_at', 'desc');

        if ($request->start_date) {
            $query->where('task_assignments.completed_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('task_assignments.completed_at', '<=', $request->end_date . ' 23:59:59');
        }

        if ($request->task_type && $request->task_type !== 'all') {
            $query->where('task_type', $request->task_type);
        }

        $completedTasks = $query->get();

        return view('progress', compact(
            'userPoints', 
            'completedTasksCount', 
            'claimedRewardsCount', 
            'completedTasks'
        ));
    }
}
