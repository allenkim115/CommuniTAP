<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Submissions') }}
            </h2>
                <p class="text-sm text-gray-600 mt-1">Review and manage task submissions</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('tasks.creator.history') }}" 
                   class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                   style="background-color: #2B9D8D;"
                   onmouseover="this.style.backgroundColor='#248A7C'"
                   onmouseout="this.style.backgroundColor='#2B9D8D'">
                    <i class="fas fa-history mr-2"></i>
                    View History
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 sm:p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Pending Review</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $submissions->total() }}</p>
                        </div>
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg flex items-center justify-center flex-shrink-0 ml-2" style="background-color: rgba(254, 210, 179, 0.2);">
                            <i class="fas fa-clock text-base sm:text-xl" style="color: #FED2B3;"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 sm:p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Completed Today</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg flex items-center justify-center flex-shrink-0 ml-2" style="background-color: rgba(43, 157, 141, 0.2);">
                            <i class="fas fa-check-circle text-base sm:text-xl" style="color: #2B9D8D;"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 sm:p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Completed</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg flex items-center justify-center flex-shrink-0 ml-2" style="background-color: rgba(43, 157, 141, 0.2);">
                            <i class="fas fa-chart-line text-base sm:text-xl" style="color: #2B9D8D;"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 sm:p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Rejected Today</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg flex items-center justify-center flex-shrink-0 ml-2" style="background-color: rgba(43, 157, 141, 0.2);">
                            <i class="fas fa-times-circle text-base sm:text-xl" style="color: #2B9D8D;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick search + Task type filter -->
            <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row gap-3 sm:gap-4 sm:items-center sm:justify-between">
                <div class="w-full sm:w-64">
                    <label for="submissions-task-type" class="block text-xs font-semibold text-gray-600 mb-1">Task Type</label>
                    <select id="submissions-task-type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-brand-teal text-sm min-h-[40px] bg-white">
                        <option value="all">All Types</option>
                        <option value="daily">Daily Task</option>
                        <option value="one_time">One-Time Task</option>
                    </select>
                </div>
                <div class="relative w-full sm:w-96">
                    <input
                        type="text"
                        id="submissions-search"
                        placeholder="Search submissions..."
                        class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-brand-teal text-sm min-h-[40px]"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <button
                        type="button"
                        id="clearSubmissionsSearch"
                        class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        aria-label="Clear search"
                    >
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Submissions Table -->
            <div id="submissions-table-container">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Submissions</h3>
                </div>
                    
                    @if($submissions->count() > 0)
                        <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                    <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Photos</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Points</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"></th>
                                    </tr>
                                </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($submissions as $submission)
                                    @php
                                        $searchBlob = strtolower(
                                            ($submission->user->name ?? '') . ' ' .
                                            ($submission->user->email ?? '') . ' ' .
                                            ($submission->task->title ?? '') . ' ' .
                                            ($submission->task->task_type ?? '') . ' pending'
                                        );
                                    @endphp
                                    <tr onclick="window.location='{{ route('tasks.creator.show', $submission) }}'" class="transition-colors cursor-pointer"
                                        onmouseover="this.style.backgroundColor='rgba(43, 157, 141, 0.1)';"
                                        onmouseout="this.style.backgroundColor='';"
                                        data-submission-row
                                        data-task-type="{{ $submission->task->task_type }}"
                                        data-search="{{ $searchBlob }}">
                                        <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                <x-user-avatar
                                                    :user="$submission->user"
                                                    size="h-10 w-10"
                                                    text-size="text-sm"
                                                    class="flex-shrink-0 text-white font-semibold"
                                                    style="background-color: #2B9D8D;"
                                                />
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $submission->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit($submission->user->email, 25) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($submission->task->title, 40) }}</div>
                                            <div class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $submission->task->task_type)) }}</div>
                                            </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ is_string($submission->submitted_at) ? \Carbon\Carbon::parse($submission->submitted_at)->format('M j, Y g:i A') : $submission->submitted_at->format('M j, Y g:i A') }}
                                            </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                    @php
                                                        $photoBg = 'rgba(229, 231, 235, 0.5)';
                                                        $photoColor = '#6B7280';
                                                        if($submission->photos && count($submission->photos) > 0) {
                                                            $photoBg = 'rgba(43, 157, 141, 0.2)';
                                                            $photoColor = '#2B9D8D';
                                                        }
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $photoBg }}; color: {{ $photoColor }};"
                                                        @if($submission->photos && count($submission->photos) > 0) 
                                                        @else 
                                                        @endif>
                                                    <i class="fas fa-images mr-1"></i>
                                                    {{ $submission->photos ? count($submission->photos) : 0 }}
                                                    </span>
                                                    @if($submission->rejection_count > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs text-red-600 bg-red-50">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Rejected {{ $submission->rejection_count }}/3
                                                </span>
                                                    @endif
                                                </div>
                                            </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-semibold" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                                <i class="fas fa-star mr-1 text-xs"></i>
                                                {{ $submission->task->points_awarded }}
                                            </span>
                                            </td>
                                        <td class="px-6 py-4 text-right">
                                            <i class="fas fa-chevron-right text-gray-400"></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                            {{ $submissions->appends(request()->query())->links() }}
                        </div>
                    @else
                    <div class="text-center py-16">
                        <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No pending submissions</h3>
                        <p class="text-sm text-gray-500">All task submissions have been reviewed.</p>
                        </div>
                    @endif
                    <div id="submissions-search-empty" class="hidden text-center py-12 border-t border-gray-200">
                        <div class="mx-auto h-14 w-14 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                            <i class="fas fa-search text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">No submissions match your search</h3>
                        <p class="text-sm text-gray-500">Try a different keyword.</p>
                    </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const clientSearch = document.getElementById('submissions-search');
    const clearClientBtn = document.getElementById('clearSubmissionsSearch');
    const emptyState = document.getElementById('submissions-search-empty');
    const typeFilter = document.getElementById('submissions-task-type');

    const applyClientFilter = () => {
        const rows = Array.from(document.querySelectorAll('[data-submission-row]'));
        if (!clientSearch || rows.length === 0) return;

        const query = (clientSearch.value || '').trim().toLowerCase();
        const selectedType = (typeFilter && typeFilter.value) || 'all';
        let visible = 0;

        rows.forEach(row => {
            const haystack = (row.dataset.search || '').toLowerCase();
            const rowType = (row.dataset.taskType || '').toLowerCase();
            const typeMatches = selectedType === 'all' || rowType === selectedType;
            const searchMatches = !query || haystack.includes(query);
            const matches = typeMatches && searchMatches;
            row.classList.toggle('hidden', !matches);
            if (matches) visible++;
        });

        if (emptyState) {
            emptyState.classList.toggle('hidden', visible !== 0);
        }

        if (clearClientBtn) {
            clearClientBtn.classList.toggle('hidden', !query);
        }
    };

    if (clientSearch) {
        clientSearch.addEventListener('input', applyClientFilter);
        clientSearch.addEventListener('keyup', applyClientFilter);
        clientSearch.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') e.preventDefault();
        });
    }

    if (clearClientBtn) {
        clearClientBtn.addEventListener('click', () => {
            clientSearch.value = '';
            applyClientFilter();
            clientSearch.focus();
        });
    }

    if (typeFilter) {
        typeFilter.addEventListener('change', applyClientFilter);
    }

    applyClientFilter();
});
</script>