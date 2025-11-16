<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Task;
use App\Models\Reward;
use App\Models\TaskAssignment;
use App\Models\TapNomination;
use App\Models\UserIncidentReport;
use Barryvdh\DomPDF\Facade\Pdf; 
use Carbon\Carbon;
use Carbon\CarbonPeriod;



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

    public function adminDashboard(Request $request)
{
        [$currentStart, $currentEnd, $selectedPeriod, $periodLabel] = $this->resolveDateRange($request);
        [$previousStart, $previousEnd] = $this->buildComparisonRange($currentStart, $currentEnd);

        $totalUsers = User::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $totalUsersPrevious = User::whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $totalUsersDelta = $this->calculateDelta($totalUsers, $totalUsersPrevious);

        $totalTasks = Task::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $totalTasksPrevious = Task::whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $totalTasksDelta = $this->calculateDelta($totalTasks, $totalTasksPrevious);

        $totalIncidents = UserIncidentReport::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $totalIncidentsPrevious = UserIncidentReport::whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $totalIncidentsDelta = $this->calculateDelta($totalIncidents, $totalIncidentsPrevious);

        $taskStatusCounts = Task::select('status', DB::raw('COUNT(*) as total'))
            ->whereBetween('updated_at', [$currentStart, $currentEnd])
            ->groupBy('status')
            ->pluck('total', 'status');

        $tasksCompleted = $taskStatusCounts['completed'] ?? 0;
        $tasksPending = $taskStatusCounts['pending'] ?? 0;
        $tasksPublished = $taskStatusCounts['published'] ?? 0;
        $tasksInactive = $taskStatusCounts['inactive'] ?? 0;

        [$labels, $userGrowth] = $this->buildTimeSeriesCounts(
            User::query(),
            'created_at',
            $currentStart,
            $currentEnd
        );

        [$taskCompletionLabels, $taskCompletionTrend] = $this->buildTimeSeriesCounts(
            TaskAssignment::query()->where('status', 'completed')->whereNotNull('completed_at'),
            'completed_at',
            $currentStart,
            $currentEnd
        );

        // Business analytics (all-time for now)
    $totalAssignments = TaskAssignment::count();
    $completedAssignments = TaskAssignment::where('status', 'completed')->count();
    $taskCompletionRate = $totalAssignments > 0 ? round(($completedAssignments / $totalAssignments) * 100, 1) : 0;
    
        $activeVolunteers = User::whereHas('taskAssignments', function ($query) {
        $query->where('status', 'completed');
    })->count();
    
        $allTimeUsers = User::count();
    $usersWithTasks = User::whereHas('taskAssignments')->count();
        $engagementRate = $allTimeUsers > 0 ? round(($usersWithTasks / $allTimeUsers) * 100, 1) : 0;

    $taskAssignmentTable = (new TaskAssignment())->getTable();
    $taskTable = (new Task())->getTable();
    $totalPointsAwarded = TaskAssignment::where($taskAssignmentTable . '.status', 'completed')
        ->join($taskTable, $taskAssignmentTable . '.taskId', '=', $taskTable . '.taskId')
        ->sum($taskTable . '.points_awarded') ?? 0;
    
    $tasksWithAssignments = Task::whereHas('assignments')->count();
    $avgTaskCompletionRate = $tasksWithAssignments > 0 ? round(($completedAssignments / $tasksWithAssignments), 1) : 0;

    $taskChainNominations = TapNomination::accepted()->forDailyTasks()->count();
    $totalNominations = TapNomination::count();
    $chainEngagementRate = $totalNominations > 0 ? round(($taskChainNominations / $totalNominations) * 100, 1) : 0;

    $topPerformers = User::where('status', 'active')
        ->orderBy('points', 'desc')
        ->limit(5)
        ->get(['userId', 'firstName', 'lastName', 'email', 'points']);

        $periodOptions = [
            'last_7_days' => 'Last 7 days',
            'last_30_days' => 'Last 30 days',
            'last_90_days' => 'Last 90 days',
            'this_month' => 'This month',
            'this_quarter' => 'This quarter',
            'this_year' => 'This year',
            'custom' => 'Custom range',
        ];

        $rangeSummary = [
            'label' => $periodLabel,
            'current_start' => $currentStart,
            'current_end' => $currentEnd,
            'previous_start' => $previousStart,
            'previous_end' => $previousEnd,
        ];

        $filterStartDate = $request->input('start_date', $currentStart->toDateString());
        $filterEndDate = $request->input('end_date', $currentEnd->toDateString());

        $selectedSegment = $request->input('segment', 'role');
        $segmentationData = $this->buildSegmentationData($selectedSegment, $currentStart, $currentEnd);
        $taskTypeEngagement = $this->buildTaskTypeEngagement($currentStart, $currentEnd);

        $retentionMetrics = $this->buildRetentionMetrics(Carbon::now());
        $incidentInsights = $this->buildIncidentInsights($currentStart, $currentEnd);

        $alerts = $this->buildAlerts(
            $totalUsersDelta,
            $totalTasksDelta,
            $totalIncidentsDelta,
            $taskCompletionRate,
            $incidentInsights,
            $engagementRate,
            $chainEngagementRate
        );

    return view('admin-dashboard', compact(
        'totalUsers',
        'totalTasks',
        'totalIncidents',
        'tasksCompleted',
        'tasksPending',
            'tasksPublished',
            'tasksInactive',
        'taskCompletionRate',
        'activeVolunteers',
        'engagementRate',
        'usersWithTasks',
        'totalPointsAwarded',
        'avgTaskCompletionRate',
        'completedAssignments',
        'taskChainNominations',
        'chainEngagementRate',
        'totalNominations',
        'topPerformers',
        'labels',
        'userGrowth',
        'taskCompletionTrend',
            'taskCompletionLabels',
            'periodOptions',
            'selectedPeriod',
            'rangeSummary',
            'filterStartDate',
            'filterEndDate',
            'totalUsersPrevious',
            'totalUsersDelta',
            'totalTasksPrevious',
            'totalTasksDelta',
            'totalIncidentsPrevious',
            'totalIncidentsDelta',
            'selectedSegment',
            'segmentationData',
            'taskTypeEngagement',
            'retentionMetrics',
            'incidentInsights',
            'alerts'
        ));
    }

    public function chartDetails(Request $request, string $chart)
    {
        [$currentStart, $currentEnd, $selectedPeriod, $periodLabel] = $this->resolveDateRange($request);
        $selectedSegment = $request->input('segment', 'task_location');
        $label = $request->input('label');

        $pageTitle = '';
        $description = '';
        $columns = [];
        $rows = [];
        $breadcrumbRoute = route('admin.dashboard', [
            'period' => $selectedPeriod,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'segment' => $selectedSegment,
        ]);

        switch ($chart) {
            case 'user-growth':
                $pageTitle = 'User Growth Details';
                $description = 'List of newly registered users for the selected period.';
                $columns = [
                    ['key' => 'name', 'label' => 'Volunteer'],
                    ['key' => 'email', 'label' => 'Email'],
                    ['key' => 'created_at', 'label' => 'Joined'],
                ];

                $details = $this->buildUserGrowthDetails($currentStart, $currentEnd);

                if ($label && isset($details[$label])) {
                    $rows = $details[$label];
                } else {
                    foreach ($details as $set) {
                        $rows = array_merge($rows, $set);
                    }
                }

                break;

            case 'task-completion':
                $pageTitle = 'Task Completion Details';
                $description = 'Completed task assignments captured within the selected period.';
                $columns = [
                    ['key' => 'task', 'label' => 'Task'],
                    ['key' => 'assignee', 'label' => 'Assignee'],
                    ['key' => 'task_type', 'label' => 'Type'],
                    ['key' => 'completed_at', 'label' => 'Completed'],
                ];

                $details = $this->buildTaskCompletionDetails($currentStart, $currentEnd);

                if ($label && isset($details[$label])) {
                    $rows = $details[$label];
                } else {
                    foreach ($details as $set) {
                        $rows = array_merge($rows, $set);
                    }
                }

                break;

            case 'segmentation':
                $pageTitle = 'Segment Breakdown';
                $description = 'Records contributing to the selected segment.';

                $segmentation = $this->buildSegmentationData($selectedSegment, $currentStart, $currentEnd);
                $segmentLabel = $label ?: array_key_first($segmentation['details'] ?? []);

                if ($selectedSegment === 'reward_sponsor') {
                    $columns = [
                        ['key' => 'label', 'label' => 'Reward'],
                        ['key' => 'points', 'label' => 'Points'],
                        ['key' => 'quantity', 'label' => 'Quantity'],
                        ['key' => 'status', 'label' => 'Status'],
                        ['key' => 'timestamp', 'label' => 'Updated'],
                    ];
                } else {
                    $columns = [
                        ['key' => 'label', 'label' => 'Task'],
                        ['key' => 'type', 'label' => 'Type'],
                        ['key' => 'status', 'label' => 'Status'],
                        ['key' => 'timestamp', 'label' => 'Created'],
                    ];
                }

                if (
                    isset($segmentation['details'])
                    && $segmentLabel
                    && array_key_exists($segmentLabel, $segmentation['details'])
                ) {
                    $rows = $segmentation['details'][$segmentLabel];
                    $label = $segmentLabel;
                } else {
                    $rows = [];
                }

                break;

            case 'task-status':
                $pageTitle = 'Task Status Distribution';
                $description = 'Tasks grouped by current status.';
                $columns = [
                    ['key' => 'title', 'label' => 'Task'],
                    ['key' => 'task_type', 'label' => 'Type'],
                    ['key' => 'status', 'label' => 'Status'],
                    ['key' => 'updated_at', 'label' => 'Last Updated'],
                ];

                $statusMappings = [
                    'Completed' => 'completed',
                    'Pending' => 'pending',
                    'Published' => 'published',
                    'Inactive' => 'inactive',
                ];

                $statusFilter = null;
                if ($label && isset($statusMappings[$label])) {
                    $statusFilter = $statusMappings[$label];
                }

                $query = Task::select('title', 'task_type', 'status', 'updated_at')
                    ->whereBetween('updated_at', [$currentStart, $currentEnd])
                    ->orderBy('updated_at', 'desc');

                if ($statusFilter) {
                    $query->where('status', $statusFilter);
                }

                $rows = $query->get()
                    ->map(function ($task) {
                        return [
                            'title' => $task->title,
                            'task_type' => $task->task_type ?? 'Unspecified',
                            'status' => $task->status ? ucfirst(str_replace('_', ' ', $task->status)) : 'Unspecified',
                            'updated_at' => optional($task->updated_at)->format('M d, Y H:i'),
                        ];
                    })
                    ->toArray();

                break;

            default:
                abort(404);
        }

        return view('admin.dashboard.chart-details', [
            'pageTitle' => $pageTitle,
            'description' => $description,
            'rows' => $rows,
            'columns' => $columns,
            'selectedPeriod' => $selectedPeriod,
            'periodLabel' => $periodLabel,
            'currentStart' => $currentStart,
            'currentEnd' => $currentEnd,
            'breadcrumbRoute' => $breadcrumbRoute,
            'chart' => $chart,
            'label' => $label,
            'selectedSegment' => $selectedSegment,
        ]);
    }

    protected function resolveDateRange(Request $request): array
    {
        $period = $request->input('period', 'last_30_days');
        $now = Carbon::now();

        switch ($period) {
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

    protected function buildComparisonRange(Carbon $start, Carbon $end): array
    {
        $diff = $start->diffInDays($end) + 1;
        $previousEnd = $start->copy()->subDay()->endOfDay();
        $previousStart = $previousEnd->copy()->subDays($diff - 1)->startOfDay();

        return [$previousStart, $previousEnd];
    }

    protected function calculateDelta(int $current, int $previous): array
    {
        $delta = $current - $previous;
        $deltaPercent = $previous > 0 ? round(($delta / $previous) * 100, 1) : null;

        return [
            'value' => $delta,
            'percent' => $deltaPercent,
        ];
    }

    protected function buildTimeSeriesCounts($query, string $dateColumn, Carbon $start, Carbon $end): array
    {
        $rangeStart = $start->copy();
        $rangeEnd = $end->copy();
        $totalDays = $rangeStart->diffInDays($rangeEnd) + 1;
        $grouping = $totalDays > 62 ? 'month' : 'day';

        $columnExpression = $grouping === 'month'
            ? "DATE_FORMAT({$dateColumn}, '%Y-%m')"
            : "DATE({$dateColumn})";

        $results = (clone $query)
            ->selectRaw("{$columnExpression} as period_key, COUNT(*) as total")
            ->whereBetween($dateColumn, [$rangeStart, $rangeEnd])
            ->groupBy('period_key')
            ->orderBy('period_key')
            ->pluck('total', 'period_key')
            ->toArray();

        $labels = [];
        $data = [];

        $seriesStart = $grouping === 'month'
            ? $rangeStart->copy()->startOfMonth()
            : $rangeStart->copy();

        $seriesEnd = $grouping === 'month'
            ? $rangeEnd->copy()->endOfMonth()
            : $rangeEnd->copy();

        $period = CarbonPeriod::create(
            $seriesStart,
            $grouping === 'month' ? '1 month' : '1 day',
            $seriesEnd
        );

        foreach ($period as $periodDate) {
            $periodKey = $grouping === 'month'
                ? $periodDate->format('Y-%m')
                : $periodDate->format('Y-m-d');

            $labels[] = $grouping === 'month'
                ? $periodDate->format('M Y')
                : $periodDate->format('M d');

            $data[] = $results[$periodKey] ?? 0;
        }

        return [$labels, $data];
}


    protected function buildSegmentationData(string $segment, Carbon $start, Carbon $end): array
    {
        $allowedSegments = ['task_location', 'reward_sponsor'];
        if (!in_array($segment, $allowedSegments, true)) {
            $segment = 'task_location';
        }

        $result = [
            'segment' => $segment,
            'title' => '',
            'description' => '',
            'labels' => [],
            'values' => [],
            'table' => [],
            'details' => [],
        ];

        switch ($segment) {
            case 'task_location':
                $result['title'] = 'Tasks by Location';
                $result['description'] = 'Task creation volume grouped by location for the selected period.';

                $rows = Task::selectRaw("COALESCE(NULLIF(TRIM(location), ''), 'Unspecified') AS segment_label, COUNT(*) AS total")
                    ->whereBetween('created_at', [$start, $end])
                    ->groupBy('segment_label')
                    ->orderByDesc('total')
                    ->limit(12)
                    ->get();

                $total = $rows->sum('total');

                $result['labels'] = $rows->pluck('segment_label')->toArray();
                $result['values'] = $rows->pluck('total')->toArray();
                $result['table'] = $rows->map(function ($row) use ($total) {
                    $share = $total > 0 ? round(($row->total / $total) * 100, 1) : 0;
                    return [
                        'label' => $row->segment_label,
                        'count' => $row->total,
                        'share' => $share,
                    ];
                })->toArray();

                $tasks = Task::select('title', 'task_type', 'status', 'location', 'created_at')
                    ->whereBetween('created_at', [$start, $end])
                    ->orderBy('created_at', 'desc')
                    ->get();

                $result['details'] = $tasks->groupBy(function ($task) {
                    return $task->location && trim($task->location) !== '' ? $task->location : 'Unspecified';
                })->map(function ($group) {
                    return $group->take(25)->map(function ($task) {
                        return [
                            'label' => $task->title,
                            'type' => $task->task_type,
                            'status' => $task->status,
                            'timestamp' => optional($task->created_at)->format('M d, Y H:i'),
                        ];
                    })->values();
                })->toArray();

                break;

            case 'reward_sponsor':
                $result['title'] = 'Rewards by Sponsor';
                $result['description'] = 'Active rewards grouped by sponsor for the selected period.';

                $baseQuery = Reward::query();
                $baseQuery->where(function ($query) use ($start, $end) {
                    $query->whereBetween('created_date', [$start, $end])
                        ->orWhereBetween('created_at', [$start, $end])
                        ->orWhereBetween('last_update_date', [$start, $end]);
                });

                $rows = (clone $baseQuery)
                    ->selectRaw("COALESCE(NULLIF(TRIM(sponsor_name), ''), 'Unspecified') AS segment_label, COUNT(*) AS total")
                    ->groupBy('segment_label')
                    ->orderByDesc('total')
                    ->limit(12)
                    ->get();

                $total = $rows->sum('total');

                $result['labels'] = $rows->pluck('segment_label')->toArray();
                $result['values'] = $rows->pluck('total')->toArray();
                $result['table'] = $rows->map(function ($row) use ($total) {
                    $share = $total > 0 ? round(($row->total / $total) * 100, 1) : 0;
                    return [
                        'label' => $row->segment_label,
                        'count' => $row->total,
                        'share' => $share,
                    ];
                })->toArray();

                $rewards = (clone $baseQuery)
                    ->select('sponsor_name', 'reward_name', 'points_cost', 'QTY', 'status', 'created_date', 'last_update_date')
                    ->orderByRaw('COALESCE(last_update_date, created_date, created_at) DESC')
                    ->get();

                $result['details'] = $rewards->groupBy(function ($reward) {
                    return $reward->sponsor_name && trim($reward->sponsor_name) !== '' ? $reward->sponsor_name : 'Unspecified';
                })->map(function ($group) {
                    return $group->take(25)->map(function ($reward) {
                        return [
                            'label' => $reward->reward_name,
                            'points' => $reward->points_cost,
                            'quantity' => $reward->QTY,
                            'status' => $reward->status,
                            'timestamp' => optional($reward->last_update_date ?? $reward->created_date)->format('M d, Y'),
                        ];
                    })->values();
                })->toArray();

                break;

            default:
                $result['title'] = 'Tasks by Location';
                $result['description'] = 'Task creation volume grouped by location for the selected period.';

                $rows = Task::selectRaw("COALESCE(NULLIF(TRIM(location), ''), 'Unspecified') AS segment_label, COUNT(*) AS total")
                    ->whereBetween('created_at', [$start, $end])
                    ->groupBy('segment_label')
                    ->orderByDesc('total')
                    ->limit(12)
                    ->get();

                $total = $rows->sum('total');

                $result['labels'] = $rows->pluck('segment_label')->toArray();
                $result['values'] = $rows->pluck('total')->toArray();
                $result['table'] = $rows->map(function ($row) use ($total) {
                    $share = $total > 0 ? round(($row->total / $total) * 100, 1) : 0;
                    return [
                        'label' => $row->segment_label,
                        'count' => $row->total,
                        'share' => $share,
                    ];
                })->toArray();

                $tasks = Task::select('title', 'task_type', 'status', 'location', 'created_at')
                    ->whereBetween('created_at', [$start, $end])
                    ->orderBy('created_at', 'desc')
                    ->get();

                $result['details'] = $tasks->groupBy(function ($task) {
                    return $task->location && trim($task->location) !== '' ? $task->location : 'Unspecified';
                })->map(function ($group) {
                    return $group->take(25)->map(function ($task) {
                        return [
                            'label' => $task->title,
                            'type' => $task->task_type,
                            'status' => $task->status,
                            'timestamp' => optional($task->created_at)->format('M d, Y H:i'),
                        ];
                    })->values();
                })->toArray();

                break;
        }

        return $result;
    }

    protected function buildTaskTypeEngagement(Carbon $start, Carbon $end): array
    {
        $taskTypes = Task::select('task_type')
            ->whereBetween('created_at', [$start, $end])
            ->distinct()
            ->pluck('task_type')
            ->filter()
            ->toArray();

        if (empty($taskTypes)) {
            $taskTypes = Task::select('task_type')
                ->distinct()
                ->pluck('task_type')
                ->filter()
                ->toArray();
        }

        $taskTypes = array_values(array_unique(array_filter($taskTypes)));

        $labels = [];
        $assignmentsCounts = [];
        $completedCounts = [];
        $completionRates = [];

        foreach ($taskTypes as $type) {
            $assignmentsQuery = TaskAssignment::whereHas('task', function ($query) use ($type) {
                $query->where('task_type', $type);
            })->whereBetween('assigned_at', [$start, $end]);

            $totalAssignments = $assignmentsQuery->count();

            $completedAssignments = (clone $assignmentsQuery)
                ->where('status', 'completed')
                ->count();

            $labels[] = ucfirst(str_replace('_', ' ', $type));
            $assignmentsCounts[] = $totalAssignments;
            $completedCounts[] = $completedAssignments;
            $completionRates[] = $totalAssignments > 0 ? round(($completedAssignments / $totalAssignments) * 100, 1) : 0;
        }

        return [
            'labels' => $labels,
            'assignments' => $assignmentsCounts,
            'completed' => $completedCounts,
            'completion_rates' => $completionRates,
        ];
    }

    protected function buildUserGrowthDetails(Carbon $start, Carbon $end): array
    {
        $rangeStart = $start->copy();
        $rangeEnd = $end->copy();
        $totalDays = $rangeStart->diffInDays($rangeEnd) + 1;
        $grouping = $totalDays > 62 ? 'month' : 'day';

        $seriesStart = $grouping === 'month'
            ? $rangeStart->copy()->startOfMonth()
            : $rangeStart->copy()->startOfDay();

        $seriesEnd = $grouping === 'month'
            ? $rangeEnd->copy()->endOfMonth()
            : $rangeEnd->copy()->endOfDay();

        $period = CarbonPeriod::create(
            $seriesStart,
            $grouping === 'month' ? '1 month' : '1 day',
            $seriesEnd
        );

        $details = [];

        foreach ($period as $point) {
            if ($grouping === 'month') {
                $periodStart = $point->copy()->startOfMonth();
                $periodEnd = $point->copy()->endOfMonth();
                $label = $point->format('M Y');
            } else {
                $periodStart = $point->copy()->startOfDay();
                $periodEnd = $point->copy()->endOfDay();
                $label = $point->format('M d');
            }

            $users = User::select('firstName', 'middleName', 'lastName', 'email', 'created_at')
                ->whereBetween('created_at', [$periodStart, $periodEnd])
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->map(function ($user) {
                    $nameParts = array_filter([$user->firstName, $user->middleName, $user->lastName]);
                    $fullName = implode(' ', $nameParts);

                    return [
                        'name' => $fullName,
                        'email' => $user->email,
                        'created_at' => optional($user->created_at)->format('M d, Y H:i'),
                    ];
                })
                ->toArray();

            $details[$label] = $users;
        }

        return $details;
    }

    protected function buildTaskCompletionDetails(Carbon $start, Carbon $end): array
    {
        $rangeStart = $start->copy();
        $rangeEnd = $end->copy();
        $totalDays = $rangeStart->diffInDays($rangeEnd) + 1;
        $grouping = $totalDays > 62 ? 'month' : 'day';

        $seriesStart = $grouping === 'month'
            ? $rangeStart->copy()->startOfMonth()
            : $rangeStart->copy()->startOfDay();

        $seriesEnd = $grouping === 'month'
            ? $rangeEnd->copy()->endOfMonth()
            : $rangeEnd->copy()->endOfDay();

        $period = CarbonPeriod::create(
            $seriesStart,
            $grouping === 'month' ? '1 month' : '1 day',
            $seriesEnd
        );

        $details = [];

        foreach ($period as $point) {
            if ($grouping === 'month') {
                $periodStart = $point->copy()->startOfMonth();
                $periodEnd = $point->copy()->endOfMonth();
                $label = $point->format('M Y');
            } else {
                $periodStart = $point->copy()->startOfDay();
                $periodEnd = $point->copy()->endOfDay();
                $label = $point->format('M d');
            }

            $assignments = TaskAssignment::with([
                'task:taskId,title,task_type',
                'user:userId,firstName,middleName,lastName',
            ])
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$periodStart, $periodEnd])
                ->orderBy('completed_at', 'desc')
                ->limit(50)
                ->get()
                ->map(function ($assignment) {
                    $user = $assignment->user;
                    $task = $assignment->task;

                    $nameParts = $user ? array_filter([$user->firstName, $user->middleName, $user->lastName]) : [];
                    $fullName = $user ? implode(' ', $nameParts) : 'Unknown User';
                    $taskTitle = $task ? $task->title : 'Task #' . $assignment->taskId;

                    return [
                        'task' => $taskTitle,
                        'assignee' => $fullName,
                        'task_type' => $task->task_type ?? 'Unspecified',
                        'completed_at' => optional($assignment->completed_at)->format('M d, Y H:i'),
                    ];
                })
                ->toArray();

            $details[$label] = $assignments;
        }

        return $details;
    }

    protected function buildRetentionMetrics(Carbon $referencePoint): array
    {
        $windows = [
            30 => '30-Day Retention',
            60 => '60-Day Retention',
            90 => '90-Day Retention',
        ];

        $metrics = [];

        foreach ($windows as $days => $label) {
            $cohortStart = $referencePoint->copy()->subDays($days)->startOfDay();
            $cohortEnd = $referencePoint->copy()->endOfDay();
            $activityThreshold = $referencePoint->copy()->subDays($days);

            $newUsers = User::whereBetween('created_at', [$cohortStart, $cohortEnd])->count();

            $retainedUsers = User::whereBetween('created_at', [$cohortStart, $cohortEnd])
                ->whereHas('taskAssignments', function ($query) use ($activityThreshold) {
                    $query->where('status', 'completed')
                        ->where('completed_at', '>=', $activityThreshold);
                })
                ->count();

            $metrics[] = [
                'label' => $label,
                'cohort_size' => $newUsers,
                'retained' => $retainedUsers,
                'retention_rate' => $newUsers > 0 ? round(($retainedUsers / $newUsers) * 100, 1) : 0,
                'lookback_days' => $days,
            ];
        }

        return $metrics;
    }

    protected function buildIncidentInsights(Carbon $start, Carbon $end): array
    {
        $resolvedReports = UserIncidentReport::whereNotNull('moderation_date')
            ->whereBetween('report_date', [$start, $end])
            ->get(['reportId', 'incident_type', 'status', 'report_date', 'moderation_date', 'action_taken']);

        $resolutionDurations = $resolvedReports->map(function ($report) {
            if (!$report->moderation_date || !$report->report_date) {
                return null;
            }
            return $report->moderation_date->diffInHours($report->report_date);
        })->filter();

        $averageResolutionHours = $resolutionDurations->isNotEmpty()
            ? round($resolutionDurations->avg(), 1)
            : null;

        $medianResolutionHours = $resolutionDurations->isNotEmpty()
            ? $resolutionDurations->sort()->values()->median()
            : null;

        $openIncidents = UserIncidentReport::whereIn('status', ['pending', 'under_review'])->count();

        $overdueIncidents = UserIncidentReport::whereIn('status', ['pending', 'under_review'])
            ->where('report_date', '<=', Carbon::now()->subDays(7))
            ->count();

        $recentResolutions = $resolvedReports
            ->sortByDesc('moderation_date')
            ->take(8)
            ->map(function ($report) {
                return [
                    'incident_type' => $report->incident_type,
                    'action_taken' => $report->action_taken,
                    'resolved_at' => optional($report->moderation_date)->format('M d, Y'),
                    'resolution_hours' => $report->moderation_date && $report->report_date
                        ? $report->moderation_date->diffInHours($report->report_date)
                        : null,
                ];
            })
            ->values()
            ->toArray();

        return [
            'resolved_count' => $resolvedReports->count(),
            'average_resolution_hours' => $averageResolutionHours,
            'median_resolution_hours' => $medianResolutionHours,
            'open_incidents' => $openIncidents,
            'overdue_incidents' => $overdueIncidents,
            'recent_resolutions' => $recentResolutions,
        ];
    }

    protected function buildAlerts(
        array $usersDelta,
        array $tasksDelta,
        array $incidentsDelta,
        float $taskCompletionRate,
        array $incidentInsights,
        float $engagementRate,
        float $chainEngagementRate
    ): array {
        $alerts = [];

        if (!is_null($usersDelta['percent']) && $usersDelta['percent'] <= -15) {
            $alerts[] = [
                'level' => 'critical',
                'message' => 'New user registrations dropped more than 15% compared to the previous period.',
            ];
        }

        if (!is_null($tasksDelta['percent']) && $tasksDelta['percent'] <= -20) {
            $alerts[] = [
                'level' => 'warning',
                'message' => 'Task creation is down more than 20%. Review upcoming campaigns or creator activity.',
            ];
        }

        if ($taskCompletionRate < 50) {
            $alerts[] = [
                'level' => 'warning',
                'message' => 'Task completion rate is below 50%. Consider coaching volunteers or adjusting task difficulty.',
            ];
        }

        if ($incidentInsights['overdue_incidents'] > 0) {
            $alerts[] = [
                'level' => 'critical',
                'message' => "{$incidentInsights['overdue_incidents']} incident report(s) have been open for more than 7 days.",
            ];
        }

        if (!is_null($incidentsDelta['percent']) && $incidentsDelta['percent'] >= 25) {
            $alerts[] = [
                'level' => 'warning',
                'message' => 'Incident reports increased by more than 25% this period.',
            ];
        }

        if ($engagementRate < 40) {
            $alerts[] = [
                'level' => 'info',
                'message' => 'Overall engagement is below 40%. Consider outreach to dormant volunteers.',
            ];
        }

        if ($chainEngagementRate < 20) {
            $alerts[] = [
                'level' => 'info',
                'message' => 'Task chain engagement is below 20%. Highlight successful chains to encourage participation.',
            ];
        }

        if (empty($alerts)) {
            $alerts[] = [
                'level' => 'success',
                'message' => 'All key metrics are within healthy ranges for this period.',
            ];
        }

        return $alerts;
}


    private function userDashboard()
    {
        $user = Auth::user();

        // Exclude inactive/deactivated tasks from all queries
        $userTasks = $user->assignedTasks()
            ->where('tasks.status', '!=', 'inactive')
            ->get();
        $ongoingTasks = $user->ongoingTasks()
            ->where('tasks.status', '!=', 'inactive')
            ->get();
        $completedTasks = $user->completedTasks()
            ->where('tasks.status', '!=', 'inactive')
            ->get();

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
        $completedTasksCount = $user->completedTasks()
            ->where('tasks.status', '!=', 'inactive')
            ->count();
        $claimedRewardsCount = 0;
        
        // Preserve filter inputs for the view
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $taskType = $request->get('task_type', 'all');

        $query = $user->completedTasks()
            ->where('tasks.status', '!=', 'inactive') // Exclude inactive/deactivated tasks
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
