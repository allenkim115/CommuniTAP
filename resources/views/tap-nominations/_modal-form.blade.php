<!-- Modal Header -->
<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-teal-50 dark:from-orange-900/20 dark:to-teal-900/20">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Nominate Next Volunteer</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tap & Pass a daily task to another volunteer</p>
            </div>
        </div>
        <button type="button" onclick="closeNominationModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<!-- Modal Body -->
<div class="px-6 py-6 max-h-[calc(100vh-200px)] overflow-y-auto">
    <!-- Info Banner -->
    <div class="bg-gradient-to-r from-orange-50 to-teal-50 dark:from-orange-900/20 dark:to-teal-900/20 border-l-4 border-orange-500 dark:border-orange-400 rounded-xl p-4 mb-6 shadow-md">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
            </svg>
            <p class="text-sm text-orange-800 dark:text-orange-200 leading-relaxed">
                <strong>You've completed a daily task!</strong> Nominate another user for a daily task — when they accept, both of you earn <span class="font-bold">1 point</span>.
            </p>
        </div>
    </div>

    @if($userDailyTaskAssignment)
    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-l-4 border-green-500 dark:border-green-400 rounded-xl p-4 shadow-md">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-md">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-green-800 dark:text-green-200">
                    Completed Task
                </p>
                <p class="text-sm text-green-700 dark:text-green-300">
                    {{ $userDailyTaskAssignment->task->title }}
                </p>
                <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                    Completed on {{ $userDailyTaskAssignment->completed_at->format('M d, Y g:i A') }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('tap-nominations.store') }}" id="nomination-form" class="space-y-6" onsubmit="event.preventDefault(); showNominationConfirmModal();" novalidate>
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Task -->
            <div class="space-y-2">
                <label for="modal_task_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                    Select Daily Task
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <select id="modal_task_id" name="task_id" class="block w-full px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all duration-200 appearance-none bg-white hover:border-orange-400 hover:shadow-md" required>
                        <option value="">Choose a daily task...</option>
                        @foreach($availableDailyTasks as $availableTask)
                            <option value="{{ $availableTask->taskId }}" {{ old('task_id') == $availableTask->taskId ? 'selected' : '' }}>
                                {{ $availableTask->title }} — {{ $availableTask->points_awarded }} pts
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('task_id')" />
            </div>

            <!-- Nominee -->
            <div class="space-y-2">
                <label for="modal_nominee_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                    Select User to Nominate
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <select id="modal_nominee_id" name="nominee_id" class="block w-full px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all duration-200 appearance-none bg-white hover:border-orange-400 hover:shadow-md" required disabled>
                        <option value="">Select a daily task first...</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('nominee_id')" />
            </div>
        </div>

        <!-- Points Info -->
        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-xl p-5 shadow-md">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-base font-bold text-yellow-800 dark:text-yellow-200 mb-2">Points System</h4>
                    <ul class="space-y-1.5 text-sm text-yellow-800 dark:text-yellow-200">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            You earn <strong class="font-bold">1 point</strong> for sending a nomination
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Nominee earns <strong class="font-bold">1 point</strong> if they accept
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Both users contribute to Tap & Pass participation
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Footer -->
<div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex flex-col sm:flex-row justify-end gap-3">
    <button type="button" onclick="closeNominationModal()" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold rounded-xl border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
        Cancel
    </button>
    <button type="submit" form="nomination-form" class="inline-flex items-center justify-center gap-2 px-8 py-2.5 text-sm font-bold rounded-xl text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2"
            style="background-color: #F3A261;"
            onmouseover="this.style.backgroundColor='#E8944F'"
            onmouseout="this.style.backgroundColor='#F3A261'">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
        </svg>
        Send Nomination
    </button>
</div>

