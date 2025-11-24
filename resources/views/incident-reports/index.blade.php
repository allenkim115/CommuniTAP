<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Incident Reports') }}
        </h2>
    </x-slot>

    @php
        $statusClasses = [
            'pending' => 'badge-soft badge-soft-amber',
            'under_review' => 'badge-soft badge-soft-teal',
            'resolved' => 'badge-soft badge-soft-teal',
            'dismissed' => 'badge-soft badge-soft-slate',
        ];

        $typeClasses = [
            'non_participation' => 'badge-soft badge-soft-amber',
            'abuse' => 'badge-soft badge-soft-orange',
            'spam' => 'badge-soft badge-soft-teal',
            'inappropriate_content' => 'badge-soft badge-soft-orange',
            'harassment' => 'badge-soft badge-soft-orange',
        ];

        $statusCounts = [
            'pending' => 0,
            'under_review' => 0,
            'resolved' => 0,
            'dismissed' => 0,
        ];

        foreach ($reports as $statusReport) {
            $statusCounts[$statusReport->status] = ($statusCounts[$statusReport->status] ?? 0) + 1;
        }
    @endphp

    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="card-surface p-6 lg:p-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="section-heading">Incident oversight</p>
                        <h3 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">Your recent incident activity</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Track reports you've submitted and follow up on moderation progress.
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('incident-reports.create') }}" class="btn-brand">
                            Report a user
                        </a>
                        <a href="{{ route('incident-reports.index') }}#incident-report-list" class="btn-muted">
                            Jump to list
                        </a>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($statusCounts as $status => $count)
                        @php
                            $labels = [
                                'pending' => 'Pending review',
                                'under_review' => 'Under review',
                                'resolved' => 'Resolved',
                                'dismissed' => 'Dismissed'
                            ];
                            $chipClass = $statusClasses[$status] ?? 'badge-soft badge-soft-slate';
                        @endphp
                        <div class="stat-card">
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $labels[$status] }}</span>
                                <span class="{{ $chipClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                            </div>
                            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ $count }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">On this page</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-surface p-6 lg:p-8 space-y-6">
                <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <div class="w-full md:max-w-md">
                        <label for="report-search" class="section-heading">Quick search</label>
                        <div class="relative mt-2">
                            <input
                                id="report-search"
                                type="search"
                                placeholder="Search by user, incident type, or task"
                                class="w-full rounded-2xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 focus:border-brand-teal focus:ring-brand-teal"
                            >
                            <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-400">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="w-full md:w-56">
                        <label for="report-status-filter" class="section-heading">Filter by status</label>
                        <select
                            id="report-status-filter"
                            class="mt-2 w-full rounded-2xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 focus:border-brand-teal focus:ring-brand-teal"
                        >
                            <option value="all">All statuses</option>
                            <option value="pending">Pending</option>
                            <option value="under_review">Under review</option>
                            <option value="resolved">Resolved</option>
                            <option value="dismissed">Dismissed</option>
                        </select>
                    </div>
                </div>

                @if($reports->count() > 0)
                    <div id="incident-report-list" class="space-y-4">
                        @foreach($reports as $report)
                            @php
                                $searchText = Str::lower(collect([
                                    $report->reportedUser->fullName,
                                    $report->reportedUser->email,
                                    str_replace('_', ' ', $report->incident_type),
                                    optional($report->task)->title,
                                    optional($report->task)->task_type,
                                ])->filter()->implode(' '));
                                $statusChip = $statusClasses[$report->status] ?? 'badge-soft badge-soft-slate';
                                $typeChip = $typeClasses[$report->incident_type] ?? 'badge-soft badge-soft-slate';

                                $currentUser = auth()->user();
                                $canViewTask = false;
                                if ($report->task && $currentUser) {
                                    $canViewTask =
                                        $currentUser->isAdmin() ||
                                        ($report->task->status === 'published') ||
                                        ($report->task->FK1_userId && $report->task->FK1_userId === $currentUser->userId) ||
                                        $report->task->isAssignedTo($currentUser->userId);
                                }
                            @endphp
                            <article
                                class="card-surface p-5 lg:p-6 transition hover:shadow-lg focus-visible:ring-2 focus-visible:ring-brand-teal cursor-pointer"
                                data-report-card
                                data-status="{{ $report->status }}"
                                data-search="{{ $searchText }}"
                                data-href="{{ route('incident-reports.show', $report) }}"
                                tabindex="0"
                                role="link"
                            >
                                <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                                    <div class="flex-1 space-y-4">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-peach/60 text-base font-semibold text-brand-orange-dark">
                                                    {{ strtoupper(substr($report->reportedUser->firstName, 0, 1)) }}{{ strtoupper(substr($report->reportedUser->lastName, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $report->reportedUser->fullName }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->reportedUser->email }}</p>
                                                </div>
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                <span class="{{ $typeChip }}">
                                                    {{ ucwords(str_replace('_', ' ', $report->incident_type)) }}
                                                </span>
                                                <span class="{{ $statusChip }}">
                                                    {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="grid gap-4 sm:grid-cols-2">
                                            <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
                                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Date reported</p>
                                                <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $report->report_date->format('M d, Y') }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $report->report_date->format('g:i A') }}</p>
                                            </div>
                                            <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
                                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Related task</p>
                                                @if($report->task)
                                                    <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ Str::limit($report->task->title, 40) }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mt-1">
                                                        {{ ucwords(str_replace('_', ' ', $report->task->task_type)) }}
                                                    </p>
                                                @else
                                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No task linked</p>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ Str::limit($report->description, 180) }}
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-start gap-4 md:items-end md:text-right">
                                        <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Progress</div>
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                            <span class="h-2 w-2 rounded-full bg-brand-teal"></span>
                                            {{ $report->status === 'resolved' ? 'Completed' : 'Awaiting moderation' }}
                                        </div>
                                        <div class="text-xs font-medium uppercase tracking-wide text-brand-teal dark:text-brand-peach">Click card to view details</div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div id="report-empty-state" class="hidden rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/60 p-8 text-center">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-white">No reports match your filters</h4>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Try clearing the search or selecting a different status.</p>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                        {{ $reports->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/60 px-6 py-16 text-center">
                        <svg class="h-16 w-16 text-brand-peach" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2m14 0v2a2 2 0 01-2 2h-.001a2 2 0 01-2-2v-2M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2m-9 4h10m-5-5v10" />
                        </svg>
                        <h3 class="mt-6 text-lg font-semibold text-gray-900 dark:text-white">No incident reports yet</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            When you submit an incident report, you'll see the moderation journey right here.
                        </p>
                        <a href="{{ route('incident-reports.create') }}" class="mt-6 btn-brand">
                            Create your first report
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const searchInput = document.getElementById('report-search');
                const statusFilter = document.getElementById('report-status-filter');
                const cards = document.querySelectorAll('[data-report-card]');
                const emptyState = document.getElementById('report-empty-state');

                const applyFilters = () => {
                    const searchTerm = (searchInput?.value || '').trim().toLowerCase();
                    const statusValue = statusFilter?.value || 'all';
                    let visibleCount = 0;

                    cards.forEach((card) => {
                        const matchesSearch = !searchTerm || (card.dataset.search || '').includes(searchTerm);
                        const matchesStatus = statusValue === 'all' || card.dataset.status === statusValue;
                        const isVisible = matchesSearch && matchesStatus;

                        card.classList.toggle('hidden', !isVisible);
                        if (isVisible) {
                            visibleCount += 1;
                        }
                    });

                    if (emptyState) {
                        emptyState.classList.toggle('hidden', visibleCount !== 0);
                    }
                };

                searchInput?.addEventListener('input', applyFilters);
                statusFilter?.addEventListener('change', applyFilters);

                cards.forEach((card) => {
                    const navigate = () => {
                        const href = card.dataset.href;
                        if (href) {
                            window.location.assign(href);
                        }
                    };

                    card.addEventListener('click', (event) => {
                        if ((event.target instanceof Element) && event.target.closest('button, a')) {
                            return;
                        }
                        navigate();
                    });

                    card.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') {
                            event.preventDefault();
                            navigate();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
