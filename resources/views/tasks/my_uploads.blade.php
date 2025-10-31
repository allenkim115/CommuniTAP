<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Uploaded Tasks') }}
            </h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('tasks.creator.submissions') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-500 hover:bg-green-600 active:bg-green-700 text-white font-semibold text-sm shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400 dark:focus:ring-offset-gray-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Review Submissions
                </a>
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold text-sm shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400 dark:focus:ring-offset-gray-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Task
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
                   class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-5 text-center shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition
                   ring-1 ring-gray-100 dark:ring-gray-700 @class(['ring-2 ring-orange-400' => ($activeStatus ?? 'all') === $label])">
                    <p class="text-[11px] uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">{{ ucfirst($label) }}</p>
                    <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats[$label] }}</p>
                </a>
                @endforeach
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('tasks.my-uploads') }}" class="mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 md:p-5 flex flex-col sm:flex-row sm:items-end sm:space-x-4 space-y-3 sm:space-y-0 ring-1 ring-gray-100 dark:ring-gray-700">
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Status</label>
                        <select id="status" name="status" class="h-11 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            @php $options = ['all' => 'All', 'pending' => 'Pending', 'live' => 'Live', 'rejected' => 'Rejected', 'completed' => 'Completed']; @endphp
                            @foreach($options as $value => $text)
                                <option value="{{ $value }}" {{ ($activeStatus ?? 'all') === $value ? 'selected' : '' }}>{{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="q" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" id="q" name="q" value="{{ $search ?? '' }}" placeholder="Search title, description, or location" class="w-full h-11 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400" />
                    </div>
                    <div class="pt-1 sm:pt-0">
                        <button type="submit" class="inline-flex items-center justify-center h-11 px-5 rounded-lg bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400 dark:focus:ring-offset-gray-900">Apply</button>
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
                    <div class="group bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-200 ring-1 ring-gray-100 dark:ring-gray-700 transform hover:-translate-y-0.5">
                        <!-- Header with title and badge to match Task Management -->
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-900 dark:text-white text-lg leading-snug">{{ $task->title }}</h4>
                            @php $isLive = in_array($task->status, ['approved','published']); @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @class([
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $isLive || $task->status === 'completed',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => $task->status === 'pending',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $task->status === 'rejected',
                                ])
                            ">
                                {{ $isLive ? 'Live' : ucfirst($task->status) }}
                            </span>
                        </div>

                        <!-- Uploader + Task Date -->
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>Uploaded by:</strong> {{ auth()->user()->name ?? 'You' }} •
                                @if($task->due_date)
                                    {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                @else
                                    {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                @endif
                            </span>
                        </div>

                        <!-- Action Link -->
                        <div class="flex items-center justify-between mt-3">
                            <a href="#" onclick="openUploadTaskModal({{ $task->taskId }}); return false;" class="inline-flex items-center gap-1 text-orange-600 text-sm font-medium">
                                <span>See more details</span>
                                <span class="transition transform">→</span>
                            </a>
                            <div class="flex items-center gap-3">
                                @if($task->status === 'inactive')
                                    <form action="{{ route('tasks.reactivate', $task) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">Reactivate</button>
                                    </form>
                                @else
                                    @php $canEdit = !in_array($task->status, ['approved','published','completed','inactive']); @endphp
                                    @if($canEdit)
                                        <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Deactivate this task proposal?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Deactivate</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Modal: Uploaded Task Details -->
    <div id="uploads-task-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-black/50" onclick="closeUploadTaskModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <div id="uploads-task-modal-content" class="p-6 space-y-4"></div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-2">
                    <a id="uploads-task-modal-view-link" href="#" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 rounded-md text-sm">Open full page</a>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-md text-sm" onclick="closeUploadTaskModal()">Close</button>
                </div>
            </div>
        </div>
    </div>
 </x-app-layout>

<script id="uploads-json" type="application/json">{!! $uploads->keyBy('taskId')->toJson() !!}</script>

<script>
    // Prepare uploads data for JS access
    const uploadsDataEl = document.getElementById('uploads-json');
    const uploadsData = uploadsDataEl ? JSON.parse(uploadsDataEl.textContent || '{}') : {};

    function openUploadTaskModal(taskId){
        const task = uploadsData[taskId];
        if(!task) return;

        const modal = document.getElementById('uploads-task-modal');
        const contentEl = document.getElementById('uploads-task-modal-content');
        const viewLink = document.getElementById('uploads-task-modal-view-link');

        // Compute uploader name
        const uploaderName = (() => {
            const u = task.assigned_user || task.uploader || null;
            if (!u) return 'You';
            const computed = u.name || [u.firstName, u.middleName, u.lastName].filter(Boolean).join(' ').trim();
            return computed && computed.length > 0 ? computed : 'Admin';
        })();

        // Compute date
        const dateText = task.due_date
            ? (typeof task.due_date === 'string' ? new Date(task.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : new Date(task.due_date.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }))
            : (typeof task.creation_date === 'string' ? new Date(task.creation_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : (task.creation_date && task.creation_date.date ? new Date(task.creation_date.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Not specified'));

        // Compute time
        const timeText = (task.start_time && task.end_time)
            ? (new Date('2000-01-01T' + task.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) + ' - ' + new Date('2000-01-01T' + task.end_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }))
            : (task.start_time ? (new Date('2000-01-01T' + task.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) + ' onwards') : 'Flexible');

        // Compute created date
        const createdDateText = task.created_at || task.creation_date
            ? (typeof (task.created_at || task.creation_date) === 'string' 
                ? new Date(task.created_at || task.creation_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                : new Date((task.created_at || task.creation_date).date || task.created_at || task.creation_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }))
            : 'Not specified';

        // Compute task type (formatted as "User Uploaded")
        const taskTypeText = task.task_type ? task.task_type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ') : 'Not specified';

        viewLink.href = `/tasks/${taskId}`;

        // Modal content with tabs (Details / Participants)
        contentEl.innerHTML = `
            <div class="border-b border-gray-200 dark:border-gray-700 -mt-2 -mx-6 px-6">
                <div class="flex items-center justify-between">
                    <nav class="flex space-x-8" aria-label="Tabs">
                        <button id="uploads-details-tab" class="py-4 px-1 border-b-2 border-orange-500 font-medium text-sm text-orange-600 dark:text-orange-400">Details</button>
                        <button id="uploads-participants-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Participants</button>
                    </nav>
                    <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white py-4" onclick="closeUploadTaskModal()" aria-label="Close modal">✕</button>
                </div>
            </div>
            <div class="pt-6">
                <div id="uploads-details-content" class="space-y-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">${task.title || ''}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Uploaded by:</strong> ${uploaderName}</p>
                    <p class="text-gray-700 dark:text-gray-300">${task.description || ''}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Date:</strong> ${dateText}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Time:</strong> ${timeText}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Location:</strong> ${task.location || 'Community'}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Points:</strong> ${((task.points_awarded !== undefined && task.points_awarded !== null) ? task.points_awarded : '')}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Type:</strong> ${taskTypeText}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Created:</strong> ${createdDateText}</span>
                        </div>
                    </div>
                </div>
                <div id="uploads-participants-content" class="hidden">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Participants ${task.assignments && task.assignments.length ? `(${task.assignments.length})` : '(0)'}</h4>
                    ${task.assignments && task.assignments.length > 0 ?
                        task.assignments.map(assignment => {
                            const userName = assignment.user && assignment.user.name ? assignment.user.name : 'Unknown User';
                            const userEmail = assignment.user && assignment.user.email ? assignment.user.email : '';
                            const initials = userName.substring(0, 2).toUpperCase();
                            const status = assignment.status || '';
                            const statusClass = status === 'assigned' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                              status === 'submitted' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                              status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                              'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                            const statusText = status ? status.charAt(0).toUpperCase() + status.slice(1) : '';
                            return `
                                <div class="flex items-center justify-between p-3 mb-2 last:mb-0 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">${initials}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">${userName}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">${userEmail}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs rounded-full ${statusClass}">${statusText}</span>
                                    </div>
                                </div>
                            `;
                        }).join('') :
                        '<p class="text-sm text-gray-500 dark:text-gray-400">No participants yet.</p>'
                    }
                </div>
            </div>
        `;

        // Wire up tabs
        const dTab = document.getElementById('uploads-details-tab');
        const pTab = document.getElementById('uploads-participants-tab');
        const dContent = document.getElementById('uploads-details-content');
        const pContent = document.getElementById('uploads-participants-content');
        
        function setTab(tab){
            if(tab==='details'){
                dTab.classList.add('border-orange-500','text-orange-600','dark:text-orange-400');
                dTab.classList.remove('border-transparent','text-gray-500','dark:text-gray-400');
                pTab.classList.remove('border-orange-500','text-orange-600','dark:text-orange-400');
                pTab.classList.add('border-transparent','text-gray-500','dark:text-gray-400');
                dContent.classList.remove('hidden');
                pContent.classList.add('hidden');
            } else {
                pTab.classList.add('border-orange-500','text-orange-600','dark:text-orange-400');
                pTab.classList.remove('border-transparent','text-gray-500','dark:text-gray-400');
                dTab.classList.remove('border-orange-500','text-orange-600','dark:text-orange-400');
                dTab.classList.add('border-transparent','text-gray-500','dark:text-gray-400');
                pContent.classList.remove('hidden');
                dContent.classList.add('hidden');
            }
        }
        
        if(dTab && pTab){
            dTab.addEventListener('click', ()=>setTab('details'));
            pTab.addEventListener('click', ()=>setTab('participants'));
            setTab('details');
        }

        modal.classList.remove('hidden');
    }

    function closeUploadTaskModal(){
        const modal = document.getElementById('uploads-task-modal');
        if(modal) modal.classList.add('hidden');
    }

    // Close on ESC
    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape') closeUploadTaskModal();
    });
</script>

