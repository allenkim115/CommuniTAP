<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\TapNomination;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Resolve date range from request (same logic as DashboardController)
     */
    protected function resolveDateRange(Request $request): array
    {
        $period = $request->input('period', 'last_30_days');
        $now = Carbon::now();

        switch ($period) {
            case 'all':
                // Use a very wide date range to show all data (10 years ago to now)
                $start = $now->copy()->subYears(10)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'All time';
                break;
            case 'last_7_days':
                $start = $now->copy()->subDays(6)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'Last 7 days';
                break;
            case 'last_30_days':
                $start = $now->copy()->subDays(29)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'Last 30 days';
                break;
            case 'last_90_days':
                $start = $now->copy()->subDays(89)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'Last 90 days';
                break;
            case 'this_month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                $label = 'This month';
                break;
            case 'this_quarter':
                $start = $now->copy()->firstOfQuarter()->startOfDay();
                $end = $now->copy()->lastOfQuarter()->endOfDay();
                $label = 'This quarter';
                break;
            case 'this_year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                $label = 'This year';
                break;
            case 'custom':
                $startInput = $request->input('start_date');
                $endInput = $request->input('end_date');

                if ($startInput && $endInput) {
                    $start = Carbon::parse($startInput)->startOfDay();
                    $end = Carbon::parse($endInput)->endOfDay();

                    if ($end->lessThan($start)) {
                        [$start, $end] = [$end, $start];
                    }

                    $label = $start->isSameDay($end)
                        ? $start->format('M d, Y')
                        : $start->format('M d, Y') . ' - ' . $end->format('M d, Y');

                    return [$start, $end, $period, $label];
                }

                $start = $now->copy()->subDays(29)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'Last 30 days';
                $period = 'last_30_days';
                break;
            default:
                $start = $now->copy()->subDays(29)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'Last 30 days';
                $period = 'last_30_days';
                break;
        }

        return [$start, $end, $period, $label];
    }

    /**
     * Generate Volunteer Report
     * Includes user details, activity, task completions, points earned
     */
    public function generateVolunteerReport(Request $request)
    {
        // Resolve date range using the same logic as dashboard
        [$currentStart, $currentEnd, $selectedPeriod, $periodLabel] = $this->resolveDateRange($request);
        $startDate = $currentStart->toDateString();
        $endDate = $currentEnd->toDateString();
        
        $status = $request->get('status', 'all');

        // Build query for users
        $usersQuery = User::query();

        // Apply status filter
        if ($status !== 'all') {
            $usersQuery->where('status', $status);
        }

        // Get users with their relationships
        $users = $usersQuery->with([
            'taskAssignments.task',
            'assignedTasks',
            'nominationsMade',
            'nominationsReceived'
        ])->get();

        // Calculate statistics for each user
        $userStats = [];
        foreach ($users as $user) {
            // Get task assignments with date filters (assigned in the period)
            $assignments = $user->taskAssignments()
                ->whereBetween('assigned_at', [$currentStart, $currentEnd])
                ->with('task')
                ->get();
            
            // Get completed assignments (completed in the period)
            $completedAssignments = $user->taskAssignments()
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$currentStart, $currentEnd])
                ->with('task')
                ->get();
            
            // Calculate statistics
            $totalTasksAssigned = $assignments->count();
            $totalTasksCompleted = $completedAssignments->count();
            $totalPointsEarned = $completedAssignments->sum(function($assignment) {
                return $assignment->task ? $assignment->task->points_awarded : 0;
            });
            
            // Get nominations within the period
            $nominationsMade = $user->nominationsMade()
                ->where('status', 'accepted')
                ->whereBetween('nomination_date', [$currentStart, $currentEnd])
                ->count();
            $nominationsReceived = $user->nominationsReceived()
                ->where('status', 'accepted')
                ->whereBetween('nomination_date', [$currentStart, $currentEnd])
                ->count();

            $userStats[] = [
                'user' => $user,
                'totalTasksAssigned' => $totalTasksAssigned,
                'totalTasksCompleted' => $totalTasksCompleted,
                'totalPointsEarned' => $totalPointsEarned,
                'nominationsMade' => $nominationsMade,
                'nominationsReceived' => $nominationsReceived,
            ];
        }

        // Overall statistics (filtered by date range)
        $totalUsers = User::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $activeUsers = User::where('status', 'active')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();
        
        // Points earned from completed tasks in the period
        $taskAssignmentTable = (new TaskAssignment())->getTable();
        $taskTable = (new Task())->getTable();
        $totalPoints = TaskAssignment::where($taskAssignmentTable . '.status', 'completed')
            ->whereBetween($taskAssignmentTable . '.completed_at', [$currentStart, $currentEnd])
            ->join($taskTable, $taskAssignmentTable . '.taskId', '=', $taskTable . '.taskId')
            ->sum($taskTable . '.points_awarded') ?? 0;

        $data = [
            'userStats' => $userStats,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalPoints' => $totalPoints,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'periodLabel' => $periodLabel,
        ];

        $filename = 'CommunityTAP-Volunteer-Report-' . $currentStart->format('Y-m-d') . '_to_' . $currentEnd->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('admin.pdf.volunteer', $data)->setPaper('a4', 'landscape');
        return $pdf->download($filename);
    }

    /**
     * Generate Task Report
     * Includes task details, assignments, completions, statistics
     */
    public function generateTaskReport(Request $request)
    {
        // Resolve date range using the same logic as dashboard
        [$currentStart, $currentEnd, $selectedPeriod, $periodLabel] = $this->resolveDateRange($request);
        $startDate = $currentStart->toDateString();
        $endDate = $currentEnd->toDateString();
        
        $taskType = $request->get('task_type', 'all');
        $status = $request->get('status', 'all');

        // Build query for tasks
        $tasksQuery = Task::with(['assignedUsers', 'assignments'])
            ->whereBetween('creation_date', [$currentStart, $currentEnd]);

        // Apply filters
        if ($taskType !== 'all') {
            $tasksQuery->where('task_type', $taskType);
        }
        if ($status !== 'all') {
            $tasksQuery->where('status', $status);
        }

        $tasks = $tasksQuery->orderBy('creation_date', 'desc')->get();

        // Calculate statistics for each task (filtered by period)
        $taskStats = [];
        foreach ($tasks as $task) {
            // Get assignments within the period
            $assignments = $task->assignments()
                ->whereBetween('assigned_at', [$currentStart, $currentEnd])
                ->get();
            
            // Get completed assignments within the period
            $completedAssignments = $task->assignments()
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$currentStart, $currentEnd])
                ->get();
            
            $assignedCount = $assignments->count();
            $completedCount = $completedAssignments->count();
            $totalPointsAwarded = $completedAssignments->count() * $task->points_awarded;

            $taskStats[] = [
                'task' => $task,
                'assignedCount' => $assignedCount,
                'completedCount' => $completedCount,
                'completionRate' => $assignedCount > 0 ? ($completedCount / $assignedCount) * 100 : 0,
                'totalPointsAwarded' => $totalPointsAwarded,
            ];
        }

        // Overall statistics (filtered by date range)
        $totalTasks = Task::whereBetween('creation_date', [$currentStart, $currentEnd])->count();
        
        // Get table names to avoid ambiguity
        $taskAssignmentTable = (new TaskAssignment())->getTable();
        $taskTable = (new Task())->getTable();
        
        $totalAssignments = TaskAssignment::whereBetween('assigned_at', [$currentStart, $currentEnd])->count();
        $totalCompleted = TaskAssignment::where('status', 'completed')
            ->whereBetween('completed_at', [$currentStart, $currentEnd])
            ->count();
        $totalPointsAwarded = TaskAssignment::where($taskAssignmentTable . '.status', 'completed')
            ->whereBetween($taskAssignmentTable . '.completed_at', [$currentStart, $currentEnd])
            ->join($taskTable, $taskAssignmentTable . '.taskId', '=', $taskTable . '.taskId')
            ->sum($taskTable . '.points_awarded') ?? 0;

        $data = [
            'taskStats' => $taskStats,
            'totalTasks' => $totalTasks,
            'totalAssignments' => $totalAssignments,
            'totalCompleted' => $totalCompleted,
            'totalPointsAwarded' => $totalPointsAwarded,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'taskType' => $taskType,
            'status' => $status,
            'periodLabel' => $periodLabel,
        ];

        $filename = 'CommunityTAP-Task-Report-' . $currentStart->format('Y-m-d') . '_to_' . $currentEnd->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('admin.pdf.task', $data)->setPaper('a4', 'landscape');
        return $pdf->download($filename);
    }

    /**
     * Generate Task Chain Report
     * Includes task chain data from tap nominations
     */
    public function generateTaskChainReport(Request $request)
    {
        // Resolve date range using the same logic as dashboard
        [$currentStart, $currentEnd, $selectedPeriod, $periodLabel] = $this->resolveDateRange($request);
        $startDate = $currentStart->toDateString();
        $endDate = $currentEnd->toDateString();

        // Build query for task chain (accepted nominations for daily tasks)
        $chainQuery = TapNomination::with(['task', 'nominator', 'nominee'])
            ->accepted()
            ->forDailyTasks();

        // Apply date filters
        $chainQuery->whereBetween('nomination_date', [$currentStart, $currentEnd]);

        $taskChain = $chainQuery->orderBy('nomination_date', 'desc')->get();

        // Calculate statistics
        $totalNominations = $taskChain->count();
        $uniqueTasks = $taskChain->pluck('FK1_taskId')->unique()->count();
        $uniqueNominators = $taskChain->pluck('FK2_nominatorId')->unique()->count();
        $uniqueNominees = $taskChain->pluck('FK3_nomineeId')->unique()->count();
        $uniqueParticipants = $taskChain->pluck('FK2_nominatorId')
            ->merge($taskChain->pluck('FK3_nomineeId'))
            ->unique()
            ->count();

        // Group by task to show chains
        $chainsByTask = $taskChain->groupBy('FK1_taskId');

        $data = [
            'taskChain' => $taskChain,
            'chainsByTask' => $chainsByTask,
            'totalNominations' => $totalNominations,
            'uniqueTasks' => $uniqueTasks,
            'uniqueNominators' => $uniqueNominators,
            'uniqueNominees' => $uniqueNominees,
            'uniqueParticipants' => $uniqueParticipants,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodLabel' => $periodLabel,
        ];

        $filename = 'CommunityTAP-Task-Chain-Report-' . $currentStart->format('Y-m-d') . '_to_' . $currentEnd->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('admin.pdf.task-chain', $data)->setPaper('a4', 'landscape');
        return $pdf->download($filename);
    }
}

