<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin - Tap & Pass Task Chain') }}
            </h2>
            <div class="text-sm text-gray-500">
                {{ __('Administrator View') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Admin Info -->
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Administrator View:</strong> Monitor the complete Tap & Pass nomination chain to track volunteer engagement and task distribution patterns. 
                            This helps you understand how users are collaborating and passing daily tasks to each other.
                        </p>
                    </div>
                </div>
            </div>

            @if($taskChain->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Tap & Pass Activity</h3>
                        <p class="text-sm text-gray-600 mb-6">
                            Showing {{ $taskChain->count() }} accepted nominations in the Tap & Pass system. 
                            <span class="text-blue-600 font-medium">Administrator monitoring view</span>
                        </p>
                        
                        <div class="space-y-6">
                            @foreach($taskChain as $nomination)
                                <div class="border border-gray-200 rounded-lg p-4 bg-green-50 border-green-200">
                                    
                                    <!-- Chain Link Header -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Accepted
                                            </span>
                                        </div>
                                        <span class="text-sm text-gray-500">
                                            {{ $nomination->nomination_date->format('M d, Y g:i A') }}
                                        </span>
                                    </div>

                                    <!-- Task Information -->
                                    <div class="mb-4">
                                        <h4 class="font-medium text-gray-900 mb-2">{{ $nomination->task->title }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">{{ $nomination->task->description }}</p>
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Points: {{ $nomination->task->points_awarded }}</span>
                                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded">{{ ucfirst($nomination->task->task_type) }}</span>
                                            @if($nomination->task->location)
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">{{ $nomination->task->location }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Chain Flow -->
                                    <div class="flex items-center justify-center space-x-4 p-4 bg-white rounded-lg border-2 border-dashed border-gray-300">
                                        <!-- Nominator -->
                                        <div class="text-center">
                                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900">{{ $nomination->nominator->firstName }}</p>
                                            <p class="text-xs text-gray-500">{{ $nomination->nominator->lastName }}</p>
                                            <p class="text-xs text-blue-600 mt-1">Completed Task</p>
                                        </div>

                                        <!-- Arrow -->
                                        <div class="flex flex-col items-center">
                                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                            <p class="text-xs text-green-600 font-medium mt-1">Tapped</p>
                                        </div>

                                        <!-- Nominee -->
                                        <div class="text-center">
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900">{{ $nomination->nominee->firstName }}</p>
                                            <p class="text-xs text-gray-500">{{ $nomination->nominee->lastName }}</p>
                                            <p class="text-xs text-green-600 mt-1">Accepted Task</p>
                                        </div>
                                    </div>

                                    <!-- Points Earned -->
                                    <div class="mt-4 flex justify-center">
                                        <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                            Both users earned 1 point each for Tap & Pass participation
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $taskChain->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <div class="mx-auto w-12 h-12 text-gray-400 mb-4">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Task Chain Yet</h3>
                        <p class="text-gray-600 mb-4">The Tap & Pass task chain is empty. Start the chain by completing daily tasks and nominating others!</p>
                        <a href="{{ route('tasks.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('View Available Tasks') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
