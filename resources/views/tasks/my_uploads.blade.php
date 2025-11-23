<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-3xl bg-gradient-to-r from-orange-600 via-orange-500 to-teal-500 bg-clip-text text-transparent">
                {{ __('My Uploaded Tasks') }}
            </h2>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('tasks.creator.submissions') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-green-600 hover:text-green-700 bg-green-50 hover:bg-green-100 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Review Submissions
                </a>
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Task
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 via-orange-50/30 to-teal-50/20 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <x-session-toast />

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                @php $labels = ['pending','live','rejected','completed','all']; @endphp
                @foreach($labels as $label)
                <a href="{{ route('tasks.my-uploads', array_filter(['status' => $label === 'all' ? 'all' : $label, 'q' => $search ?? null])) }}"
                   class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center border-2 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 @class(['border-orange-500 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20' => ($activeStatus ?? 'all') === $label, 'border-gray-200 dark:border-gray-700' => ($activeStatus ?? 'all') !== $label])">
                    <p class="text-xs uppercase tracking-wide font-bold text-gray-500 dark:text-gray-400 mb-1">{{ ucfirst($label) }}</p>
                    <p class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-teal-500 bg-clip-text text-transparent">{{ $stats[$label] }}</p>
                </a>
                @endforeach
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('tasks.my-uploads') }}" class="mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4 flex flex-col sm:flex-row sm:items-end sm:space-x-4 space-y-3 sm:space-y-0">
                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1">Status</label>
                        <select id="status" name="status" class="border-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-xl shadow-sm px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            @php $options = ['all' => 'All', 'pending' => 'Pending', 'live' => 'Live', 'rejected' => 'Rejected', 'completed' => 'Completed']; @endphp
                            @foreach($options as $value => $text)
                                <option value="{{ $value }}" {{ ($activeStatus ?? 'all') === $value ? 'selected' : '' }}>{{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="q" class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" id="q" name="q" value="{{ $search ?? '' }}" placeholder="Search title, description, or location" class="w-full border-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-xl shadow-sm px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all" />
                    </div>
                    <div class="pt-5 sm:pt-0">
                        <button type="submit" class="bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 text-white font-bold py-2 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">Apply</button>
                    </div>
                </div>
            </form>

            @if($uploads->count() === 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
                    <p class="text-gray-600 dark:text-gray-300 font-semibold">You haven't uploaded any tasks yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($uploads as $task)
                    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 border-2 border-gray-200 dark:border-gray-700 cursor-pointer task-card overflow-hidden" 
                         data-task-id="{{ $task->taskId }}"
                         onclick="openTaskModal({{ $task->taskId }})">
                        <!-- Decorative gradient background -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-400/10 to-teal-400/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                        <!-- Shine effect on hover -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        
                        <div class="relative">
                        <div class="flex justify-between items-start mb-3 gap-3">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight flex-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">{{ $task->title }}</h3>
                            @php
                                $isLive = in_array($task->status, ['approved','published']);
                            @endphp
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg shadow-sm whitespace-nowrap flex-shrink-0
                                @class([
                                    'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 dark:from-gray-700 dark:to-gray-600 dark:text-gray-200' => $task->status === 'pending' || $task->status === 'draft' || $task->status === 'inactive',
                                    'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/30 dark:to-emerald-900/30 dark:text-green-300' => $isLive || $task->status === 'completed',
                                    'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 dark:from-red-900/30 dark:to-rose-900/30 dark:text-red-300' => $task->status === 'rejected',
                                ])
                            ">
                                {{ $isLive ? 'Live' : ($task->status === 'draft' || $task->status === 'inactive' ? 'Cancelled' : ucfirst($task->status)) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 mb-4 leading-relaxed">{{ $task->description }}</p>

                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-4 space-y-1">
                            <p class="font-semibold">
                                <strong>Date:</strong>
                                @if($task->due_date)
                                    {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                @else
                                    {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                @endif
                            </p>
                            <p class="font-semibold"><strong>Points:</strong> <span class="text-orange-600 dark:text-orange-400">{{ $task->points_awarded }}</span></p>
                            <p class="font-semibold"><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}</p>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('tasks.show', $task) }}" onclick="event.stopPropagation();" class="text-orange-600 hover:text-orange-700 text-sm font-bold transition-colors">View</a>
                            <div class="flex items-center space-x-2" onclick="event.stopPropagation();">
                                @php $canEdit = in_array($task->status, ['pending','rejected','draft','inactive']); @endphp
                                @if($canEdit)
                                    <a href="{{ route('tasks.edit', $task) }}" class="px-3 py-1.5 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">Edit</a>
                                    @if(!in_array($task->status, ['draft', 'inactive']))
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" id="cancel-task-form-{{ $task->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="showConfirmModal('Cancel this task proposal?', 'Cancel Task Proposal', 'Cancel', 'Keep', 'red').then(confirmed => { if(confirmed) document.getElementById('cancel-task-form-{{ $task->id }}').submit(); });" class="px-3 py-1.5 bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">Cancel</button>
                                        </form>
                                    @endif
                                @else
                                    <button class="px-3 py-1.5 bg-gray-300 text-gray-600 text-xs font-bold rounded-xl cursor-not-allowed shadow-sm" title="Editing disabled after approval" disabled>Locked</button>
                                @endif
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
    <div id="task-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTaskModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-0" style="display:none"></div>
                <div id="task-modal-content" class="p-6 space-y-4">
                    <!-- Dynamic content -->
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-2 bg-gray-50 dark:bg-gray-800/50">
                    <a id="task-modal-view-link" href="#" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">Open full page</a>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 text-white rounded-xl text-sm font-bold transition-all duration-200 shadow-lg hover:shadow-xl" onclick="closeTaskModal()">Close</button>
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
            const viewLink = document.getElementById('task-modal-view-link');

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
            const isLive = ['approved', 'published'].includes(task.status);

            viewLink.href = `/tasks/${taskId}`;

            contentEl.innerHTML = `
                <div class="border-b border-gray-200 dark:border-gray-700 -mt-2 -mx-6 px-6">
                    <div class="flex items-center justify-between">
                        <nav class="flex space-x-8" aria-label="Tabs">
                            <button id=\"modal-details-tab\" class=\"py-4 px-1 border-b-2 border-orange-500 font-bold text-sm text-orange-600 dark:text-orange-400\">Details</button>
                            <button id=\"modal-participants-tab\" class=\"py-4 px-1 border-b-2 border-transparent font-semibold text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300\">Participants</button>
                        </nav>
                        <button type=\"button\" class=\"text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white py-4 transition-colors\" onclick=\"closeTaskModal()\" aria-label=\"Close modal\">
                            <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 18L18 6M6 6l12 12\"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class=\"pt-6\">
                <div id=\"modal-details-content\">
                    <div class=\"space-y-6\">
                    <div>
                        <h2 class=\"text-2xl font-bold text-gray-900 dark:text-white mb-2\">${task.title}</h2>
                        <span class=\"px-4 py-1.5 text-xs font-bold rounded-lg shadow-sm
                            ${task.status === 'pending' ? 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 dark:from-gray-700 dark:to-gray-600 dark:text-gray-200' :
                              isLive || task.status === 'completed' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/30 dark:to-emerald-900/30 dark:text-green-300' :
                              task.status === 'rejected' ? 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 dark:from-red-900/30 dark:to-rose-900/30 dark:text-red-300' :
                              'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 dark:from-gray-700 dark:to-gray-600 dark:text-gray-200'}">
                            ${isLive ? 'Live' : task.status.charAt(0).toUpperCase() + task.status.slice(1)}
                        </span>
                    </div>
                    <p class=\"text-sm font-semibold text-gray-600 dark:text-gray-400\"><strong>Uploaded by:</strong> ${uploaderName}</p>
                    <p class=\"text-gray-700 dark:text-gray-300 leading-relaxed\">${task.description || ''}</p>
                    <div class=\"grid grid-cols-1 sm:grid-cols-2 gap-4\">
                        <div class=\"flex items-center space-x-3 p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl\">
                            <svg class=\"w-5 h-5 text-orange-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\"></path></svg>
                            <span class=\"text-sm font-semibold text-gray-700 dark:text-gray-300\"><strong>Date:</strong> ${dateText}</span>
                        </div>
                        <div class=\"flex items-center space-x-3 p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl\">
                            <svg class=\"w-5 h-5 text-teal-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\"></path></svg>
                            <span class=\"text-sm font-semibold text-gray-700 dark:text-gray-300\"><strong>Time:</strong> ${timeText}</span>
                        </div>
                        <div class=\"flex items-center space-x-3 p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl\">
                            <svg class=\"w-5 h-5 text-orange-500\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path fill-rule=\"evenodd\" d=\"M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z\" clip-rule=\"evenodd\"></path></svg>
                            <span class=\"text-sm font-semibold text-gray-700 dark:text-gray-300\"><strong>Location:</strong> ${task.location || 'Community'}</span>
                        </div>
                        <div class=\"flex items-center space-x-3 p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl\">
                            <svg class=\"w-5 h-5 text-teal-500\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path fill-rule=\"evenodd\" d=\"M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm8 0a2 2 0 114 0 2 2 0 01-4 0z\" clip-rule=\"evenodd\"></path></svg>
                            <span class=\"text-sm font-semibold text-gray-700 dark:text-gray-300\"><strong>Type:</strong> ${task.task_type ? task.task_type.replace('_',' ').replace(/\\b\\w/g, l => l.toUpperCase()) : ''}</span>
                        </div>
                        <div class=\"flex items-center space-x-3 p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl\">
                            <svg class=\"w-5 h-5 text-orange-500\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path d=\"M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z\"></path></svg>
                            <span class=\"text-sm font-semibold text-gray-700 dark:text-gray-300\"><strong>Points:</strong> ${((task.points_awarded !== undefined && task.points_awarded !== null) ? task.points_awarded : '')}</span>
                        </div>
                        <div class=\"flex items-center space-x-3 p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl\">
                            <svg class=\"w-5 h-5 text-teal-500\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path fill-rule=\"evenodd\" d=\"M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z\" clip-rule=\"evenodd\"></path></svg>
                            <span class=\"text-sm font-semibold text-gray-700 dark:text-gray-300\"><strong>Created:</strong> ${task.published_date ? (typeof task.published_date === 'string' ? new Date(task.published_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.published_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })) : (typeof task.creation_date === 'string' ? new Date(task.creation_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.creation_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }))}</span>
                        </div>
                    </div>
                    </div>
                </div>
                <div id=\"modal-participants-content\" class=\"hidden\">
                    <h4 class=\"text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2\">
                        <svg class=\"w-5 h-5 text-orange-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z\"/>
                        </svg>
                        Participants ${task.assignments && task.assignments.length ? `(${task.assignments.length})` : ''}
                    </h4>
                    ${task.assignments && task.assignments.length > 0 ?
                        task.assignments.map(assignment => `
                            <div class=\\"flex items-center justify-between p-4 mb-3 last:mb-0 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-600 hover:shadow-md transition-all duration-200\\">
                                <div class=\\"flex items-center space-x-3\\">
                                    <div class=\\"h-10 w-10 rounded-full bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-md\\">
                                        <span class=\\"text-sm font-bold text-white\\">${assignment.user && assignment.user.name ? assignment.user.name.substring(0,2).toUpperCase() : 'U'}</span>
                                    </div>
                                    <div>
                                        <p class=\\"text-sm font-bold text-gray-900 dark:text-white\\">${assignment.user && assignment.user.name ? assignment.user.name : 'Unknown User'}</p>
                                        <p class=\\"text-xs text-gray-500 dark:text-gray-400\\">${assignment.user && assignment.user.email ? assignment.user.email : ''}</p>
                                    </div>
                                </div>
                                <div class=\\"flex items-center space-x-2\\">
                                    <span class=\\"px-3 py-1.5 text-xs font-bold rounded-lg ${assignment.status === 'assigned' ? 'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 dark:from-blue-900/30 dark:to-blue-800/30 dark:text-blue-300' : assignment.status === 'submitted' ? 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 dark:from-yellow-900/30 dark:to-amber-900/30 dark:text-yellow-300' : assignment.status === 'completed' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/30 dark:to-emerald-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'} shadow-sm\\">${assignment.status ? assignment.status.charAt(0).toUpperCase() + assignment.status.slice(1) : ''}</span>
                                </div>
                            </div>
                        `).join('') :
                        '<div class="text-center py-8"><p class="text-sm text-gray-500 dark:text-gray-400">No participants yet.</p></div>'
                    }
                </div>
                </div>
            `;

            // Wire modal tabs
            const mdTab = document.getElementById('modal-details-tab');
            const mpTab = document.getElementById('modal-participants-tab');
            const mdContent = document.getElementById('modal-details-content');
            const mpContent = document.getElementById('modal-participants-content');
            function setModalTab(tab){
                if(tab==='details'){
                    mdTab.classList.add('border-orange-500','text-orange-600','dark:text-orange-400');
                    mdTab.classList.remove('border-transparent','text-gray-500','dark:text-gray-400');
                    mpTab.classList.remove('border-orange-500','text-orange-600','dark:text-orange-400');
                    mpTab.classList.add('border-transparent','text-gray-500','dark:text-gray-400');
                    mdContent.classList.remove('hidden');
                    mpContent.classList.add('hidden');
                } else {
                    mpTab.classList.add('border-orange-500','text-orange-600','dark:text-orange-400');
                    mpTab.classList.remove('border-transparent','text-gray-500','dark:text-gray-400');
                    mdTab.classList.remove('border-orange-500','text-orange-600','dark:text-orange-400');
                    mdTab.classList.add('border-transparent','text-gray-500','dark:text-gray-400');
                    mpContent.classList.remove('hidden');
                    mdContent.classList.add('hidden');
                }
            }
            if(mdTab && mpTab){
                mdTab.addEventListener('click', ()=>setModalTab('details'));
                mpTab.addEventListener('click', ()=>setModalTab('participants'));
                setModalTab('details');
            }

            modal.classList.remove('hidden');
        }

        function closeTaskModal() {
            const modal = document.getElementById('task-modal');
            if (modal) modal.classList.add('hidden');
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeTaskModal();
        });
    </script>
</x-app-layout>
