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
     * Generate Volunteer Report
     * Includes user details, activity, task completions, points earned
     */
    public function generateVolunteerReport(Request $request)
    {
        // Get optional filters
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
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
            // Get task assignments with date filters
            $assignmentsQuery = $user->taskAssignments();
            if ($startDate) {
                $assignmentsQuery->where('assigned_at', '>=', $startDate);
            }
            if ($endDate) {
                $assignmentsQuery->where('assigned_at', '<=', $endDate . ' 23:59:59');
            }
            $assignments = $assignmentsQuery->with('task')->get();
            
            // Get completed assignments
            $completedAssignments = $assignments->where('status', 'completed');
            if ($startDate) {
                $startDateCarbon = Carbon::parse($startDate);
                $completedAssignments = $completedAssignments->filter(function($assignment) use ($startDateCarbon) {
                    return $assignment->completed_at && $assignment->completed_at >= $startDateCarbon;
                });
            }
            if ($endDate) {
                $endDateCarbon = Carbon::parse($endDate . ' 23:59:59');
                $completedAssignments = $completedAssignments->filter(function($assignment) use ($endDateCarbon) {
                    return $assignment->completed_at && $assignment->completed_at <= $endDateCarbon;
                });
            }
            
            // Calculate statistics
            $totalTasksAssigned = $assignments->count();
            $totalTasksCompleted = $completedAssignments->count();
            $totalPointsEarned = $completedAssignments->sum(function($assignment) {
                return $assignment->task ? $assignment->task->points_awarded : 0;
            });
            
            // Get nominations
            $nominationsMade = $user->nominationsMade->where('status', 'accepted')->count();
            $nominationsReceived = $user->nominationsReceived->where('status', 'accepted')->count();

            $userStats[] = [
                'user' => $user,
                'totalTasksAssigned' => $totalTasksAssigned,
                'totalTasksCompleted' => $totalTasksCompleted,
                'totalPointsEarned' => $totalPointsEarned,
                'nominationsMade' => $nominationsMade,
                'nominationsReceived' => $nominationsReceived,
            ];
        }

        // Overall statistics
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $totalPoints = User::sum('points');

        $data = [
            'userStats' => $userStats,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalPoints' => $totalPoints,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
        ];

        $pdf = Pdf::loadView('admin.pdf.volunteer', $data);
        return $pdf->download('CommunityTAP-Volunteer-Report-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate Task Report
     * Includes task details, assignments, completions, statistics
     */
    public function generateTaskReport(Request $request)
    {
        // Get optional filters
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $taskType = $request->get('task_type', 'all');
        $status = $request->get('status', 'all');

        // Build query for tasks
        $tasksQuery = Task::with(['assignedUsers', 'assignments']);

        // Apply filters
        if ($taskType !== 'all') {
            $tasksQuery->where('task_type', $taskType);
        }
        if ($status !== 'all') {
            $tasksQuery->where('status', $status);
        }
        if ($startDate) {
            $tasksQuery->where('creation_date', '>=', $startDate);
        }
        if ($endDate) {
            $tasksQuery->where('creation_date', '<=', $endDate . ' 23:59:59');
        }

        $tasks = $tasksQuery->orderBy('creation_date', 'desc')->get();

        // Calculate statistics for each task
        $taskStats = [];
        foreach ($tasks as $task) {
            $assignments = $task->assignments;
            $completedAssignments = $assignments->where('status', 'completed');
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

        // Overall statistics
        $totalTasks = Task::count();
        $totalAssignments = TaskAssignment::count();
        $totalCompleted = TaskAssignment::where('status', 'completed')->count();
        
        // Get table names to avoid ambiguity
        $taskAssignmentTable = (new TaskAssignment())->getTable();
        $taskTable = (new Task())->getTable();
        
        $totalPointsAwarded = TaskAssignment::where($taskAssignmentTable . '.status', 'completed')
            ->join($taskTable, $taskAssignmentTable . '.taskId', '=', $taskTable . '.taskId')
            ->sum($taskTable . '.points_awarded');

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
        ];

        $pdf = Pdf::loadView('admin.pdf.task', $data);
        return $pdf->download('CommunityTAP-Task-Report-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate Task Chain Report
     * Includes task chain data from tap nominations
     */
    public function generateTaskChainReport(Request $request)
    {
        // Get optional filters
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Build query for task chain (accepted nominations for daily tasks)
        $chainQuery = TapNomination::with(['task', 'nominator', 'nominee'])
            ->accepted()
            ->forDailyTasks();

        // Apply date filters
        if ($startDate) {
            $chainQuery->where('nomination_date', '>=', $startDate);
        }
        if ($endDate) {
            $chainQuery->where('nomination_date', '<=', $endDate . ' 23:59:59');
        }

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
        ];

        $pdf = Pdf::loadView('admin.pdf.task-chain', $data);
        return $pdf->download('CommunityTAP-Task-Chain-Report-' . date('Y-m-d') . '.pdf');
    }
}

