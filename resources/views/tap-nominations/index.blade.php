<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tap & Pass Nominations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            
            <!-- System Info -->
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Tap & Pass System:</strong> Accept or decline nominations sent to you for daily tasks. 
                            Accepting a nomination will assign you to the task and award you 1 point.
                        </p>
                    </div>
                </div>
            </div>

            @if($nominations->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Nominations Received</h3>
                        
                        <div class="space-y-4">
                            @foreach($nominations as $nomination)
                                <div class="border border-gray-200 rounded-lg p-4 {{ $nomination->status === 'pending' ? 'bg-yellow-50 border-yellow-200' : ($nomination->status === 'accepted' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200') }}">
                                    
                                    <!-- Status Badge -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center space-x-2">
                                            @if($nomination->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @elseif($nomination->status === 'accepted')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Accepted
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Declined
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-sm text-gray-500">
                                            {{ $nomination->nomination_date->format('M d, Y g:i A') }}
                                        </span>
                                    </div>

                                    <!-- Task Information -->
                                    <div class="mb-3">
                                        <h4 class="font-medium text-gray-900">{{ $nomination->task->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $nomination->task->description }}</p>
                                        <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                            <span>Points: {{ $nomination->task->points_awarded }}</span>
                                            <span>Type: {{ ucfirst($nomination->task->task_type) }}</span>
                                            @if($nomination->task->location)
                                                <span>Location: {{ $nomination->task->location }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Nominator Information -->
                                    <div class="mb-3">
                                        <p class="text-sm text-gray-600">
                                            <strong>Nominated by:</strong> {{ $nomination->nominator->fullName }}
                                        </p>
                                    </div>

                                    <!-- Actions for Pending Nominations -->
                                    @if($nomination->status === 'pending')
                                        <div class="flex space-x-3">
                                            <form method="POST" action="{{ route('tap-nominations.accept', $nomination) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                        onclick="return confirm('Accept this nomination? You will be assigned to the task and earn 1 point.')">
                                                    Accept (+1 point)
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('tap-nominations.decline', $nomination) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                        onclick="return confirm('Decline this nomination?')">
                                                    Decline
                                                </button>
                                            </form>
                                        </div>
                                    @endif

                                    <!-- Status Messages -->
                                    @if($nomination->status === 'accepted')
                                        <div class="mt-2 text-sm text-green-600">
                                            ✅ You accepted this nomination and earned 1 point!
                                        </div>
                                    @elseif($nomination->status === 'declined')
                                        <div class="mt-2 text-sm text-red-600">
                                            ❌ You declined this nomination.
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $nominations->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <div class="mx-auto w-12 h-12 text-gray-400 mb-4">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Nominations Yet</h3>
                        <p class="text-gray-600 mb-4">You haven't received any Tap & Pass nominations yet.</p>
                        <p class="text-sm text-gray-500 mb-4">Complete daily tasks to become eligible to receive nominations from other users.</p>
                        
                        <!-- Debug info for testing -->
                        <div class="mt-4 p-3 bg-gray-100 rounded-lg text-xs text-gray-600">
                            <strong>Debug Info:</strong><br>
                            User ID: {{ Auth::id() }}<br>
                            Check nominations table for entries with FK3_nomineeId = {{ Auth::id() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
