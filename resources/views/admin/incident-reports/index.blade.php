<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Incident Reports Management') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-600 truncate">Total Reports</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-600 truncate">Pending</dt>
                                    <dd class="text-lg font-medium text-yellow-600">{{ $stats['pending'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Under Review</dt>
                                    <dd class="text-lg font-medium text-blue-600">{{ $stats['under_review'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Resolved</dt>
                                    <dd class="text-lg font-medium text-green-600">{{ $stats['resolved'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Dismissed</dt>
                                    <dd class="text-lg font-medium text-gray-600">{{ $stats['dismissed'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.incident-reports.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                    <option value="">All Statuses</option>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="incident_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Incident Type</label>
                                <select id="incident_type" name="incident_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                    <option value="">All Types</option>
                                    @foreach($incidentTypes as $key => $label)
                                        <option value="{{ $key }}" {{ request('incident_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">From Date</label>
                                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">To Date</label>
                                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search by reporter or reported user name/email..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>

                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.incident-reports.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" id="reports-table-container">
                <div class="p-6">
                    <div id="reports-content">
                    @if($reports->count() > 0)
                        <form id="bulk-form" method="POST" action="{{ route('admin.incident-reports.bulk-update') }}">
                            @csrf
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center space-x-2">
                                    <select id="bulk-action" name="action" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                        <option value="">Bulk Actions</option>
                                        <option value="mark_pending">Mark as Pending</option>
                                        <option value="mark_under_review">Mark as Under Review</option>
                                        <option value="mark_resolved">Mark as Resolved</option>
                                        <option value="mark_dismissed">Mark as Dismissed</option>
                                        <option value="delete">Delete Selected</option>
                                    </select>
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Apply
                                    </button>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $reports->total() }} total reports
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left">
                                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                            </th>
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($reports as $report)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" name="report_ids[]" value="{{ $report->reportId }}" class="report-checkbox rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    #{{ $report->reportId }}
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
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($report->incident_type === 'non_participation') bg-yellow-100 text-yellow-800
                                                        @elseif($report->incident_type === 'abuse') bg-red-100 text-red-800
                                                        @elseif($report->incident_type === 'spam') bg-blue-100 text-blue-800
                                                        @elseif($report->incident_type === 'inappropriate_content') bg-purple-100 text-purple-800
                                                        @elseif($report->incident_type === 'harassment') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucwords(str_replace('_', ' ', $report->incident_type)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($report->status === 'under_review') bg-blue-100 text-blue-800
                                                        @elseif($report->status === 'resolved') bg-green-100 text-green-800
                                                        @elseif($report->status === 'dismissed') bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $report->report_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                    <a href="{{ route('admin.incident-reports.show', $report) }}" 
                                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        View
                                                    </a>
                                                    <a href="{{ route('admin.incident-reports.edit', $report) }}" 
                                                       class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                        Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>

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
    </div>

    @push('scripts')
    <script>
        // Auto-update reports via AJAX when filters change (no page refresh)
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.querySelector('form[action="{{ route('admin.incident-reports.index') }}"]');
            const statusSelect = document.getElementById('status');
            const incidentTypeSelect = document.getElementById('incident_type');
            const dateFromInput = document.getElementById('date_from');
            const dateToInput = document.getElementById('date_to');
            const searchInput = document.getElementById('search');

            function updateReports() {
                const reportsContainer = document.getElementById('reports-table-container');
                const reportsContent = document.getElementById('reports-content');
                
                if (reportsContent) {
                    reportsContent.innerHTML = '<div class="p-8 text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="mt-2 text-gray-600 dark:text-gray-400">Loading reports...</p></div>';
                }

                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);

                fetch(`{{ route('admin.incident-reports.index') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newReportsContent = doc.getElementById('reports-content');
                    
                    if (newReportsContent && reportsContent) {
                        reportsContent.innerHTML = newReportsContent.innerHTML;
                    }
                    
                    // Re-initialize select all functionality
                    const selectAll = document.getElementById('select-all');
                    if (selectAll) {
                        selectAll.addEventListener('change', function() {
                            const checkboxes = document.querySelectorAll('.report-checkbox');
                            checkboxes.forEach(checkbox => {
                                checkbox.checked = this.checked;
                            });
                        });
                    }
                    
                    const newUrl = `{{ route('admin.incident-reports.index') }}?${params.toString()}`;
                    window.history.pushState({path: newUrl}, '', newUrl);
                })
                .catch(error => {
                    console.error('Error updating reports:', error);
                    if (reportsContent) {
                        reportsContent.innerHTML = '<div class="p-8 text-center text-red-600">Error loading reports. Please refresh the page.</div>';
                    }
                });
            }

            // Debounce search input
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(updateReports, 500);
                });
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', updateReports);
            }
            
            if (incidentTypeSelect) {
                incidentTypeSelect.addEventListener('change', updateReports);
            }
            
            if (dateFromInput) {
                dateFromInput.addEventListener('change', updateReports);
            }
            
            if (dateToInput) {
                dateToInput.addEventListener('change', updateReports);
            }
        });

        // Select all functionality
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.report-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }

        // Bulk form submission
        document.getElementById('bulk-form').addEventListener('submit', function(e) {
            const action = document.getElementById('bulk-action').value;
            const checkedBoxes = document.querySelectorAll('.report-checkbox:checked');
            
            if (!action) {
                e.preventDefault();
                showAlertModal('Please select a bulk action.', 'Action Required', 'warning');
                return;
            }
            
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                showAlertModal('Please select at least one report.', 'Selection Required', 'warning');
                return;
            }
            
            if (action === 'delete') {
                e.preventDefault();
                showConfirmModal(
                    `Are you sure you want to delete ${checkedBoxes.length} report(s)? This action cannot be undone.`,
                    'Confirm Deletion',
                    'Delete',
                    'Cancel',
                    'red'
                ).then(confirmed => {
                    if (confirmed) {
                        document.getElementById('bulk-form').submit();
                    }
                });
                return;
            }
        });
    </script>
    @endpush
</x-admin-layout>
