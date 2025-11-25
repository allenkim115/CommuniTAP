<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\TapNomination;
use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\UserIncidentReport;
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
     * Generate User Accounts List
     */
    public function generateUserAccountsReport(Request $request)
    {
        [$currentStart, $currentEnd, $selectedPeriod, $periodLabel] = $this->resolveDateRange($request);
        $startDate = $currentStart->toDateString();
        $endDate = $currentEnd->toDateString();

        $status = $request->get('status', 'all');
        $role = $request->get('role', 'all');

        $usersQuery = User::query();

        if ($status !== 'all') {
            $usersQuery->where('status', $status);
        }

        if ($role !== 'all') {
            $usersQuery->where('role', $role);
        }

        $users = $usersQuery->orderBy('created_at', 'desc')->get();

        $userStats = $users->map(function ($user) use ($currentStart, $currentEnd) {
            $assignments = $user->taskAssignments()
                ->whereBetween('assigned_at', [$currentStart, $currentEnd])
                ->with('task')
                ->get();

            $completedAssignments = $user->taskAssignments()
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$currentStart, $currentEnd])
                ->with('task')
                ->get();

            $pointsEarned = $completedAssignments->sum(function ($assignment) {
                return $assignment->task ? $assignment->task->points_awarded : 0;
            });

            $nominationsMade = $user->nominationsMade()
                ->where('status', 'accepted')
                ->whereBetween('nomination_date', [$currentStart, $currentEnd])
                ->count();

            $nominationsReceived = $user->nominationsReceived()
                ->where('status', 'accepted')
                ->whereBetween('nomination_date', [$currentStart, $currentEnd])
                ->count();

            return [
                'user' => $user,
                'assignments' => $assignments->count(),
                'completions' => $completedAssignments->count(),
                'pointsEarned' => $pointsEarned,
                'nominationsMade' => $nominationsMade,
                'nominationsReceived' => $nominationsReceived,
                'lastAssignedAt' => optional($assignments->sortByDesc('assigned_at')->first())->assigned_at,
                'lastCompletedAt' => optional($completedAssignments->sortByDesc('completed_at')->first())->completed_at,
            ];
        });

        $taskAssignmentTable = (new TaskAssignment())->getTable();
        $taskTable = (new Task())->getTable();

        $totalPointsPeriod = TaskAssignment::where($taskAssignmentTable . '.status', 'completed')
            ->whereBetween($taskAssignmentTable . '.completed_at', [$currentStart, $currentEnd])
            ->join($taskTable, $taskAssignmentTable . '.taskId', '=', $taskTable . '.taskId')
            ->sum($taskTable . '.points_awarded') ?? 0;

        $data = [
            'userStats' => $userStats,
            'totalUsers' => $users->count(),
            'activeUsers' => $users->where('status', 'active')->count(),
            'totalPoints' => $users->sum('points'),
            'totalPointsPeriod' => $totalPointsPeriod,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'role' => $role,
            'periodLabel' => $periodLabel,
        ];

        $filename = 'CommunityTAP-User-Accounts-Report-' . $currentStart->format('Y-m-d') . '_to_' . $currentEnd->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('admin.pdf.user-accounts', $data)->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /**
     * Generate Task List
     */
    public function generateTaskListReport(Request $request)
    {
        [$currentStart, $currentEnd, $selectedPeriod, $periodLabel] = $this->resolveDateRange($request);
        $startDate = $currentStart->toDateString();
        $endDate = $currentEnd->toDateString();

        $taskType = $request->get('task_type', 'all');
        $status = $request->get('status', 'all');

        $tasksQuery = Task::with(['assignedUsers', 'assignments'])
            ->whereBetween('creation_date', [$currentStart, $currentEnd]);

        if ($taskType !== 'all') {
            $tasksQuery->where('task_type', $taskType);
        }

        if ($status !== 'all') {
            $tasksQuery->where('status', $status);
        }

        $tasks = $tasksQuery->orderBy('creation_date', 'desc')->get();

        $taskStats = $tasks->map(function ($task) use ($currentStart, $currentEnd) {
            $assignments = $task->assignments()
                ->whereBetween('assigned_at', [$currentStart, $currentEnd])
                ->get();

            $completedAssignments = $task->assignments()
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$currentStart, $currentEnd])
                ->get();

            $assignedCount = $assignments->count();
            $completedCount = $completedAssignments->count();

            return [
                'task' => $task,
                'assignedCount' => $assignedCount,
                'completedCount' => $completedCount,
                'completionRate' => $assignedCount > 0 ? ($completedCount / $assignedCount) * 100 : 0,
                'totalPointsAwarded' => $completedCount * $task->points_awarded,
                'participants' => $task->assignedUsers->count(),
            ];
        });

        $taskAssignmentTable = (new TaskAssignment())->getTable();
        $taskTable = (new Task())->getTable();

        $totalTasks = Task::whereBetween('creation_date', [$currentStart, $currentEnd])->count();
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

        $filename = 'CommunityTAP-Task-List-' . $currentStart->format('Y-m-d') . '_to_' . $currentEnd->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('admin.pdf.task-list', $data)->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /**
     * Generate Rewards List
     */
    public function generateRewardsReport(Request $request)
    {
        [$currentStart, $currentEnd, $selectedPeriod, $periodLabel] = $this->resolveDateRange($request);
        $startDate = $currentStart->toDateString();
        $endDate = $currentEnd->toDateString();

        $status = $request->get('status', 'all');

        $rewardsQuery = Reward::query();

        if ($status !== 'all') {
            $rewardsQuery->where('status', $status);
        }

        $rewards = $rewardsQuery
            ->withCount('redemptions')
            ->orderBy('created_date', 'desc')
            ->get();

        $rewardIds = $rewards->pluck('rewardId');

        $periodRedemptions = $rewardIds->isNotEmpty()
            ? RewardRedemption::whereIn('FK1_rewardId', $rewardIds)
                ->whereBetween('redemption_date', [$currentStart, $currentEnd])
                ->get()
                ->groupBy('FK1_rewardId')
            : collect();

        $rewardStats = $rewards->map(function ($reward) use ($periodRedemptions) {
            $redemptions = $periodRedemptions->get($reward->rewardId, collect());

            return [
                'reward' => $reward,
                'redemptionsInPeriod' => $redemptions->count(),
                'pendingRedemptions' => $redemptions->where('status', 'pending')->count(),
                'approvedRedemptions' => $redemptions->where('status', 'approved')->count(),
                'claimedRedemptions' => $redemptions->where('status', 'claimed')->count(),
                'pointsRedeemed' => $redemptions->count() * ($reward->points_cost ?? 0),
                'inventoryRemaining' => $reward->QTY ?? 0,
            ];
        });

        $redemptionsThisPeriod = $rewardStats->sum('redemptionsInPeriod');
        $pointsRedeemed = $rewardStats->sum('pointsRedeemed');
        $activeRewards = $rewards->where('status', 'active')->count();
        $inactiveRewards = $rewards->count() - $activeRewards;
        $inventoryTotal = $rewards->sum(function ($reward) {
            return $reward->QTY ?? 0;
        });

        $pendingGlobal = RewardRedemption::where('status', 'pending')->count();

        $data = [
            'rewardStats' => $rewardStats,
            'totalRewards' => $rewards->count(),
            'activeRewards' => $activeRewards,
            'inactiveRewards' => $inactiveRewards,
            'inventoryTotal' => $inventoryTotal,
            'redemptionsThisPeriod' => $redemptionsThisPeriod,
            'pointsRedeemed' => $pointsRedeemed,
            'pendingGlobal' => $pendingGlobal,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'periodLabel' => $periodLabel,
        ];

        $filename = 'CommunityTAP-Rewards-List-' . $currentStart->format('Y-m-d') . '_to_' . $currentEnd->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('admin.pdf.rewards-list', $data)->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /**
     * Generate Tap & Pass Nominations List
     */
    public function generateTapPassReport(Request $request)
    {
        [$currentStart, $currentEnd, $selectedPeriod, $periodLabel] = $this->resolveDateRange($request);
        $startDate = $currentStart->toDateString();
        $endDate = $currentEnd->toDateString();

        $status = $request->get('status', 'all');

        $nominationsQuery = TapNomination::with(['task', 'nominator', 'nominee'])
            ->whereBetween('nomination_date', [$currentStart, $currentEnd]);

        if ($status !== 'all') {
            $nominationsQuery->where('status', $status);
        }

        $nominations = $nominationsQuery->orderBy('nomination_date', 'desc')->get();

        $statusCounts = [
            'pending' => $nominations->where('status', 'pending')->count(),
            'accepted' => $nominations->where('status', 'accepted')->count(),
            'declined' => $nominations->where('status', 'declined')->count(),
        ];

        $topNominators = $nominations
            ->groupBy('FK2_nominatorId')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'user' => optional($first)->nominator,
                    'userId' => $first->FK2_nominatorId ?? null,
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values();

        $topNominees = $nominations
            ->groupBy('FK3_nomineeId')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'user' => optional($first)->nominee,
                    'userId' => $first->FK3_nomineeId ?? null,
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values();

        $taskBreakdown = $nominations
            ->groupBy('FK1_taskId')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'task' => optional($first)->task,
                    'taskId' => $first->FK1_taskId ?? null,
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values();

        $data = [
            'nominations' => $nominations,
            'statusCounts' => $statusCounts,
            'topNominators' => $topNominators,
            'topNominees' => $topNominees,
            'taskBreakdown' => $taskBreakdown,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'periodLabel' => $periodLabel,
        ];

        $filename = 'CommunityTAP-Tap-and-Pass-' . $currentStart->format('Y-m-d') . '_to_' . $currentEnd->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('admin.pdf.tap-pass-nominations', $data)->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /**
     * Generate Incident Reports List
     */
    public function generateIncidentReportsReport(Request $request)
    {
        [$currentStart, $currentEnd, $selectedPeriod, $periodLabel] = $this->resolveDateRange($request);
        $startDate = $currentStart->toDateString();
        $endDate = $currentEnd->toDateString();

        $status = $request->get('status', 'all');
        $incidentType = $request->get('incident_type', 'all');

        $incidentQuery = UserIncidentReport::with(['reporter', 'reportedUser', 'task', 'moderator'])
            ->whereBetween('report_date', [$currentStart, $currentEnd]);

        if ($status !== 'all') {
            $incidentQuery->where('status', $status);
        }

        if ($incidentType !== 'all') {
            $incidentQuery->where('incident_type', $incidentType);
        }

        $incidents = $incidentQuery->orderBy('report_date', 'desc')->get();

        $statusCounts = $incidents->groupBy('status')->map->count();
        $typeCounts = $incidents->groupBy('incident_type')->map->count();

        $resolutionDurations = $incidents->filter(function ($report) {
            return $report->moderation_date && $report->report_date;
        })->map(function ($report) {
            $start = $report->report_date;
            $end = $report->moderation_date;

            if ($end->lessThan($start)) {
                [$start, $end] = [$end, $start];
            }

            return $end->diffInHours($start);
        });

        $averageResolutionHours = $resolutionDurations->isNotEmpty()
            ? round($resolutionDurations->avg(), 1)
            : null;

        $medianResolutionHours = $resolutionDurations->isNotEmpty()
            ? round($resolutionDurations->sort()->values()->median(), 1)
            : null;

        $overdueCount = $incidents->filter(function ($report) {
            return in_array($report->status, ['pending', 'under_review']) &&
                $report->report_date &&
                $report->report_date->lessThanOrEqualTo(now()->subDays(7));
        })->count();

        $data = [
            'incidents' => $incidents,
            'statusCounts' => $statusCounts,
            'typeCounts' => $typeCounts,
            'averageResolutionHours' => $averageResolutionHours,
            'medianResolutionHours' => $medianResolutionHours,
            'overdueCount' => $overdueCount,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'incidentType' => $incidentType,
            'periodLabel' => $periodLabel,
        ];

        $filename = 'CommunityTAP-Incident-Reports-' . $currentStart->format('Y-m-d') . '_to_' . $currentEnd->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('admin.pdf.incident-reports', $data)->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}

