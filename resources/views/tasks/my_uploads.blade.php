<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Uploaded Tasks') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('tasks.creator.submissions') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Review Submissions
                </a>
                <a href="{{ route('tasks.create') }}" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                    + Add Task
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <x-session-toast />

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                @php $labels = ['pending','live','rejected','completed','all']; @endphp
                @foreach($labels as $label)
                <a href="{{ route('tasks.my-uploads', array_filter(['status' => $label === 'all' ? 'all' : $label, 'q' => $search ?? null])) }}"
                   class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border @class(['border-orange-500' => ($activeStatus ?? 'all') === $label])">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ ucfirst($label) }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats[$label] }}</p>
                </a>
                @endforeach
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('tasks.my-uploads') }}" class="mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-3 sm:space-y-0">
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Status</label>
                        <select id="status" name="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm">
                            @php $options = ['all' => 'All', 'pending' => 'Pending', 'live' => 'Live', 'rejected' => 'Rejected', 'completed' => 'Completed']; @endphp
                            @foreach($options as $value => $text)
                                <option value="{{ $value }}" {{ ($activeStatus ?? 'all') === $value ? 'selected' : '' }}>{{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="q" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" id="q" name="q" value="{{ $search ?? '' }}" placeholder="Search title, description, or location" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm" />
                    </div>
                    <div class="pt-5 sm:pt-0">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Apply</button>
                    </div>
                </div>
            </form>

            @if($uploads->count() === 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 text-center">
                    <p class="text-gray-600 dark:text-gray-300">You haven't uploaded any tasks yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($uploads as $task)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $task->title }}</h3>
                            @php
                                $isLive = in_array($task->status, ['approved','published']);
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @class([
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => $task->status === 'pending',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $isLive || $task->status === 'completed',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $task->status === 'rejected',
                                ])
                            ">
                                {{ $isLive ? 'Live' : ucfirst($task->status) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 mb-3">{{ $task->description }}</p>

                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <p>
                                <strong>Date:</strong>
                                @if($task->due_date)
                                    {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                @else
                                    {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                @endif
                            </p>
                            <p><strong>Points:</strong> {{ $task->points_awarded }}</p>
                            <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}</p>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="{{ route('tasks.show', $task) }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">View</a>
                            <div class="flex items-center space-x-2">
                                @php $canEdit = in_array($task->status, ['pending','rejected']); @endphp
                                @if($canEdit)
                                    <a href="{{ route('tasks.edit', $task) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded">Edit</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Deactivate this task?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">Deactivate</button>
                                    </form>
                                @else
                                    <button class="px-3 py-1 bg-gray-300 text-gray-600 text-xs rounded cursor-not-allowed" title="Editing disabled after approval" disabled>Locked</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

