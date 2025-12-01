<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Incident Reports Management') }}
        </h2>
    </x-slot>

    @php
        $summaryCards = [
            [
                'label' => 'Total Reports',
                'value' => $stats['total'],
                'context' => 'All incident submissions to date',
                'icon' => 'fas fa-folder-open',
                'accent' => 'text-slate-600 bg-slate-100'
            ],
            [
                'label' => 'Pending Review',
                'value' => $stats['pending'],
                'context' => 'Awaiting moderator triage',
                'icon' => 'fas fa-clock',
                'accent' => 'text-brand-orange-dark bg-brand-peach/60'
            ],
            [
                'label' => 'Under Review',
                'value' => $stats['under_review'],
                'context' => 'Actively being investigated',
                'icon' => 'fas fa-eye',
                'accent' => 'text-brand-teal-dark bg-brand-teal/10'
            ],
            [
                'label' => 'Resolved',
                'value' => $stats['resolved'],
                'context' => 'Successfully actioned',
                'icon' => 'fas fa-circle-check',
                'accent' => 'text-brand-teal-dark bg-brand-teal/10'
            ],
            [
                'label' => 'Dismissed',
                'value' => $stats['dismissed'],
                'context' => 'No action required',
                'icon' => 'fas fa-ban',
                'accent' => 'text-slate-600 bg-slate-100'
            ],
        ];

        $statusStyles = [
            'pending' => 'badge-soft badge-soft-orange',
            'under_review' => 'badge-soft badge-soft-teal',
            'resolved' => 'badge-soft badge-soft-teal',
            'dismissed' => 'badge-soft badge-soft-slate',
        ];

        $typeStyles = [
            'abuse' => 'badge-soft badge-soft-teal',
            'harassment' => 'badge-soft badge-soft-teal',
            'spam' => 'badge-soft badge-soft-teal',
            'inappropriate_content' => 'badge-soft badge-soft-teal',
            'non_participation' => 'badge-soft badge-soft-orange',
        ];
    @endphp

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($summaryCards as $card)
                    <div class="stat-card">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $card['value'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $card['context'] }}</p>
                            </div>
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl {{ $card['accent'] }}">
                                <i class="{{ $card['icon'] }} text-base"></i>
                            </span>
                        </div>
                    </div>
                @endforeach
                </div>

            <div class="card-surface">
                <div class="p-6 space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Filter reports</p>
                            <p class="text-sm text-gray-500">Refine the moderation queue. Filters update the table automatically.</p>
                        </div>
                        <a href="{{ route('admin.incident-reports.index') }}" class="btn-muted text-sm">
                            Reset filters
                        </a>
            </div>

                    <form id="filters-form" method="GET" action="{{ route('admin.incident-reports.index') }}" class="space-y-6" novalidate>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                            <label class="flex flex-col text-sm font-medium text-gray-700">
                                <span>Status</span>
                            <select id="status" name="status" class="mt-2 block w-full rounded-xl border-gray-200 focus:border-brand-teal focus:ring-brand-teal sm:text-sm" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit()">
                                    <option value="">All statuses</option>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="flex flex-col text-sm font-medium text-gray-700">
                                <span>Incident type</span>
                            <select id="incident_type" name="incident_type" class="mt-2 block w-full rounded-xl border-gray-200 focus:border-brand-teal focus:ring-brand-teal sm:text-sm" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit()">
                                    <option value="">All types</option>
                                    @foreach($incidentTypes as $key => $label)
                                        <option value="{{ $key }}" {{ request('incident_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
    
                            <label class="flex flex-col text-sm font-medium text-gray-700">
                                <span>From date</span>
                                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="mt-2 block w-full rounded-xl border-gray-200 focus:border-brand-teal focus:ring-brand-teal sm:text-sm" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit()">
                            </label>
    
                            <label class="flex flex-col text-sm font-medium text-gray-700">
                                <span>To date</span>
                                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="mt-2 block w-full rounded-xl border-gray-200 focus:border-brand-teal focus:ring-brand-teal sm:text-sm" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit()">
                            </label>
                            </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 items-end">
                            <label class="flex flex-col text-sm font-medium text-gray-700 lg:col-span-2">
                                <span>Search</span>
                                <div class="mt-2 relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <i class="fas fa-search text-xs"></i>
                                    </span>
                                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Reporter or reported name, email, task title…" class="block w-full rounded-xl border-gray-200 pl-10 focus:border-brand-teal focus:ring-brand-teal sm:text-sm">
                            </div>
                            </label>
                        </div>
                        <button type="submit" class="hidden" aria-hidden="true"></button>
                    </form>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="card-surface" id="reports-table-container">
                <div class="p-6" id="reports-content">
                    @if($reports->count() > 0)
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            {{ $reports->total() }} total reports
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Reporter
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Reported User
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($reports as $report)
                                        <tr class="hover:bg-brand-teal/5 cursor-pointer" onclick="window.location='{{ route('admin.incident-reports.show', $report) }}'">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <span class="inline-flex items-center gap-2">
                                                    <i class="fas fa-file-signature text-brand-teal-dark"></i>
                                                    #{{ $report->reportId }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $report->reporter->fullName }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $report->reporter->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $report->reportedUser->fullName }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $report->reportedUser->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $typeClass = $typeStyles[$report->incident_type] ?? 'badge-soft badge-soft-slate';
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $typeClass }}">
                                                    {{ ucwords(str_replace('_', ' ', $report->incident_type)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClass = $statusStyles[$report->status] ?? 'badge-soft badge-soft-slate';
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $statusClass }}">
                                                    {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $report->report_date->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $reports->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No incident reports found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No reports match your current filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function initIncidentFilters() {
            const run = () => {
            const filterForm = document.getElementById('filters-form');
            const searchInput = document.getElementById('search');
                const tableContainer = document.getElementById('reports-table-container');
                const reportsContent = document.getElementById('reports-content');

                if (!filterForm || !tableContainer || !reportsContent) {
                return;
            }

                const loadingClass = 'opacity-60';
                const disableClass = 'pointer-events-none';

                const setLoading = (state) => {
                    if (!tableContainer) {
                        return;
                    }
                    tableContainer.classList.toggle(loadingClass, state);
                    tableContainer.classList.toggle(disableClass, state);
                };

                const buildUrlWithParams = () => {
                    const url = new URL(filterForm.action, window.location.origin);
                    const formData = new FormData(filterForm);

                    formData.forEach((value, key) => {
                        if (value) {
                            url.searchParams.set(key, value);
                } else {
                            url.searchParams.delete(key);
                        }
                    });

                    return url;
                };

                const ajaxUpdate = (url) => {
                    setLoading(true);

                    return fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html',
                        },
                        credentials: 'same-origin',
                    })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const nextDoc = parser.parseFromString(html, 'text/html');
                            const nextContent = nextDoc.getElementById('reports-content');

                            if (nextContent) {
                                reportsContent.innerHTML = nextContent.innerHTML;
                                window.history.replaceState({}, '', url);
                            }
                        })
                        .catch(() => {
                            window.location.href = url;
                        })
                        .finally(() => setLoading(false));
                };

                const handleSubmit = (event) => {
                    event.preventDefault();
                    const url = buildUrlWithParams();

                    ajaxUpdate(url);
                };

                filterForm.addEventListener('submit', handleSubmit);

            const autoInputs = filterForm.querySelectorAll('select, input[type="date"]');
            autoInputs.forEach(input => {
                    input.addEventListener('change', () => filterForm.requestSubmit());
            });

            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => filterForm.requestSubmit(), 500);
                    });
                }

                reportsContent.addEventListener('click', (event) => {
                    const link = event.target.closest('a');
                    if (!link || !reportsContent.contains(link)) {
                        return;
                    }

                    const isPaginationLink = !!link.closest('.pagination');
                    if (!isPaginationLink) {
                        return;
                    }

                    event.preventDefault();
                    ajaxUpdate(new URL(link.href, window.location.origin));
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', run, { once: true });
            } else {
                run();
            }
        })();
    </script>
    @endpush
</x-admin-layout>
