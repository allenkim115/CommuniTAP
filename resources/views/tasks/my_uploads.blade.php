<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Uploaded Tasks') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('tasks.creator.submissions') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Review Submissions
                </a>
                <a href="{{ route('tasks.create') }}" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                    + Add Task
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <x-session-toast />

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                @php $labels = ['pending','live','rejected','completed','all']; @endphp
                @foreach($labels as $label)
                <a href="{{ route('tasks.my-uploads', array_filter(['status' => $label === 'all' ? 'all' : $label, 'q' => $search ?? null])) }}"
                   class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border @class(['border-orange-500' => ($activeStatus ?? 'all') === $label])">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ ucfirst($label) }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats[$label] }}</p>
                </a>
                @endforeach
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('tasks.my-uploads') }}" class="mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-3 sm:space-y-0">
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Status</label>
                        <select id="status" name="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm">
                            @php $options = ['all' => 'All', 'pending' => 'Pending', 'live' => 'Live', 'rejected' => 'Rejected', 'completed' => 'Completed']; @endphp
                            @foreach($options as $value => $text)
                                <option value="{{ $value }}" {{ ($activeStatus ?? 'all') === $value ? 'selected' : '' }}>{{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="q" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" id="q" name="q" value="{{ $search ?? '' }}" placeholder="Search title, description, or location" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm" />
                    </div>
                    <div class="pt-5 sm:pt-0">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Apply</button>
                    </div>
                </div>
            </form>

            @if($uploads->count() === 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 text-center">
                    <p class="text-gray-600 dark:text-gray-300">You haven't uploaded any tasks yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($uploads as $task)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 transition-all duration-200 hover:shadow-md hover:bg-gray-50 dark:hover:bg-gray-700/60 cursor-pointer task-card" 
                         data-task-id="{{ $task->taskId }}"
                         onclick="openTaskModal({{ $task->taskId }})">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $task->title }}</h3>
                            @php
                                $isLive = in_array($task->status, ['approved','published']);
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @class([
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => $task->status === 'pending',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $isLive || $task->status === 'completed',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $task->status === 'rejected',
                                ])
                            ">
                                {{ $isLive ? 'Live' : ucfirst($task->status) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 mb-3">{{ $task->description }}</p>

                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <p>
                                <strong>Date:</strong>
                                @if($task->due_date)
                                    {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                @else
                                    {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                @endif
                            </p>
                            <p><strong>Points:</strong> {{ $task->points_awarded }}</p>
                            <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}</p>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="{{ route('tasks.show', $task) }}" onclick="event.stopPropagation();" class="text-orange-600 hover:text-orange-700 text-sm font-medium">View</a>
                            <div class="flex items-center space-x-2" onclick="event.stopPropagation();">
                                @php $canEdit = in_array($task->status, ['pending','rejected']); @endphp
                                @if($canEdit)
                                    <a href="{{ route('tasks.edit', $task) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded">Edit</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Deactivate this task?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">Deactivate</button>
                                    </form>
                                @else
                                    <button class="px-3 py-1 bg-gray-300 text-gray-600 text-xs rounded cursor-not-allowed" title="Editing disabled after approval" disabled>Locked</button>
                                @endif
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
        <div class="absolute inset-0 bg-black/50" onclick="closeTaskModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <div class="px-6 py-0" style="display:none"></div>
                <div id="task-modal-content" class="p-6 space-y-4">
                    <!-- Dynamic content -->
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-2">
                    <a id="task-modal-view-link" href="#" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 rounded-md text-sm">Open full page</a>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-md text-sm" onclick="closeTaskModal()">Close</button>
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
                            <button id="modal-details-tab" class="py-4 px-1 border-b-2 border-orange-500 font-medium text-sm text-orange-600 dark:text-orange-400">Details</button>
                            <button id="modal-participants-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Participants</button>
                        </nav>
                        <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white py-4" onclick="closeTaskModal()" aria-label="Close modal">✕</button>
                    </div>
                </div>
                <div class="pt-6">
                <div id="modal-details-content">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">${task.title}</h2>
                    <div class="mb-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            ${task.status === 'pending' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' :
                              isLive || task.status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                              task.status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                              'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'}">
                            ${isLive ? 'Live' : task.status.charAt(0).toUpperCase() + task.status.slice(1)}
                        </span>
                    </div>
                    <div class="space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Uploaded by:</strong> ${uploaderName}</p>
                    <p class="text-gray-700 dark:text-gray-300">${task.description || ''}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Date:</strong> ${dateText}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Time:</strong> ${timeText}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Location:</strong> ${task.location || 'Community'}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm8 0a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Type:</strong> ${task.task_type ? task.task_type.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase()) : ''}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Points:</strong> ${((task.points_awarded !== undefined && task.points_awarded !== null) ? task.points_awarded : '')}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Created:</strong> ${task.published_date ? (typeof task.published_date === 'string' ? new Date(task.published_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.published_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })) : (typeof task.creation_date === 'string' ? new Date(task.creation_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.creation_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }))}</span>
                        </div>
                    </div>
                    </div>
                </div>
                <div id="modal-participants-content" class="hidden">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Participants ${task.assignments && task.assignments.length ? `(${task.assignments.length})` : ''}</h4>
                    ${task.assignments && task.assignments.length > 0 ?
                        task.assignments.map(assignment => `
                            <div class="flex items-center justify-between p-3 mb-2 last:mb-0 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">${assignment.user && assignment.user.name ? assignment.user.name.substring(0,2).toUpperCase() : 'U'}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">${assignment.user && assignment.user.name ? assignment.user.name : 'Unknown User'}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">${assignment.user && assignment.user.email ? assignment.user.email : ''}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full ${assignment.status === 'assigned' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : assignment.status === 'submitted' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : assignment.status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'}">${assignment.status ? assignment.status.charAt(0).toUpperCase() + assignment.status.slice(1) : ''}</span>
                                </div>
                            </div>
                        `).join('') :
                        '<p class="text-sm text-gray-500 dark:text-gray-400">No participants yet.</p>'
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
