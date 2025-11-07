<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tap & Pass - Nominate Next Volunteer') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-10 dark:from-gray-900 dark:to-gray-950">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <!-- Toast -->
            <x-session-toast />

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
                <!-- Header + Hint -->
                <div class="mb-6">
                    <div class="flex items-start justify-between">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tap & Pass — Nominate Next Volunteer</h3>
                        <a href="{{ route('tasks.index') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Back to Tasks
                        </a>
                    </div>
                    <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            You’ve completed a daily task. Nominate another user for a daily task — when they accept, both of you earn 1 point.
                        </p>
                    </div>
                </div>

                @if($userDailyTaskAssignment)
                <div class="mb-8 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <p class="text-sm text-green-800 dark:text-green-200">
                        <span class="font-semibold">Completed:</span> {{ $userDailyTaskAssignment->task->title }}
                        <span class="ml-2 text-xs opacity-80">on {{ $userDailyTaskAssignment->completed_at->format('M d, Y g:i A') }}</span>
                    </p>
                </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('tap-nominations.store') }}" class="space-y-8" onsubmit="return confirm('Send this nomination? You will earn 1 point.')">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Task -->
                        <div>
                            <label for="task_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Daily Task</label>
                            <select id="task_id" name="task_id" class="block w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white" required>
                                <option value="">Choose a daily task...</option>
                                @foreach($availableDailyTasks as $availableTask)
                                    <option value="{{ $availableTask->taskId }}" {{ old('task_id') == $availableTask->taskId ? 'selected' : '' }}>
                                        {{ $availableTask->title }} — {{ $availableTask->points_awarded }} pts
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('task_id')" />
                        </div>

                        <!-- Nominee -->
                        <div>
                            <label for="nominee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select User to Nominate</label>
                            <select id="nominee_id" name="nominee_id" class="block w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white" required>
                                <option value="">Choose a user...</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->userId }}" {{ old('nominee_id') == $user->userId ? 'selected' : '' }}>
                                        {{ $user->fullName }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('nominee_id')" />
                        </div>
                    </div>

                    <!-- Points Info -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 text-yellow-600 dark:text-yellow-300">⚑</div>
                            <div class="text-sm text-yellow-800 dark:text-yellow-200">
                                <p class="font-medium">Points System</p>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li>You earn <strong>1 point</strong> for sending a nomination</li>
                                    <li>Nominee earns <strong>1 point</strong> if they accept</li>
                                    <li>Both users contribute to Tap & Pass participation</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 rounded-md text-sm font-medium border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">Send Nomination</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
