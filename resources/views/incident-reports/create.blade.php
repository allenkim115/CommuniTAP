<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Report User') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-surface p-4 sm:p-6 lg:p-8 text-gray-900 dark:text-gray-100">
                <div class="border-b border-gray-100 dark:border-gray-700 pb-4 sm:pb-6">
                    <p class="section-heading text-xs sm:text-sm">Safety first</p>
                    <h3 class="mt-2 text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">Submit an incident report</h3>
                    <p class="mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                        Share exactly what happened so our moderators can respond quickly. False or misleading information may lead to account restrictions.
                    </p>
                    <div class="mt-4 sm:mt-6 grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-3">
                        <div class="rounded-2xl border border-brand-peach/50 bg-brand-peach/20 p-3 sm:p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-brand-orange-dark">Step 1</p>
                            <p class="mt-1 text-xs sm:text-sm font-medium text-gray-900 dark:text-white">Select user & task</p>
                        </div>
                        <div class="rounded-2xl border border-brand-teal/40 bg-brand-teal/5 p-3 sm:p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-brand-teal-dark">Step 2</p>
                            <p class="mt-1 text-xs sm:text-sm font-medium text-gray-900 dark:text-white">Describe the incident</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-3 sm:p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Step 3</p>
                            <p class="mt-1 text-xs sm:text-sm font-medium text-gray-900 dark:text-white">Attach evidence (optional)</p>
                        </div>
                    </div>
                </div>

                <form
                    id="incident-report-form"
                    method="POST"
                    action="{{ route('incident-reports.store') }}"
                    enctype="multipart/form-data"
                    class="mt-6 sm:mt-8 space-y-6 sm:space-y-8"
                    novalidate
                >
                    @csrf

                    <section class="space-y-4 sm:space-y-6">
                        <div>
                            <div class="flex items-center justify-between flex-wrap gap-1">
                                <x-input-label for="reported_user_search" :value="__('User to Report')" class="text-sm sm:text-base" />
                                <span class="text-xs text-gray-500">Required</span>
                            </div>
                            <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">Start typing their name or email. We'll only show active users.</p>
                            <input
                                id="reported_user_search"
                                type="text"
                                autocomplete="off"
                                placeholder="Type a name or email..."
                                value="{{ isset($reportedUser) ? ($reportedUser->firstName.' '.$reportedUser->lastName.' ('.$reportedUser->email.')') : '' }}"
                                class="mt-2 block w-full rounded-2xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-teal focus:ring-brand-teal text-sm px-3 sm:px-4 py-2 sm:py-2.5"
                            >
                            <input type="hidden" id="reported_user_id" name="reported_user_id" value="{{ old('reported_user_id', $reportedUser->userId ?? '') }}">
                            <div id="reported_user_suggestions" class="relative">
                                <ul class="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg hidden"></ul>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('reported_user_id')" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between flex-wrap gap-1">
                                <x-input-label for="task_search" :value="__('Related Task')" class="text-sm sm:text-base" />
                                <span class="text-xs text-gray-500">Required</span>
                            </div>
                            <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">Link the task tied to the incident so moderators can cross-check submissions.</p>
                            <input
                                id="task_search"
                                type="text"
                                autocomplete="off"
                                placeholder="Type task title..."
                                value="{{ isset($task) ? $task->title : '' }}"
                                class="mt-2 block w-full rounded-2xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-teal focus:ring-brand-teal text-sm px-3 sm:px-4 py-2 sm:py-2.5"
                            >
                            <input type="hidden" id="task_id" name="task_id" value="{{ old('task_id', $task->taskId ?? '') }}">
                            <div id="task_suggestions" class="relative">
                                <ul class="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg hidden"></ul>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('task_id')" />
                        </div>

                
                    </section>

                    <section class="space-y-4 sm:space-y-6">
                        <div>
                            <x-input-label for="incident_type" :value="__('Incident Type')" class="text-sm sm:text-base" />
                            <select
                                id="incident_type"
                                name="incident_type"
                                required
                                class="mt-2 block w-full rounded-2xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-teal focus:ring-brand-teal text-sm px-3 sm:px-4 py-2 sm:py-2.5"
                            >
                                <option value="">Select incident type...</option>
                                @foreach($incidentTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('incident_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('incident_type')" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between flex-wrap gap-1">
                                <x-input-label for="description" :value="__('Description')" class="text-sm sm:text-base" />
                                <span class="text-xs text-gray-500">Min. 10 characters</span>
                            </div>
                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                placeholder="Describe exactly what happened, when, and where. Include direct quotes if available."
                                class="mt-2 block w-full rounded-2xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-teal focus:ring-brand-teal text-sm px-3 sm:px-4 py-2 sm:py-2.5"
                                required
                            >{{ old('description') }}</textarea>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Be factual and avoid assumptions. Moderators can follow up if they need clarification.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    </section>

                    <section class="space-y-4">
                        <div class="flex items-center justify-between flex-wrap gap-1">
                            <x-input-label for="evidence_images" :value="__('Additional Evidence (Optional)')" class="text-sm sm:text-base" />
                            <span class="text-xs text-gray-500">Up to 3 images</span>
                        </div>
                        <input type="file" id="evidence_images" name="evidence_images[]" multiple accept="image/*" class="hidden" aria-describedby="evidence-help">
                        <div id="evidence-upload-area" class="rounded-3xl border-2 border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900/40 p-4 sm:p-6 text-center transition cursor-pointer">
                            <svg class="mx-auto h-8 w-8 sm:h-10 sm:w-10 text-brand-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <p id="evidence-upload-text" class="mt-2 sm:mt-3 text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100 px-2">Click to select photos or drag and drop (up to 3)</p>
                            <p id="evidence-help" class="mt-1 text-xs text-gray-500 dark:text-gray-400 px-2">Accepted: jpeg, png, jpg, gif • Max 5MB each</p>
                        </div>
                        @error('evidence_images')
                            <p class="text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @error('evidence_images.*')
                            <p class="text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <div id="evidence-selected-files" class="hidden">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Selected files</h4>
                            <div id="evidence-file-list" class="grid grid-cols-1 gap-3 sm:grid-cols-3"></div>
                        </div>
                    </section>

                    <div class="flex flex-col gap-3 border-t border-gray-100 dark:border-gray-700 pt-4 sm:pt-6 sm:flex-row sm:items-center sm:justify-end">
                        <a href="{{ route('incident-reports.index') }}" class="btn-muted text-sm sm:text-base w-full sm:w-auto order-2 sm:order-1">
                            Cancel
                        </a>
                        <button type="submit" class="btn-brand text-sm sm:text-base w-full sm:w-auto order-1 sm:order-2" id="submit-button">
                            Submit report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const form = document.getElementById('incident-report-form');
            if (form) {
                const submitButton = document.getElementById('submit-button');
                const reportedUserIdInput = document.getElementById('reported_user_id');
                const taskIdInput = document.getElementById('task_id');
                const incidentTypeInput = document.getElementById('incident_type');
                const descriptionInput = document.getElementById('description');

                [reportedUserIdInput, taskIdInput, incidentTypeInput, descriptionInput].forEach((field) => {
                    if (!field) return;
                    field.addEventListener('invalid', (event) => {
                        event.preventDefault();
                    });
                });

                const collectValidationErrors = () => {
                    const errors = [];
                    if (!reportedUserIdInput.value) {
                        errors.push('Select a user to report');
                    }
                    if (!taskIdInput.value) {
                        errors.push('Select the related task');
                    }
                    if (!incidentTypeInput.value) {
                        errors.push('Choose an incident type');
                    }
                    if (!descriptionInput.value) {
                        errors.push('Provide a description');
                    } else if (descriptionInput.value.length < 10) {
                        errors.push('Description must be at least 10 characters');
                    }
                    return errors;
                };

                const showValidationModal = (errors) => {
                    const message = `Please fix the following:\n- ${errors.join('\n- ')}`;
                    if (typeof window.showAlertModal === 'function') {
                        window.showAlertModal(message, 'Validation Error', 'warning');
                    } else {
                        alert(message); // graceful fallback if modal helper is unavailable
                    }
                };

                form.addEventListener('submit', (event) => {
                    const errors = collectValidationErrors();

                    if (errors.length) {
                        event.preventDefault();
                        showValidationModal(errors);
                        return;
                    }

                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-60', 'cursor-not-allowed');
                    submitButton.textContent = 'Submitting...';
                });
            }

            function debounce(fn, delay) {
                let timer;
                return function (...args) {
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
                items.forEach((item) => {
                    const li = document.createElement('li');
                    li.className = 'px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-brand-peach/50 dark:hover:bg-gray-700 cursor-pointer';
                    li.textContent = formatItem(item);
                    li.addEventListener('click', () => onSelect(item));
                    containerUl.appendChild(li);
                });
                containerUl.classList.remove('hidden');
            }

            (function initUserAutocomplete() {
                const input = document.getElementById('reported_user_search');
                const hidden = document.getElementById('reported_user_id');
                const list = document.querySelector('#reported_user_suggestions ul');
                if (!input || !hidden || !list) return;

                const search = debounce(async (q) => {
                    if (!q || q.length < 2) {
                        list.classList.add('hidden');
                        return;
                    }
                    try {
                        const res = await fetch(`{{ route('incident-reports.users.search') }}?q=${encodeURIComponent(q)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        const data = await res.json();
                        renderSuggestions(list, data, (u) => `${u.firstName} ${u.lastName} (${u.email})`, (u) => {
                            input.value = `${u.firstName} ${u.lastName} (${u.email})`;
                            hidden.value = u.userId;
                            list.classList.add('hidden');
                            input.blur();
                            input.dispatchEvent(new Event('change'));
                        });
                    } catch (e) {
                        list.classList.add('hidden');
                    }
                }, 200);

                input.addEventListener('input', (e) => {
                    hidden.value = '';
                    search(e.target.value);
                });
                input.addEventListener('blur', () => setTimeout(() => list.classList.add('hidden'), 150));
            })();

            (function initTaskAutocomplete() {
                const input = document.getElementById('task_search');
                const hidden = document.getElementById('task_id');
                const list = document.querySelector('#task_suggestions ul');
                if (!input || !hidden || !list) return;

                const search = debounce(async (q) => {
                    if (!q || q.length < 2) {
                        list.classList.add('hidden');
                        return;
                    }
                    try {
                        const res = await fetch(`{{ route('incident-reports.tasks.search') }}?q=${encodeURIComponent(q)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        const data = await res.json();
                        renderSuggestions(list, data, (t) => `${t.title}`, (t) => {
                            input.value = t.title;
                            hidden.value = t.taskId;
                            list.classList.add('hidden');
                            input.blur();
                            input.dispatchEvent(new Event('change'));
                        });
                    } catch (e) {
                        list.classList.add('hidden');
                    }
                }, 200);

                input.addEventListener('input', (e) => {
                    if (!e.target.value) hidden.value = '';
                    search(e.target.value);
                });
                input.addEventListener('blur', () => setTimeout(() => list.classList.add('hidden'), 150));
            })();

            (function initEvidenceUploader() {
                const input = document.getElementById('evidence_images');
                const area = document.getElementById('evidence-upload-area');
                const text = document.getElementById('evidence-upload-text');
                const selectedContainer = document.getElementById('evidence-selected-files');
                const fileList = document.getElementById('evidence-file-list');
                const MAX_FILES = 3;
                let filesState = [];

                if (!area || !input) return;

                const setAreaHighlight = (active) => {
                    area.classList.toggle('ring-2', active);
                    area.classList.toggle('ring-brand-teal/70', active);
                    area.classList.toggle('bg-brand-teal/5', active);
                };

                area.addEventListener('click', () => input.click());
                area.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    setAreaHighlight(true);
                });
                area.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    setAreaHighlight(false);
                });
                area.addEventListener('drop', (e) => {
                    e.preventDefault();
                    setAreaHighlight(false);
                    mergeAndRender(Array.from(e.dataTransfer.files || []).filter((f) => f.type.startsWith('image/')));
                });

                input.addEventListener('change', () => mergeAndRender(Array.from(input.files || [])));

                function mergeAndRender(newFiles) {
                    filesState = mergeFiles(filesState, newFiles).slice(0, MAX_FILES);
                    render();
                }

                function render() {
                    text.textContent = filesState.length
                        ? `${filesState.length} photo${filesState.length > 1 ? 's' : ''} selected`
                        : 'Click to select photos or drag and drop (up to 3)';
                    selectedContainer.classList.toggle('hidden', filesState.length === 0);
                    fileList.innerHTML = '';
                    filesState.forEach((file, index) => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'relative overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900';
                        const url = URL.createObjectURL(file);
                        wrapper.innerHTML = `
                            <img src="${url}" alt="Evidence image" class="h-24 sm:h-32 w-full object-cover" />
                            <button type="button" data-index="${index}" class="absolute top-2 right-2 rounded-full bg-black/60 px-2 py-1 text-xs text-white hover:bg-black/80 touch-target">Remove</button>
                        `;
                        fileList.appendChild(wrapper);
                    });
                    fileList.querySelectorAll('button[data-index]').forEach((btn) =>
                        btn.addEventListener('click', () => {
                            const idx = Number(btn.getAttribute('data-index'));
                            filesState.splice(idx, 1);
                            setInputFiles(input, filesState);
                            render();
                        })
                    );
                    setInputFiles(input, filesState);
                }

                function setInputFiles(target, files) {
                    const dt = new DataTransfer();
                    files.forEach((f) => dt.items.add(f));
                    target.files = dt.files;
                }

                function mergeFiles(existing, incoming) {
                    const out = [...existing];
                    const signature = (f) => `${f.name}|${f.size}`;
                    const seen = new Set(out.map(signature));
                    for (const file of incoming) {
                        if (!file.type.startsWith('image/')) continue;
                        const sig = signature(file);
                        if (seen.has(sig)) continue;
                        out.push(file);
                        seen.add(sig);
                        if (out.length >= MAX_FILES) break;
                    }
                    return out;
                }
            })();
        </script>
    @endpush
</x-app-layout>
