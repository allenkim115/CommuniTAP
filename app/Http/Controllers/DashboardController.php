<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;
use App\Models\Reward;


use Barryvdh\DomPDF\Facade\Pdf; 



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
    // Overview Cards
    $totalUsers = User::count();
    $totalTasks = Task::count();
    $totalPoints = User::sum('points'); // fixed

    // Task status counts for chart
    $tasksCompleted = Task::where('status', 'completed')->count();
    $tasksPending = Task::where('status', 'pending')->count();
    $tasksInProgress = Task::where('status', 'in_progress')->count();

    // User growth per month (last 6 months)
    $userGrowth = [];
    $labels = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i)->format('M');
        $count = User::whereMonth('created_at', now()->subMonths($i)->month)->count();
        $labels[] = $month;
        $userGrowth[] = $count;
    }

    return view('admin-dashboard', compact(
        'totalUsers',
        'totalTasks',
        'totalPoints',
        'tasksCompleted',
        'tasksPending',
        'tasksInProgress',
        'labels',
        'userGrowth'
    ));
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
        
        // Preserve filter inputs for the view
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $taskType = $request->get('task_type', 'all');

        $query = $user->completedTasks()
            ->withPivot('status', 'assigned_at', 'submitted_at', 'completed_at', 'photos', 'completion_notes')
            ->orderBy('task_assignments.completed_at', 'desc');

        if ($startDate) {
            $query->where('task_assignments.completed_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('task_assignments.completed_at', '<=', $endDate . ' 23:59:59');
        }

        if ($taskType && $taskType !== 'all') {
            $query->where('task_type', $taskType);
        }

        $completedTasks = $query->get();

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


    public function generateAdminPdf()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $totalTasks = Task::count();
        $totalPoints = User::sum('points');
        $totalRewards = Reward::count();

        $users = User::all();
        $tasks = Task::with('assignedUsers')->get();
        $rewards = Reward::all();

        $pdf = Pdf::loadView('admin/pdf/pdf-generate', compact(
            'totalUsers', 'activeUsers', 'totalTasks', 'totalPoints', 'users', 'tasks',
             'rewards',
            'totalRewards'
        ));

        return $pdf->download('admin_dashboard_report.pdf');
    }

}
