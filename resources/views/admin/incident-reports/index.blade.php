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

    <div class="py-4 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3 lg:gap-4">
                @foreach($summaryCards as $card)
                    <div class="stat-card p-3 sm:p-4">
                        <div class="flex items-start justify-between gap-2 sm:gap-3 lg:gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] sm:text-xs lg:text-sm font-medium text-gray-500 leading-tight">{{ $card['label'] }}</p>
                                <p class="mt-1.5 sm:mt-2 text-xl sm:text-2xl font-semibold text-gray-900">{{ $card['value'] }}</p>
                                <p class="text-[9px] sm:text-[10px] lg:text-xs text-gray-500 mt-1 leading-tight">{{ $card['context'] }}</p>
                            </div>
                            <span class="inline-flex h-8 w-8 sm:h-9 sm:w-9 lg:h-10 lg:w-10 items-center justify-center rounded-xl flex-shrink-0 {{ $card['accent'] }}">
                                <i class="{{ $card['icon'] }} text-xs sm:text-sm lg:text-base"></i>
                            </span>
                        </div>
                    </div>
                @endforeach
                </div>

            <div class="card-surface">
                <div x-data="{ filtersOpen: false }" class="p-3 sm:p-6">
                    <button type="button" @click="filtersOpen = !filtersOpen" class="w-full sm:hidden flex items-center justify-between py-2.5 px-3 bg-gray-50 rounded-lg mb-0">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-filter text-base" style="color: #2B9D8D;"></i>
                            <span class="text-sm font-semibold text-gray-900">Filters</span>
                            @if(request('status') || request('incident_type') || request('date_from') || request('date_to') || request('search'))
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-brand-teal text-white text-xs font-bold">{{ 
                                    (request('status') ? 1 : 0) + 
                                    (request('incident_type') ? 1 : 0) + 
                                    (request('date_from') ? 1 : 0) + 
                                    (request('date_to') ? 1 : 0) + 
                                    (request('search') ? 1 : 0) 
                                }}</span>
                            @endif
                        </div>
                        <i class="fas fa-chevron-down transition-transform text-sm" :class="{'rotate-180': filtersOpen}"></i>
                    </button>
                    <div class="hidden sm:flex flex-wrap items-center justify-between gap-4 mb-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Filter reports</p>
                            <p class="text-xs text-gray-500">Refine the moderation queue. Filters update automatically.</p>
                        </div>
                        @if(request('status') || request('incident_type') || request('date_from') || request('date_to') || request('search'))
                        <a href="{{ route('admin.incident-reports.index') }}" class="btn-muted text-sm">
                            Reset filters
                        </a>
                        @endif
                    </div>

                    <form id="filters-form" method="GET" action="{{ route('admin.incident-reports.index') }}" class="space-y-3 sm:space-y-4 mt-3 sm:mt-0" x-show="filtersOpen || window.innerWidth >= 640" x-cloak novalidate>
                        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 lg:gap-6">
                            <label class="flex flex-col text-xs sm:text-sm font-medium text-gray-700">
                                <span class="hidden sm:inline mb-1.5">Status</span>
                            <select id="status" name="status" class="mt-0 sm:mt-2 block w-full rounded-lg sm:rounded-xl border border-gray-200 focus:border-brand-teal focus:ring-2 focus:ring-brand-teal text-sm px-3 py-2 min-h-[40px] sm:min-h-[44px] bg-white" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit()">
                                    <option value="">All statuses</option>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="flex flex-col text-xs sm:text-sm font-medium text-gray-700">
                                <span class="hidden sm:inline mb-1.5">Incident type</span>
                            <select id="incident_type" name="incident_type" class="mt-0 sm:mt-2 block w-full rounded-lg sm:rounded-xl border border-gray-200 focus:border-brand-teal focus:ring-2 focus:ring-brand-teal text-sm px-3 py-2 min-h-[40px] sm:min-h-[44px] bg-white" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit()">
                                    <option value="">All types</option>
                                    @foreach($incidentTypes as $key => $label)
                                        <option value="{{ $key }}" {{ request('incident_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
    
                            <label class="flex flex-col text-xs sm:text-sm font-medium text-gray-700">
                                <span class="hidden sm:inline mb-1.5">From date</span>
                                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="mt-0 sm:mt-2 block w-full rounded-lg sm:rounded-xl border border-gray-200 focus:border-brand-teal focus:ring-2 focus:ring-brand-teal text-sm px-3 py-2 min-h-[40px] sm:min-h-[44px]" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit()">
                            </label>
    
                            <label class="flex flex-col text-xs sm:text-sm font-medium text-gray-700">
                                <span class="hidden sm:inline mb-1.5">To date</span>
                                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="mt-0 sm:mt-2 block w-full rounded-lg sm:rounded-xl border border-gray-200 focus:border-brand-teal focus:ring-2 focus:ring-brand-teal text-sm px-3 py-2 min-h-[40px] sm:min-h-[44px]" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit()">
                            </label>
                            </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 sm:gap-4 lg:gap-6">
                            <label class="flex flex-col text-xs sm:text-sm font-medium text-gray-700 lg:col-span-2">
                                <span class="hidden sm:inline mb-1.5">Search</span>
                                <div class="mt-0 sm:mt-2 relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <i class="fas fa-search text-xs"></i>
                                    </span>
                                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search..." class="block w-full rounded-lg sm:rounded-xl border border-gray-200 pl-10 pr-4 py-2 focus:border-brand-teal focus:ring-2 focus:ring-brand-teal text-sm min-h-[40px] sm:min-h-[44px]">
                            </div>
                            </label>
                        </div>
                        @if(request('status') || request('incident_type') || request('date_from') || request('date_to') || request('search'))
                        <div class="flex justify-end pt-2 sm:hidden">
                            <a href="{{ route('admin.incident-reports.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors min-h-[36px]">
                                <i class="fas fa-times text-xs"></i>
                                Clear
                            </a>
                        </div>
                        @endif
                        <button type="submit" class="hidden" aria-hidden="true"></button>
                    </form>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="card-surface" id="reports-table-container">
                <div class="p-4 sm:p-6" id="reports-content">
                    @if($reports->count() > 0)
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-4">
                            {{ $reports->total() }} total reports
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="grid grid-cols-1 sm:hidden gap-4 mb-4" id="reports-mobile-cards">
                            @foreach($reports as $report)
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 cursor-pointer hover:bg-brand-teal/5 transition-colors" onclick="window.location='{{ route('admin.incident-reports.show', $report) }}'">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-file-signature text-brand-teal-dark"></i>
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">#{{ $report->reportId }}</span>
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $report->report_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="space-y-2 mb-3">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Reporter</p>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report->reporter->fullName }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $report->reporter->email }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Reported User</p>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report->reportedUser->fullName }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $report->reportedUser->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                                        @php
                                            $typeClass = $typeStyles[$report->incident_type] ?? 'badge-soft badge-soft-slate';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold tracking-wide {{ $typeClass }}">
                                            {{ ucwords(str_replace('_', ' ', $report->incident_type)) }}
                                        </span>
                                        @php
                                            $statusClass = $statusStyles[$report->status] ?? 'badge-soft badge-soft-slate';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold tracking-wide {{ $statusClass }}">
                                            {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Desktop Table View -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Reporter
                                        </th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Reported User
                                        </th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($reports as $report)
                                        <tr class="hover:bg-brand-teal/5 cursor-pointer" onclick="window.location='{{ route('admin.incident-reports.show', $report) }}'">
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <span class="inline-flex items-center gap-2">
                                                    <i class="fas fa-file-signature text-brand-teal-dark"></i>
                                                    #{{ $report->reportId }}
                                                </span>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $report->reporter->fullName }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $report->reporter->email }}
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $report->reportedUser->fullName }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $report->reportedUser->email }}
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $typeClass = $typeStyles[$report->incident_type] ?? 'badge-soft badge-soft-slate';
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $typeClass }}">
                                                    {{ ucwords(str_replace('_', ' ', $report->incident_type)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClass = $statusStyles[$report->status] ?? 'badge-soft badge-soft-slate';
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $statusClass }}">
                                                    {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $report->report_date->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 sm:mt-6">
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
