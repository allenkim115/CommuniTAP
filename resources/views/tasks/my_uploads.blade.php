<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Tasks
            </a>
            <div class="flex items-center gap-2 sm:gap-3 flex-wrap w-full sm:w-auto">
                <a href="{{ route('tasks.creator.submissions') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-lg sm:rounded-xl transition-all duration-200 shadow-sm hover:shadow-md flex-1 sm:flex-initial min-w-0"
                   style="color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);"
                   onmouseover="this.style.color='#248A7C'; this.style.backgroundColor='rgba(43, 157, 141, 0.2)';"
                   onmouseout="this.style.color='#2B9D8D'; this.style.backgroundColor='rgba(43, 157, 141, 0.1)';">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="truncate">Review Submissions</span>
                </a>
                <a href="{{ route('tasks.creator.history') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-lg sm:rounded-xl transition-all duration-200 shadow-sm hover:shadow-md flex-1 sm:flex-initial min-w-0"
                   style="color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);"
                   onmouseover="this.style.color='#248A7C'; this.style.backgroundColor='rgba(43, 157, 141, 0.2)';"
                   onmouseout="this.style.color='#2B9D8D'; this.style.backgroundColor='rgba(43, 157, 141, 0.1)';">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="truncate">View History</span>
                </a>
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold text-white rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex-1 sm:flex-initial min-w-0"
                   style="background-color: #F3A261;"
                   onmouseover="this.style.backgroundColor='#E8944F'"
                   onmouseout="this.style.backgroundColor='#F3A261'">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="truncate">Add Task</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 via-orange-50/30 to-teal-50/20 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 md:py-8">
            <x-session-toast />

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 sm:mb-8 gap-4">
                <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0" style="background-color: #F3A261;">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">My Uploaded Tasks</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Manage and track your task submissions</p>
                    </div>
                </div>
                <div class="px-3 py-1.5 sm:px-4 sm:py-2 bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-md border border-gray-200 dark:border-gray-700 w-full sm:w-auto text-center sm:text-left">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300">Total: <span class="dark:text-orange-400" style="color: #F3A261;">{{ $stats['all'] ?? 0 }}</span></span>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mb-6 sm:mb-8">
                @php 
                    $labels = ['pending','live','rejected','completed','uncompleted','all'];
                @endphp
                @foreach($labels as $label)
                <button type="button" onclick="setStatusFilter('{{ $label }}')" 
                   class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-md p-3 sm:p-4 md:p-5 text-center border-2 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 active:scale-95 status-filter-btn @class(['dark:from-orange-900/20 dark:to-orange-800/20' => ($activeStatus ?? 'all') === $label, 'border-gray-200 dark:border-gray-700 dark:hover:border-orange-700' => ($activeStatus ?? 'all') !== $label])"
                   data-status="{{ $label }}"
                   @if(($activeStatus ?? 'all') === $label) style="border-color: #F3A261; background-color: rgba(243, 162, 97, 0.1);" @endif
                   @if(($activeStatus ?? 'all') !== $label) onmouseover="this.style.borderColor='#F3A261';" onmouseout="this.style.borderColor='';" @endif>
                    <p class="text-[10px] xs:text-xs uppercase tracking-wide font-bold text-gray-500 dark:text-gray-400 mb-1 sm:mb-2 leading-tight">{{ ucfirst($label) }}</p>
                    <p class="text-lg sm:text-xl md:text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #F3A261, #2B9D8D); -webkit-background-clip: text;">{{ $stats[$label] ?? 0 }}</p>
                </button>
                @endforeach
            </div>

            <!-- Filters -->
            <div class="mb-4 sm:mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-3 sm:p-4 flex flex-col sm:flex-row sm:items-end gap-3 sm:gap-4">
                    <div class="flex-shrink-0 w-full sm:min-w-[160px] sm:w-auto">
                        <label for="status" class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Status</label>
                        <div class="relative">
                            <select id="status" name="status" class="w-full border-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg sm:rounded-xl shadow-sm pl-3 pr-10 py-2 sm:py-2.5 text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all appearance-none bg-white dark:bg-gray-900">
                                @php $options = ['all' => 'All', 'pending' => 'Pending', 'live' => 'Live', 'rejected' => 'Rejected', 'completed' => 'Completed', 'uncompleted' => 'Uncompleted']; @endphp
                                @foreach($options as $value => $text)
                                    <option value="{{ $value }}" {{ ($activeStatus ?? 'all') === $value ? 'selected' : '' }}>{{ $text }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0 w-full sm:w-auto">
                        <label for="q" class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Search</label>
                        <input type="text" id="q" name="q" value="{{ $search ?? '' }}" placeholder="Search title, description, or location" class="w-full border-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg sm:rounded-xl shadow-sm px-3 py-2 sm:py-2.5 text-xs sm:text-sm focus:ring-2 transition-all"
                               style="--focus-ring-color: #F3A261;"
                               onfocus="this.style.borderColor='#F3A261'; this.style.boxShadow='0 0 0 3px rgba(243, 162, 97, 0.1)';"
                               onblur="this.style.borderColor=''; this.style.boxShadow='';" />
                    </div>
                </div>
            </div>

            @if($uploads->count() === 0)
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-3xl shadow-xl p-12 text-center">
                    <div class="mx-auto w-24 h-24 mb-6 rounded-full dark:from-orange-900/30 dark:to-teal-900/30 flex items-center justify-center" style="background-color: rgba(243, 162, 97, 0.2);">
                        <svg class="w-12 h-12 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #F3A261;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">No Tasks Uploaded Yet</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-2 text-lg">You haven't uploaded any tasks yet.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Create your first task to get started!</p>
                    <a href="{{ route('tasks.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5"
                       style="background-color: #F3A261;"
                       onmouseover="this.style.backgroundColor='#E8944F'"
                       onmouseout="this.style.backgroundColor='#F3A261'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Task
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="tasks-grid">
                    @foreach($uploads as $task)
                    @php
                        // A task is "Live" only if it's approved/published AND not expired
                        $isLive = in_array($task->status, ['approved','published']) && !$task->isExpired();
                        // Exclude pending, completed, and live tasks from uncompleted
                        $isUncompleted = !in_array($task->status, ['completed', 'pending']) && !$isLive;
                        
                        // Check if inactive task has been edited (updated after deactivation)
                        // Edited inactive tasks should show as pending (waiting for publishing) instead of cancelled
                        $inactiveButEdited = false;
                        if ($task->status === 'inactive' && $isUncompleted) {
                            if ($task->deactivated_at) {
                                // Task has been edited if updated_at is after deactivated_at
                                $inactiveButEdited = $task->updated_at > $task->deactivated_at;
                            } else {
                                // If no deactivated_at but status is inactive, treat as edited (waiting for publishing)
                                $inactiveButEdited = true;
                            }
                        }
                        
                        // Determine display status: if inactive and edited, show as pending
                        $displayStatus = $inactiveButEdited ? 'pending' : $task->status;
                        $taskStatus = $isLive ? 'live' : ($task->status === 'draft' || ($task->status === 'inactive' && !$inactiveButEdited) ? 'cancelled' : $displayStatus);
                    @endphp
                    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 border-2 border-gray-200 dark:border-gray-700 cursor-pointer task-card overflow-hidden" 
                         data-task-id="{{ $task->taskId }}"
                         data-status="{{ $taskStatus }}"
                         data-uncompleted="{{ $isUncompleted ? 'true' : 'false' }}"
                         data-title="{{ strtolower($task->title) }}"
                         data-description="{{ strtolower($task->description) }}"
                         data-location="{{ strtolower($task->location ?? '') }}"
                         onclick="openTaskModal({{ $task->taskId }})">
                        <!-- Decorative gradient background -->
                        <div class="absolute top-0 right-0 w-32 h-32 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500" style="background-color: rgba(243, 162, 97, 0.1);"></div>
                        <!-- Shine effect on hover -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        
                        <div class="relative">
                            <!-- Header: Title + Status Badge -->
                            <div class="flex justify-between items-start mb-3 gap-3">
                                <h4 class="font-bold text-gray-900 dark:text-white text-lg leading-tight flex-1 min-w-0 dark:group-hover:text-orange-400 transition-colors group-hover:text-orange-600 truncate" style="--hover-color: #F3A261;" title="{{ $task->title }}">
                                    {{ $task->title }}
                                </h4>
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'rgba(254, 210, 179, 0.2)', 'color' => '#FED2B3'],
                                        'draft' => ['bg' => 'rgba(229, 231, 235, 0.5)', 'color' => '#6B7280'],
                                        'inactive' => ['bg' => 'rgba(229, 231, 235, 0.5)', 'color' => '#6B7280'],
                                        'live' => ['bg' => '#2B9D8D', 'color' => '#FFFFFF'],
                                        'completed' => ['bg' => '#2B9D8D', 'color' => '#FFFFFF'],
                                        'rejected' => ['bg' => '#2B9D8D', 'color' => '#FFFFFF'],
                                    ];
                                    // Use displayStatus for badge display (pending for edited inactive tasks)
                                    $badgeStatus = $inactiveButEdited ? 'pending' : $task->status;
                                    $statusStyle = $isLive ? $statusConfig['live'] : ($badgeStatus === 'draft' || ($badgeStatus === 'inactive' && !$inactiveButEdited) ? $statusConfig['draft'] : ($statusConfig[$badgeStatus] ?? $statusConfig['pending']));
                                    $badgeText = $isLive ? 'Live' : ($badgeStatus === 'draft' || ($badgeStatus === 'inactive' && !$inactiveButEdited) ? 'Cancelled' : ($inactiveButEdited ? 'Pending' : ucfirst($badgeStatus)));
                                @endphp
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full shadow-sm whitespace-nowrap flex-shrink-0" style="background-color: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['color'] }};">
                                    {{ $badgeText }}
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2 leading-relaxed">
                                {{ $task->description }}
                            </p>

                            <!-- Badges: Points, Date -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="inline-flex items-center gap-1 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md" style="background-color: #F3A261;">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    +{{ $task->points_awarded }} pts
                                </span>
                                <span class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-lg text-xs font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    @if($task->due_date)
                                        {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                    @else
                                        {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                    @endif
                                </span>
                                <span class="inline-flex items-center text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md" style="background-color: #2B9D8D;">
                                    {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                </span>
                            </div>

                            <!-- Footer actions -->
                            <div class="pt-4 border-t-2 border-gray-100 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('tasks.show', $task) }}" onclick="event.stopPropagation();" class="inline-flex items-center gap-2 dark:text-orange-400 dark:hover:text-orange-300 text-sm font-semibold transition-colors"
                                       style="color: #F3A261;"
                                       onmouseover="this.style.color='#E8944F';"
                                       onmouseout="this.style.color='#F3A261';">
                                        View Details
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                    <div class="flex items-center gap-2" onclick="event.stopPropagation();">
                                        @php $canEdit = in_array($task->status, ['pending','rejected','draft','inactive']); @endphp
                                        @if($canEdit)
                                            <a href="{{ route('tasks.edit', $task) }}" class="px-3 py-1.5 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200"
                                               style="background-color: #2B9D8D;"
                                               onmouseover="this.style.backgroundColor='#248A7C'"
                                               onmouseout="this.style.backgroundColor='#2B9D8D'">Edit</a>
                                            @if(!in_array($task->status, ['draft', 'inactive']))
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" id="cancel-task-form-{{ $task->id }}" class="inline" novalidate>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="showConfirmModal('Cancel this task proposal?', 'Cancel Task Proposal', 'Cancel', 'Keep', 'red').then(confirmed => { if(confirmed) document.getElementById('cancel-task-form-{{ $task->id }}').submit(); });" class="px-3 py-1.5 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200"
                                                            style="background-color: #2B9D8D;"
                                                            onmouseover="this.style.backgroundColor='#248A7C'"
                                                            onmouseout="this.style.backgroundColor='#2B9D8D'">Cancel</button>
                                                </form>
                                            @endif
                                        @else
                                            <button class="px-3 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 text-xs font-bold rounded-xl cursor-not-allowed shadow-sm" title="Editing disabled after approval" disabled>Locked</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Task Details Modal -->
    <div id="task-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true" style="backdrop-filter: blur(4px);">
        <div class="absolute inset-0 bg-black/50" onclick="closeTaskModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border-2 dark:border-orange-700 transform transition-all" style="border-color: #F3A261;">
                <div class="px-6 py-0" style="display:none"></div>
                <div id="task-modal-content" class="p-6 space-y-4">
                    <!-- Dynamic content -->
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for task details modal -->
    <script>
        // Store task data for JavaScript access
        const tasks = @json($uploads->keyBy('taskId'));
        const userAssignments = @json(collect());

        function openTaskModal(taskId) {
            const task = tasks[taskId];
            if (!task) return;

            const modal = document.getElementById('task-modal');
            const contentEl = document.getElementById('task-modal-content');

            // Compute uploader name
            const uploaderName = (() => {
                const u = task.assigned_user;
                if (!u) return 'Admin';
                const computed = u.name || [u.firstName, u.middleName, u.lastName].filter(Boolean).join(' ').trim();
                return computed && computed.length > 0 ? computed : 'Admin';
            })();

            // Compute date text
            const dateText = task.due_date ?
                (typeof task.due_date === 'string' ? new Date(task.due_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.due_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })) :
                (task.published_date ? (typeof task.published_date === 'string' ? new Date(task.published_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.published_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })) : 'Not specified');

            // Compute time text
            const timeText = (task.start_time && task.end_time)
                ? (new Date('2000-01-01T' + task.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) + ' - ' + new Date('2000-01-01T' + task.end_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }))
                : (task.start_time ? (new Date('2000-01-01T' + task.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) + ' onwards') : 'Flexible');

            const isCreator = task.FK1_userId && Number(task.FK1_userId) === Number({{ auth()->user()->userId }});
            const isFull = task.max_participants !== null && task.max_participants !== undefined && task.assignments && task.assignments.length >= task.max_participants;
            
            // Check if task is expired
            let isExpired = false;
            if (task.due_date) {
                const dueDate = typeof task.due_date === 'string' ? new Date(task.due_date) : new Date(task.due_date.date);
                if (task.end_time) {
                    // Combine due_date date with end_time
                    const [hours, minutes] = task.end_time.split(':');
                    const deadline = new Date(dueDate);
                    deadline.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                    isExpired = new Date() > deadline;
                } else {
                    isExpired = new Date() > dueDate;
                }
            }
            
            // A task is "Live" only if it's approved/published AND not expired
            const isLive = ['approved', 'published'].includes(task.status) && !isExpired;

            contentEl.innerHTML = `
                <div class="border-b border-gray-200 dark:border-gray-700 -mt-2 -mx-6 px-6 pb-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">${task.title}</h2>
                        <button type="button" class="text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-200 transition-colors" onclick="closeTaskModal()" aria-label="Close modal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">by ${uploaderName}</p>
                </div>
                <div class="pt-6">
                <div id="modal-details-content">
                    <div class="space-y-6">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">${task.description || ''}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #F3A261;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Date</p>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">${dateText}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #2B9D8D;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Time</p>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">${timeText}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Location</p>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">${task.location || 'Community'}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Points</p>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">${((task.points_awarded !== undefined && task.points_awarded !== null) ? task.points_awarded : 'N/A')}</p>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2">
                        <a href="/tasks/${taskId}" class="inline-flex items-center justify-center gap-2 w-full text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5" style="background-color: #F3A261;" onmouseover="this.style.backgroundColor='#E8944F'" onmouseout="this.style.backgroundColor='#F3A261'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Open Task</a>
                    </div>
                </div>
                </div>
            `;

            modal.classList.remove('hidden');
        }

        function closeTaskModal() {
            const modal = document.getElementById('task-modal');
            if (modal) modal.classList.add('hidden');
        }

        // Set status filter from stats button
        function setStatusFilter(status) {
            const statusSelect = document.getElementById('status');
            if (statusSelect) {
                statusSelect.value = status;
                filterTasks();
                updateActiveStatusButton(status);
            }
        }
        
        // Update active status button styling
        function updateActiveStatusButton(activeStatus) {
            const buttons = document.querySelectorAll('.status-filter-btn');
            buttons.forEach(btn => {
                const btnStatus = btn.getAttribute('data-status');
                if (btnStatus === activeStatus) {
                    btn.style.borderColor = '#F3A261';
                    btn.style.backgroundColor = 'rgba(243, 162, 97, 0.1)';
                    btn.classList.remove('border-gray-200', 'dark:border-gray-700');
                } else {
                    btn.style.borderColor = '';
                    btn.style.backgroundColor = '';
                    btn.classList.add('border-gray-200', 'dark:border-gray-700');
                }
            });
        }
        
        // Client-side filtering without page refresh
        function filterTasks() {
            const statusFilter = document.getElementById('status')?.value || 'all';
            const searchQuery = document.getElementById('q')?.value.toLowerCase().trim() || '';
            const taskCards = document.querySelectorAll('.task-card');
            const tasksGrid = document.getElementById('tasks-grid');
            const emptyState = document.querySelector('.empty-state-message');
            
            // Update active status button
            updateActiveStatusButton(statusFilter);
            
            let visibleCount = 0;
            
            taskCards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                const isUncompleted = card.getAttribute('data-uncompleted') === 'true';
                const cardTitle = card.getAttribute('data-title') || '';
                const cardDescription = card.getAttribute('data-description') || '';
                const cardLocation = card.getAttribute('data-location') || '';
                
                // Status filtering
                let statusMatch = false;
                if (statusFilter === 'all') {
                    statusMatch = true;
                } else if (statusFilter === 'uncompleted') {
                    statusMatch = isUncompleted;
                } else {
                    statusMatch = cardStatus === statusFilter;
                }
                
                // Search filtering
                let searchMatch = true;
                if (searchQuery) {
                    searchMatch = cardTitle.includes(searchQuery) || 
                                 cardDescription.includes(searchQuery) || 
                                 cardLocation.includes(searchQuery);
                }
                
                // Show or hide card
                if (statusMatch && searchMatch) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // Show/hide empty state
            if (tasksGrid) {
                const existingEmptyState = tasksGrid.querySelector('.no-tasks-message');
                if (existingEmptyState) {
                    existingEmptyState.remove();
                }
                
                if (visibleCount === 0) {
                    const noTasksDiv = document.createElement('div');
                    noTasksDiv.className = 'no-tasks-message col-span-full bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-3xl shadow-xl p-12 text-center';
                    noTasksDiv.innerHTML = `
                        <div class="mx-auto w-24 h-24 mb-6 rounded-full dark:from-orange-900/30 dark:to-teal-900/30 flex items-center justify-center" style="background-color: rgba(243, 162, 97, 0.2);">
                            <svg class="w-12 h-12 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #F3A261;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">No Tasks Found</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-2 text-lg">No tasks match your current filter criteria.</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Try adjusting your filters or search terms.</p>
                    `;
                    tasksGrid.appendChild(noTasksDiv);
                }
            }
        }
        
        // Initialize filtering on page load
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const searchInput = document.getElementById('q');
            
            // Add event listeners
            if (statusSelect) {
                statusSelect.addEventListener('change', filterTasks);
            }
            
            if (searchInput) {
                // Debounce search input
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(filterTasks, 300);
                });
            }
            
            // Initial filter on page load
            filterTasks();
        });

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeTaskModal();
        });
    </script>

    <style>
        @keyframes card-enter {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .grid > div.task-card {
            animation: card-enter 0.5s ease-out forwards;
        }
        
        .grid > div.task-card:nth-child(1) { animation-delay: 0.1s; }
        .grid > div.task-card:nth-child(2) { animation-delay: 0.2s; }
        .grid > div.task-card:nth-child(3) { animation-delay: 0.3s; }
        .grid > div.task-card:nth-child(4) { animation-delay: 0.4s; }
        .grid > div.task-card:nth-child(5) { animation-delay: 0.5s; }
        .grid > div.task-card:nth-child(6) { animation-delay: 0.6s; }
        
        .grid > div.task-card {
            opacity: 0;
        }
        
        /* Ensure hidden task cards don't take up space */
        .task-card.hidden {
            display: none !important;
        }
    </style>
</x-app-layout>
