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
                            <select id="reported_user_id" name="reported_user_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm" required>
                                <option value="">Select a user to report...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->userId }}" {{ old('reported_user_id', $reportedUser->userId ?? '') == $user->userId ? 'selected' : '' }}>
                                        {{ $user->firstName }} {{ $user->lastName }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('reported_user_id')" />
                        </div>

                        <!-- Task Selection -->
                        <div class="mb-6">
                            <x-input-label for="task_id" :value="__('Related Task (Optional)')" />
                            <select id="task_id" name="task_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="">Select a related task (optional)...</option>
                                @foreach($tasks as $taskOption)
                                    <option value="{{ $taskOption->taskId }}" {{ old('task_id', $task->taskId ?? '') == $taskOption->taskId ? 'selected' : '' }}>
                                        {{ $taskOption->title }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Select a task if this incident is related to a specific task.
                            </p>
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

                        <!-- Debug Info -->
                        <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Form Debug Info:</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                Available Users: {{ $users->count() }}<br>
                                Available Tasks: {{ $tasks->count() }}<br>
                                Selected User ID: <span id="debug-user-id">{{ $reportedUser->userId ?? 'Not selected' }}</span><br>
                                Selected Task ID: <span id="debug-task-id">{{ $task->taskId ?? 'Not selected' }}</span>
                            </p>
                            <div class="mt-2">
                                <button type="button" onclick="testJavaScript()" class="bg-purple-500 hover:bg-purple-600 text-white text-xs px-3 py-1 rounded">
                                    Test JS
                                </button>
                                <button type="button" onclick="validateForm()" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded ml-2">
                                    Validate Form
                                </button>
                                <button type="button" onclick="fillTestData()" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded ml-2">
                                    Fill Test Data
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('incident-reports.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    id="submit-button">
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
        // Debug: Check if JavaScript is loading
        console.log('JavaScript loaded successfully!');

        // Form validation function
        function validateForm() {
            const reportedUserId = document.getElementById('reported_user_id').value;
            const taskId = document.getElementById('task_id').value;
            const incidentType = document.getElementById('incident_type').value;
            const description = document.getElementById('description').value;
            const submitButton = document.getElementById('submit-button');
            
            const isValid = reportedUserId && incidentType && description && description.length >= 10;
            
            if (isValid) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                submitButton.classList.add('hover:bg-red-700');
            } else {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                submitButton.classList.remove('hover:bg-red-700');
            }
            
            // Update debug info
            document.getElementById('debug-user-id').textContent = reportedUserId || 'Not selected';
            document.getElementById('debug-task-id').textContent = taskId || 'Not selected';
            
            console.log('Form validation:', {
                reportedUserId: reportedUserId || 'MISSING',
                taskId: taskId || 'MISSING',
                incidentType: incidentType || 'MISSING',
                descriptionLength: description.length,
                isValid: isValid
            });
        }
        
        // Add event listeners for form validation
        document.getElementById('reported_user_id').addEventListener('change', validateForm);
        document.getElementById('task_id').addEventListener('change', validateForm);
        document.getElementById('incident_type').addEventListener('change', validateForm);
        document.getElementById('description').addEventListener('input', validateForm);
        
        // Initial validation
        validateForm();
        
        // Form submission handler
        document.querySelector('form').addEventListener('submit', function(e) {
            const reportedUserId = document.getElementById('reported_user_id').value;
            const taskId = document.getElementById('task_id').value;
            const incidentType = document.getElementById('incident_type').value;
            const description = document.getElementById('description').value;
            
            console.log('Form submission attempt:', {
                reportedUserId: reportedUserId || 'MISSING',
                taskId: taskId || 'MISSING',
                incidentType: incidentType || 'MISSING',
                descriptionLength: description.length,
                formData: new FormData(this)
            });
            
            // Only prevent submission if critical fields are missing
            if (!reportedUserId || !incidentType || !description || description.length < 10) {
                e.preventDefault();
                alert('Please fill in all required fields:\n- Select a user to report\n- Choose an incident type\n- Provide a description (minimum 10 characters)');
                return false;
            }
            
            // Show loading state
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
        });
        
        // Test if JavaScript is working
        function testJavaScript() {
            try {
                console.log('JavaScript test function called');
                
                // Test basic DOM access
                const debugUserId = document.getElementById('debug-user-id');
                const debugTaskId = document.getElementById('debug-task-id');
                
                console.log('DOM elements found:', {
                    debugUserId: debugUserId ? 'YES' : 'NO',
                    debugTaskId: debugTaskId ? 'YES' : 'NO'
                });
                
                // Test form elements
                const reportedUserIdInput = document.getElementById('reported_user_id');
                const taskIdInput = document.getElementById('task_id');
                const incidentTypeInput = document.getElementById('incident_type');
                const descriptionInput = document.getElementById('description');
                
                console.log('Form elements found:', {
                    reportedUserIdInput: reportedUserIdInput ? 'YES' : 'NO',
                    taskIdInput: taskIdInput ? 'YES' : 'NO',
                    incidentTypeInput: incidentTypeInput ? 'YES' : 'NO',
                    descriptionInput: descriptionInput ? 'YES' : 'NO'
                });
                
                // Test current values
                console.log('Current form values:', {
                    reportedUserId: reportedUserIdInput ? reportedUserIdInput.value : 'NOT FOUND',
                    taskId: taskIdInput ? taskIdInput.value : 'NOT FOUND',
                    incidentType: incidentTypeInput ? incidentTypeInput.value : 'NOT FOUND',
                    description: descriptionInput ? descriptionInput.value : 'NOT FOUND'
                });
                
                // Test dropdown options
                if (reportedUserIdInput) {
                    console.log('User dropdown options:', reportedUserIdInput.options.length);
                    for (let i = 0; i < reportedUserIdInput.options.length; i++) {
                        console.log(`Option ${i}: ${reportedUserIdInput.options[i].text} (value: ${reportedUserIdInput.options[i].value})`);
                    }
                }
                
                if (taskIdInput) {
                    console.log('Task dropdown options:', taskIdInput.options.length);
                    for (let i = 0; i < taskIdInput.options.length; i++) {
                        console.log(`Option ${i}: ${taskIdInput.options[i].text} (value: ${taskIdInput.options[i].value})`);
                    }
                }
                
                alert('JavaScript test completed! Check console for detailed information.');
                
            } catch (error) {
                console.error('JavaScript test error:', error);
                alert('JavaScript error: ' + error.message);
            }
        }
        
        // Fill test data for form testing
        function fillTestData() {
            try {
                console.log('Filling test data...');
                
                const reportedUserIdInput = document.getElementById('reported_user_id');
                const taskIdInput = document.getElementById('task_id');
                const incidentTypeInput = document.getElementById('incident_type');
                const descriptionInput = document.getElementById('description');
                
                // Select first available user (skip the empty option)
                if (reportedUserIdInput && reportedUserIdInput.options.length > 1) {
                    reportedUserIdInput.selectedIndex = 1; // Select first actual user
                    console.log('Selected user:', reportedUserIdInput.options[reportedUserIdInput.selectedIndex].text);
                }
                
                // Select first available task (skip the empty option)
                if (taskIdInput && taskIdInput.options.length > 1) {
                    taskIdInput.selectedIndex = 1; // Select first actual task
                    console.log('Selected task:', taskIdInput.options[taskIdInput.selectedIndex].text);
                }
                
                // Select first incident type (skip the empty option)
                if (incidentTypeInput && incidentTypeInput.options.length > 1) {
                    incidentTypeInput.selectedIndex = 1; // Select first incident type
                    console.log('Selected incident type:', incidentTypeInput.options[incidentTypeInput.selectedIndex].text);
                }
                
                // Fill description
                if (descriptionInput) {
                    descriptionInput.value = 'This is a test incident report description with more than 10 characters to meet the minimum requirement.';
                    console.log('Filled description:', descriptionInput.value);
                }
                
                // Trigger validation
                validateForm();
                
                alert('Test data filled! Form should now be ready for submission.');
                
            } catch (error) {
                console.error('Error filling test data:', error);
                alert('Error filling test data: ' + error.message);
            }
        }
    </script>
    @endpush
</x-app-layout>
