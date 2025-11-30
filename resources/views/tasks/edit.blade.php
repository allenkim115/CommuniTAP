<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-3xl bg-clip-text text-transparent" style="background: linear-gradient(to right, #F3A261, #2B9D8D); -webkit-background-clip: text;">
                {{ __('Edit Task Proposal') }}
            </h2>
            <a href="{{ route('tasks.my-uploads') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 via-orange-50/30 to-teal-50/20 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 py-10">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 space-y-10">
                
                @php
                    $isUncompleted = !in_array($task->status, ['completed']) && !in_array($task->status, ['approved', 'published']);
                @endphp
                
                <!-- Guidelines/Note Section -->
                @if($isUncompleted)
                <div class="dark:from-blue-900/20 dark:to-cyan-900/20 border-l-4 dark:border-blue-400 rounded-xl p-6 shadow-lg" style="background-color: rgba(43, 157, 141, 0.1); border-color: #2B9D8D;">
                    <h3 class="text-xl font-bold dark:text-blue-100 mb-3 flex items-center gap-2" style="color: #2B9D8D;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Important Note
                    </h3>
                    <p class="text-sm font-semibold dark:text-blue-200" style="color: #2B9D8D;">
                        Choosing "Update Task" will set status to "Pending" and require admin approval before it can be published.
                    </p>
                </div>
                @else
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-xl p-6 shadow-lg">
                    <h3 class="text-xl font-bold text-yellow-800 dark:text-yellow-100 mb-3 flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Important Note
                    </h3>
                    <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">
                        Editing this task will reset its status to "Pending" and require admin approval again.
                    </p>
                </div>
                @endif

                <!-- Form Section -->
                <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left column -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-6 border-2 border-gray-200 dark:border-gray-700 space-y-6 shadow-sm">
                            <h4 class="text-lg font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #F3A261, #2B9D8D); -webkit-background-clip: text;">Basic Information</h4>

                            <!-- Task Type (Read-only) -->
                            <div>
                                <label for="task_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Type
                                </label>
                                <input type="text" value="{{ ucfirst(str_replace('_', ' ', $task->task_type)) }}" 
                                       class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400" 
                                       readonly>
                            </div>

                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}"
                                    class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 dark:bg-gray-800 dark:text-white transition-all"
                                    style="--focus-ring-color: #F3A261;"
                                    onfocus="this.style.borderColor='#F3A261'; this.style.boxShadow='0 0 0 3px rgba(243, 162, 97, 0.1)';"
                                    onblur="this.style.borderColor=''; this.style.boxShadow='';"
                                    placeholder="Enter task title" required maxlength="100">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 100 characters.</p>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Description <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="7" maxlength="1000"
                                    class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 dark:bg-gray-800 dark:text-white transition-all"
                                    style="--focus-ring-color: #F3A261;"
                                    onfocus="this.style.borderColor='#F3A261'; this.style.boxShadow='0 0 0 3px rgba(243, 162, 97, 0.1)';"
                                    onblur="this.style.borderColor=''; this.style.boxShadow='';"
                                    placeholder="Provide details: objectives, requirements, and outcomes"
                                    required>{{ old('description', $task->description) }}</textarea>
                                <div class="mt-1 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <p>Be specific about objectives and expected results.</p>
                                    <p id="description-counter">0/1000</p>
                                </div>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-6 border-2 border-gray-200 dark:border-gray-700 space-y-6 shadow-sm">
                            <h4 class="text-lg font-bold bg-gradient-to-r from-orange-600 to-teal-500 bg-clip-text text-transparent">Schedule & Rewards</h4>

                            <!-- Points -->
                            <div>
                                <label for="points_awarded" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Points to Award <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="points_awarded" id="points_awarded" value="{{ old('points_awarded', $task->points_awarded) }}"
                                    class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white transition-all"
                                    placeholder="e.g., 50" min="1" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended: 5–100 points depending on effort.</p>
                                @error('points_awarded')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Due Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date ? (is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : $task->due_date->format('Y-m-d')) : '') }}"
                                    class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white transition-all"
                                    min="{{ date('Y-m-d') }}" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Due date must be today or later.</p>
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start & End Time -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Start Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $task->start_time ? (\Carbon\Carbon::parse($task->start_time)->format('H:i')) : '') }}"
                                        class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white transition-all @error('start_time') border-red-500 @enderror"
                                        required>
                                    @error('start_time')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        End Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $task->end_time ? (\Carbon\Carbon::parse($task->end_time)->format('H:i')) : '') }}"
                                        class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white transition-all @error('end_time') border-red-500 @enderror"
                                        required>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ensure end time is after start time.</p>
                                    @error('end_time')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Location <span class="text-red-500">*</span>
                                </label>
                                <select name="location" id="location"
                                    class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white transition-all"
                                    required>
                                    <option value="" disabled selected hidden>Select a sitio</option>
                                    @foreach(['Pig Vendor','Ermita Proper','Kastilaan','Sitio Bato','YHC','Eyeseekers','Panagdait','Kawit'] as $loc)
                                        <option value="{{ $loc }}" {{ old('location', $task->location) == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Participant Limit -->
                            <div>
                                <label for="max_participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Participant Limit
                                </label>
                                <input type="number" name="max_participants" id="max_participants"
                                    value="{{ old('max_participants', $task->max_participants) }}"
                                    class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white transition-all"
                                    placeholder="Leave blank for unlimited" min="1">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank for unlimited participants.</p>
                                @error('max_participants')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Current Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Current Status
                                </label>
                                <div class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-600">
                                    <span class="px-4 py-1.5 text-xs font-bold bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 dark:from-yellow-900/30 dark:to-amber-900/30 dark:text-yellow-300 rounded-lg shadow-sm">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('tasks.my-uploads') }}"
                            class="px-6 py-3 rounded-xl text-sm font-bold border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            Cancel
                        </a>
                        <button type="submit" name="action" value="update"
                            class="px-6 py-3 text-sm font-bold rounded-xl text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2"
                            style="background-color: {{ $isUncompleted ? '#2B9D8D' : '#F3A261' }};"
                            onmouseover="this.style.backgroundColor='{{ $isUncompleted ? '#248A7C' : '#E8944F' }}'"
                            onmouseout="this.style.backgroundColor='{{ $isUncompleted ? '#2B9D8D' : '#F3A261' }}'">
                            {{ $isUncompleted ? 'Update Task' : 'Update Task Proposal' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const textarea = document.getElementById('description');
            const counter = document.getElementById('description-counter');
            if (textarea && counter) {
                const update = () => {
                    const len = textarea.value.length;
                    counter.textContent = `${len}/1000`;
                };
                textarea.addEventListener('input', update);
                update();
            }
        })();
    </script>
</x-app-layout>
