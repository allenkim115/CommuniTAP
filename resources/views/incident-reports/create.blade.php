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

                    <form method="POST" action="{{ route('incident-reports.store') }}" enctype="multipart/form-data">
                        @csrf

						<!-- Reported User Autocomplete -->
						<div class="mb-6">
							<x-input-label for="reported_user_search" :value="__('User to Report')" />
							<input id="reported_user_search" type="text" placeholder="Type a name or email..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm" autocomplete="off" value="{{ isset($reportedUser) ? ($reportedUser->firstName.' '.$reportedUser->lastName.' ('.$reportedUser->email.')') : '' }}">
							<input type="hidden" id="reported_user_id" name="reported_user_id" value="{{ old('reported_user_id', $reportedUser->userId ?? '') }}">
							<div id="reported_user_suggestions" class="relative">
								<ul class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg max-h-60 overflow-auto hidden"></ul>
							</div>
							<x-input-error class="mt-2" :messages="$errors->get('reported_user_id')" />
						</div>

						<!-- Task Autocomplete -->
						<div class="mb-6">
						<x-input-label for="task_search" :value="__('Related Task')" />
							<input id="task_search" type="text" placeholder="Type task title..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 sm:text-sm" autocomplete="off" value="{{ isset($task) ? $task->title : '' }}">
							<input type="hidden" id="task_id" name="task_id" value="{{ old('task_id', $task->taskId ?? '') }}">
							<div id="task_suggestions" class="relative">
								<ul class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg max-h-60 overflow-auto hidden"></ul>
							</div>
						<p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
							Select the specific task related to this incident.
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

                        <!-- Evidence Images Upload (Optional) -->
                        <div class="mb-6">
                            <x-input-label for="evidence_images" :value="__('Additional Evidence (Optional)')" />
                            <input type="file" id="evidence_images" name="evidence_images[]" multiple accept="image/*" class="hidden" aria-describedby="evidence-help">
                            <div id="evidence-upload-area" class="mt-1 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors cursor-pointer">
                                <svg class="w-10 h-10 text-red-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <p id="evidence-upload-text" class="text-sm text-gray-600 dark:text-gray-400 mb-1">Click to select photos or drag and drop (up to 3)</p>
                                <p id="evidence-help" class="text-xs text-gray-500 dark:text-gray-400">Accepted: jpeg, png, jpg, gif. Max 3 images.</p>
                            </div>
                            @error('evidence_images')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @error('evidence_images.*')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div id="evidence-selected-files" class="mt-3 hidden">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Files:</h4>
                                <div id="evidence-file-list" class="space-y-2 grid grid-cols-3 gap-2"></div>
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
		// JS loaded for incident report form

        // Form validation function
        function validateForm() {
            const reportedUserId = document.getElementById('reported_user_id').value;
            const taskId = document.getElementById('task_id').value;
            const incidentType = document.getElementById('incident_type').value;
            const description = document.getElementById('description').value;
            const submitButton = document.getElementById('submit-button');
            
            const isValid = reportedUserId && incidentType && description && description.length >= 10 && document.getElementById('task_id').value;
            
            if (isValid) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                submitButton.classList.add('hover:bg-red-700');
            } else {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                submitButton.classList.remove('hover:bg-red-700');
            }
            
			// Silent validation (no debug UI)
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
            if (!reportedUserId || !incidentType || !description || description.length < 10 || !taskId) {
                e.preventDefault();
                showAlertModal('Please fill in all required fields:\n- Select a user to report\n- Select the related task\n- Choose an incident type\n- Provide a description (minimum 10 characters)', 'Validation Error', 'warning');
                return false;
            }
            
            // Show loading state
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
        });
        
		// Simple debounce helper
		function debounce(fn, delay) {
			let timer;
			return function(...args) {
				clearTimeout(timer);
				timer = setTimeout(() => fn.apply(this, args), delay);
			};
		}

		function renderSuggestions(containerUl, items, formatItem, onSelect) {
			containerUl.innerHTML = '';
			if (!items || items.length === 0) {
				containerUl.classList.add('hidden');
				return;
			}
			items.forEach(item => {
				const li = document.createElement('li');
				li.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-sm';
				li.textContent = formatItem(item);
				li.addEventListener('click', () => onSelect(item));
				containerUl.appendChild(li);
			});
			containerUl.classList.remove('hidden');
		}

		// Autocomplete: Users
		(function initUserAutocomplete(){
			const input = document.getElementById('reported_user_search');
			const hidden = document.getElementById('reported_user_id');
			const list = document.querySelector('#reported_user_suggestions ul');
			const search = debounce(async (q) => {
				if (!q || q.length < 2) { list.classList.add('hidden'); return; }
				try {
			// Search users
					const res = await fetch(`{{ route('incident-reports.users.search') }}?q=${encodeURIComponent(q)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
					const data = await res.json();
					// render results
					renderSuggestions(list, data, (u) => `${u.firstName} ${u.lastName} (${u.email})`, (u) => {
						input.value = `${u.firstName} ${u.lastName} (${u.email})`;
						hidden.value = u.userId;
						list.classList.add('hidden');
						validateForm();
					});
				} catch (e) { /* swallow */ }
			}, 200);
			input.addEventListener('input', (e) => { hidden.value = ''; search(e.target.value); validateForm(); });
			input.addEventListener('blur', () => setTimeout(() => list.classList.add('hidden'), 150));
		})();

		// Autocomplete: Tasks
		(function initTaskAutocomplete(){
			const input = document.getElementById('task_search');
			const hidden = document.getElementById('task_id');
			const list = document.querySelector('#task_suggestions ul');
			const search = debounce(async (q) => {
				if (!q || q.length < 2) { list.classList.add('hidden'); return; }
				try {
			// Search tasks
					const res = await fetch(`{{ route('incident-reports.tasks.search') }}?q=${encodeURIComponent(q)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
					const data = await res.json();
					// render results
					renderSuggestions(list, data, (t) => `${t.title}`, (t) => {
						input.value = t.title;
						hidden.value = t.taskId;
						list.classList.add('hidden');
						validateForm();
					});
				} catch (e) { /* swallow */ }
			}, 200);
			input.addEventListener('input', (e) => { if (!e.target.value) hidden.value=''; search(e.target.value); });
			input.addEventListener('blur', () => setTimeout(() => list.classList.add('hidden'), 150));
		})();

		// Removed debug/testing helpers

		// Evidence image uploader (up to 3 images) similar to task submission
		(function initEvidenceUploader(){
			const input = document.getElementById('evidence_images');
			const area = document.getElementById('evidence-upload-area');
			const text = document.getElementById('evidence-upload-text');
			const selectedContainer = document.getElementById('evidence-selected-files');
			const fileList = document.getElementById('evidence-file-list');
			const MAX_FILES = 3;
			let filesState = [];

			if (!area || !input) return;

			area.addEventListener('click', () => input.click());
			area.addEventListener('dragover', (e) => { e.preventDefault(); area.classList.add('border-blue-400','bg-blue-50','dark:bg-blue-900/20'); });
			area.addEventListener('dragleave', (e) => { e.preventDefault(); area.classList.remove('border-blue-400','bg-blue-50','dark:bg-blue-900/20'); });
			area.addEventListener('drop', (e) => {
				e.preventDefault();
				area.classList.remove('border-blue-400','bg-blue-50','dark:bg-blue-900/20');
				mergeAndRender(Array.from(e.dataTransfer.files || []).filter(f => f.type.startsWith('image/')));
			});

			input.addEventListener('change', () => mergeAndRender(Array.from(input.files || [])));

			function mergeAndRender(newFiles){
				filesState = mergeFiles(filesState, newFiles).slice(0, MAX_FILES);
				render();
			}

			function render(){
				text.textContent = `${filesState.length} photo(s) selected`;
				selectedContainer.classList.toggle('hidden', filesState.length === 0);
				fileList.innerHTML = '';
				filesState.forEach((file, index) => {
					const wrapper = document.createElement('div');
					wrapper.className = 'relative group overflow-hidden rounded border border-gray-200 dark:border-gray-700';
					const url = URL.createObjectURL(file);
					wrapper.innerHTML = `<img src="${url}" alt="evidence" class="w-full h-24 object-cover" />
					<button type="button" data-index="${index}" class="absolute top-1 right-1 bg-black/60 hover:bg-black/80 text-white text-xs rounded px-2 py-1">×</button>`;
					fileList.appendChild(wrapper);
				});
				fileList.querySelectorAll('button[data-index]').forEach(btn => btn.addEventListener('click', () => {
					const idx = Number(btn.getAttribute('data-index'));
					filesState.splice(idx,1);
					setInputFiles(input, filesState);
					render();
				}));
				setInputFiles(input, filesState);
			}

			function setInputFiles(target, files){
				const dt = new DataTransfer();
				files.forEach(f => dt.items.add(f));
				target.files = dt.files;
			}

			function mergeFiles(existing, incoming){
				const out = [...existing];
				const sig = (f) => `${f.name}|${f.size}`;
				const seen = new Set(out.map(sig));
				for (const f of incoming){
					if (!f.type.startsWith('image/')) continue;
					const s = sig(f);
					if (seen.has(s)) continue;
					out.push(f);
					seen.add(s);
					if (out.length >= MAX_FILES) break;
				}
				return out;
			}
		})();
    </script>
    @endpush
</x-app-layout>
