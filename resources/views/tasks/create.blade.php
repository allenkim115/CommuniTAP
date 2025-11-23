<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-3xl bg-clip-text text-transparent" style="background: linear-gradient(to right, #F3A261, #2B9D8D); -webkit-background-clip: text;">
                {{ __('Create Task Proposal') }}
            </h2>
            <a href="{{ route('tasks.index') }}"
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
                
                <!-- Guidelines Section -->
                <div class="dark:from-orange-900/20 dark:to-teal-900/20 border-l-4 dark:border-orange-400 rounded-xl p-6 shadow-lg" style="background-color: rgba(243, 162, 97, 0.1); border-color: #F3A261;">
                    <h3 class="text-xl font-bold dark:text-orange-100 mb-3 flex items-center gap-2" style="color: #F3A261;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                        Task Proposal Guidelines
                    </h3>
                    <ul class="list-disc list-inside text-sm font-semibold dark:text-orange-200 space-y-2" style="color: #F3A261;">
                        <li>Only <strong>User-Uploaded</strong> tasks can be created by regular users.</li>
                        <li>Daily Tasks and One-Time Tasks can only be created by administrators.</li>
                        <li>All task proposals require admin approval before being published.</li>
                        <li>Provide clear and detailed descriptions for better approval chances.</li>
                    </ul>
                </div>

                <!-- Form Section -->
                <form action="{{ route('tasks.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left column -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-6 border-2 border-gray-200 dark:border-gray-700 space-y-6 shadow-sm">
                            <h4 class="text-lg font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #F3A261, #2B9D8D); -webkit-background-clip: text;">Basic Information</h4>

                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}"
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
                                    required>{{ old('description') }}</textarea>
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
                                <input type="number" name="points_awarded" id="points_awarded" value="{{ old('points_awarded') }}"
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
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                                    class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white transition-all"
                                    min="{{ date('Y-m-d') }}" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Due date must be today or later.</p>
                            </div>

                            <!-- Start & End Time -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Start Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
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
                                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
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
                                        <option value="{{ $loc }}" {{ old('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Participant Limit -->
                            <div>
                                <label for="max_participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Participant Limit
                                </label>
                                <input type="number" name="max_participants" id="max_participants"
                                    value="{{ old('max_participants') }}"
                                    class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white transition-all"
                                    placeholder="Leave blank for unlimited" min="1">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('tasks.index') }}"
                            class="px-6 py-3 rounded-xl text-sm font-bold border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-3 text-sm font-bold rounded-xl text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2"
                            style="background-color: #F3A261;"
                            onmouseover="this.style.backgroundColor='#E8944F'"
                            onmouseout="this.style.backgroundColor='#F3A261'">
                            Submit Task Proposal
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
