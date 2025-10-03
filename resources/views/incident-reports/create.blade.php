<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Report User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">Submit Incident Report</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Please provide detailed information about the incident. False reports may result in account restrictions.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('incident-reports.store') }}">
                        @csrf

                        <!-- Reported User Selection -->
                        <div class="mb-6">
                            <x-input-label for="reported_user_id" :value="__('User to Report')" />
                            <div class="mt-1">
                                <input type="text" 
                                       id="user-search" 
                                       class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm" 
                                       placeholder="Search for user by name or email..."
                                       autocomplete="off">
                                <input type="hidden" name="reported_user_id" id="reported_user_id" value="{{ $reportedUser->userId ?? '' }}" required>
                                <div id="user-search-results" class="hidden mt-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto"></div>
                            </div>
                            @if($reportedUser)
                                <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ substr($reportedUser->firstName, 0, 1) }}{{ substr($reportedUser->lastName, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $reportedUser->fullName }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $reportedUser->email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <x-input-error class="mt-2" :messages="$errors->get('reported_user_id')" />
                        </div>

                        <!-- Task Selection (Optional) -->
                        <div class="mb-6">
                            <x-input-label for="task_id" :value="__('Related Task (Optional)')" />
                            <div class="mt-1">
                                <input type="text" 
                                       id="task-search" 
                                       class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm" 
                                       placeholder="Search for related task..."
                                       autocomplete="off">
                                <input type="hidden" name="task_id" id="task_id" value="{{ $task->taskId ?? '' }}">
                                <div id="task-search-results" class="hidden mt-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto"></div>
                            </div>
                            @if($task)
                                <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $task->title }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ Str::limit($task->description, 100) }}
                                    </div>
                                </div>
                            @endif
                            <x-input-error class="mt-2" :messages="$errors->get('task_id')" />
                        </div>

                        <!-- Incident Type -->
                        <div class="mb-6">
                            <x-input-label for="incident_type" :value="__('Incident Type')" />
                            <select id="incident_type" name="incident_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm" required>
                                <option value="">Select incident type...</option>
                                @foreach($incidentTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('incident_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('incident_type')" />
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm" 
                                      placeholder="Please provide a detailed description of the incident..."
                                      required>{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Minimum 10 characters. Be specific about what happened, when, and where.
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Evidence -->
                        <div class="mb-6">
                            <x-input-label for="evidence" :value="__('Additional Evidence (Optional)')" />
                            <textarea id="evidence" 
                                      name="evidence" 
                                      rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm" 
                                      placeholder="Any additional context, screenshots, or evidence...">{{ old('evidence') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Include any relevant context, links, or additional information that might help with the investigation.
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('evidence')" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('incident-reports.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // User search functionality
        let userSearchTimeout;
        const userSearchInput = document.getElementById('user-search');
        const userSearchResults = document.getElementById('user-search-results');
        const reportedUserIdInput = document.getElementById('reported_user_id');

        userSearchInput.addEventListener('input', function() {
            clearTimeout(userSearchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                userSearchResults.classList.add('hidden');
                return;
            }

            userSearchTimeout = setTimeout(() => {
                fetch(`{{ route('incident-reports.users.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(users => {
                        if (users.length > 0) {
                            userSearchResults.innerHTML = users.map(user => `
                                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-600 last:border-b-0" 
                                     onclick="selectUser(${user.userId}, '${user.firstName} ${user.lastName}', '${user.email}')">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                ${user.firstName.charAt(0)}${user.lastName.charAt(0)}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                ${user.firstName} ${user.lastName}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                ${user.email}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('');
                            userSearchResults.classList.remove('hidden');
                        } else {
                            userSearchResults.innerHTML = '<div class="p-3 text-sm text-gray-500 dark:text-gray-400">No users found</div>';
                            userSearchResults.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error searching users:', error);
                        userSearchResults.classList.add('hidden');
                    });
            }, 300);
        });

        function selectUser(userId, fullName, email) {
            reportedUserIdInput.value = userId;
            userSearchInput.value = fullName;
            userSearchResults.classList.add('hidden');
        }

        // Task search functionality
        let taskSearchTimeout;
        const taskSearchInput = document.getElementById('task-search');
        const taskSearchResults = document.getElementById('task-search-results');
        const taskIdInput = document.getElementById('task_id');

        taskSearchInput.addEventListener('input', function() {
            clearTimeout(taskSearchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                taskSearchResults.classList.add('hidden');
                return;
            }

            taskSearchTimeout = setTimeout(() => {
                fetch(`{{ route('incident-reports.tasks.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(tasks => {
                        if (tasks.length > 0) {
                            taskSearchResults.innerHTML = tasks.map(task => `
                                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-600 last:border-b-0" 
                                     onclick="selectTask(${task.taskId}, '${task.title}')">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        ${task.title}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        ${task.description ? task.description.substring(0, 100) + '...' : ''}
                                    </div>
                                </div>
                            `).join('');
                            taskSearchResults.classList.remove('hidden');
                        } else {
                            taskSearchResults.innerHTML = '<div class="p-3 text-sm text-gray-500 dark:text-gray-400">No tasks found</div>';
                            taskSearchResults.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error searching tasks:', error);
                        taskSearchResults.classList.add('hidden');
                    });
            }, 300);
        });

        function selectTask(taskId, title) {
            taskIdInput.value = taskId;
            taskSearchInput.value = title;
            taskSearchResults.classList.add('hidden');
        }

        // Hide search results when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#user-search') && !event.target.closest('#user-search-results')) {
                userSearchResults.classList.add('hidden');
            }
            if (!event.target.closest('#task-search') && !event.target.closest('#task-search-results')) {
                taskSearchResults.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>
