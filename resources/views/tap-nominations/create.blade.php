<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tap & Pass - Nominate Next Volunteer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Tap & Pass System Info -->
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Tap & Pass System:</strong> You have completed a daily task and can now nominate another user to any available daily task. 
                                    Both you and the nominee will earn 1 point each when the nomination is accepted.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Task Info -->
                    @if($userDailyTaskAssignment)
                    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    <strong>Completed Daily Task:</strong> {{ $userDailyTaskAssignment->task->title }} (Completed on {{ $userDailyTaskAssignment->completed_at->format('M d, Y g:i A') }})
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Nomination Form -->
                    <form method="POST" action="{{ route('tap-nominations.store') }}" onsubmit="return confirm('Are you sure you want to send this nomination? You will earn 1 point for using Tap & Pass.')">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Task Selection -->
                            <div>
                                <x-input-label for="task_id" :value="__('Select Daily Task to Nominate For')" />
                                <select id="task_id" name="task_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Choose a daily task...</option>
                                    @foreach($availableDailyTasks as $availableTask)
                                        <option value="{{ $availableTask->taskId }}" {{ old('task_id') == $availableTask->taskId ? 'selected' : '' }}>
                                            {{ $availableTask->title }} - {{ $availableTask->points_awarded }} points
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('task_id')" />
                            </div>

                            <!-- Nominee Selection -->
                            <div>
                                <x-input-label for="nominee_id" :value="__('Select User to Nominate')" />
                                <select id="nominee_id" name="nominee_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Choose a user...</option>
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->userId }}" {{ old('nominee_id') == $user->userId ? 'selected' : '' }}>
                                            {{ $user->fullName }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('nominee_id')" />
                            </div>

                            <!-- Points Information -->
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Points System:</strong> 
                                            <ul class="list-disc list-inside mt-2">
                                                <li>You will earn <strong>1 point</strong> for making this nomination</li>
                                                <li>The nominated user will earn <strong>1 point</strong> if they accept</li>
                                                <li>Both users earn points for participating in the Tap & Pass system</li>
                                            </ul>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('tasks.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            
                            <x-primary-button>
                                {{ __('Send Nomination') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
