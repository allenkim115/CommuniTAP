<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-3xl bg-gradient-to-r from-orange-600 via-orange-500 to-teal-500 bg-clip-text text-transparent">
            {{ __('Tap & Pass - Nominate Next Volunteer') }}
        </h2>
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Tasks
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 via-orange-50/30 to-teal-50/20 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast -->
            <x-session-toast />

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border-2 border-gray-200 dark:border-gray-700 p-8 sm:p-10 relative overflow-hidden">
                <!-- Animated background decoration -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-orange-200/20 to-teal-200/20 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-teal-200/20 to-orange-200/20 rounded-full -ml-32 -mb-32 blur-3xl"></div>
                <div class="relative z-10">
                <!-- Header + Hint -->
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Nominate Next Volunteer</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tap & Pass a daily task to another volunteer</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-orange-50 to-teal-50 dark:from-orange-900/20 dark:to-teal-900/20 border-l-4 border-orange-500 dark:border-orange-400 rounded-xl p-5 shadow-md">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                            </svg>
                            <p class="text-sm text-orange-800 dark:text-orange-200 leading-relaxed">
                                <strong>You've completed a daily task!</strong> Nominate another user for a daily task — when they accept, both of you earn <span class="font-bold">1 point</span>.
                        </p>
                        </div>
                    </div>
                </div>

                @if($userDailyTaskAssignment)
                <div class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-l-4 border-green-500 dark:border-green-400 rounded-xl p-5 shadow-md">
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
                <form method="POST" action="{{ route('tap-nominations.store') }}" class="space-y-8" onsubmit="return confirm('Send this nomination? You will earn 1 point.')">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Task -->
                        <div class="space-y-2">
                            <label for="task_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                Select Daily Task
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <select id="task_id" name="task_id" class="block w-full px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all duration-200 appearance-none bg-white hover:border-orange-400 hover:shadow-md" required>
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
                            <label for="nominee_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                Select User to Nominate
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <select id="nominee_id" name="nominee_id" class="block w-full px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all duration-200 appearance-none bg-white hover:border-orange-400 hover:shadow-md" required>
                                <option value="">Choose a user...</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->userId }}" {{ old('nominee_id') == $user->userId ? 'selected' : '' }}>
                                        {{ $user->fullName }} ({{ $user->email }})
                                    </option>
                                @endforeach
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
                    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-xl p-6 shadow-md">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-yellow-800 dark:text-yellow-200 mb-3">Points System</h4>
                                <ul class="space-y-2 text-sm text-yellow-800 dark:text-yellow-200">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        You earn <strong class="font-bold">1 point</strong> for sending a nomination
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Nominee earns <strong class="font-bold">1 point</strong> if they accept
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Both users contribute to Tap & Pass participation
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t-2 border-gray-200 dark:border-gray-700">
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold rounded-xl border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-3 text-sm font-bold rounded-xl text-white bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 focus:ring-2 focus:ring-orange-400 focus:ring-offset-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send Nomination
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes form-enter {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        form {
            animation: form-enter 0.6s ease-out;
        }
        
        select:focus {
            transform: scale(1.01);
        }
        
        @keyframes select-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4); }
            50% { box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1); }
        }
        
        select:focus {
            animation: select-glow 2s ease-in-out infinite;
        }
    </style>
</x-app-layout>
