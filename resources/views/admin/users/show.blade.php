<x-admin-layout>
    <div class="py-6 lg:py-8 bg-gradient-to-br from-gray-50 via-white to-orange-50/30 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all duration-200 border border-gray-200">
                    <i class="fas fa-arrow-left"></i>
                    Back to Users
                </a>
            </div>
            
            <!-- Page Header -->
            <div class="bg-gradient-to-r from-orange-500 via-orange-600 to-teal-500 rounded-2xl shadow-lg border border-orange-200 overflow-hidden">
                <div class="bg-white/10 backdrop-blur-sm p-6 lg:p-8">
                    <div class="flex items-start gap-4">
                        <div class="bg-white/20 rounded-xl p-4 backdrop-blur-sm">
                            <i class="fas fa-user-circle text-white text-3xl"></i>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-2">Account Profile</h1>
                            <p class="text-white/90 text-sm lg:text-base">User account information and details</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Profile Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Profile Header Section -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-8 border-b border-gray-200">
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-lg">
                                <span class="text-white text-3xl font-bold">
                                    {{ strtoupper(substr($user->full_name ?? ($user->firstName.' '.$user->lastName), 0, 2)) }}
                                </span>
                            </div>
                            @if($user->status === 'active')
                                <div class="absolute bottom-0 right-0 w-6 h-6 rounded-full border-4 border-white shadow-sm" style="background-color: #2B9D8D;"></div>
                            @elseif($user->status === 'suspended')
                                <div class="absolute bottom-0 right-0 w-6 h-6 rounded-full border-4 border-white shadow-sm" style="background-color: #2B9D8D;"></div>
                            @else
                                <div class="absolute bottom-0 right-0 w-6 h-6 bg-gray-500 rounded-full border-4 border-white shadow-sm"></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">
                                {{ $user->full_name ?? ($user->firstName.' '.$user->lastName) }}
                            </h2>
                            <p class="text-gray-600 mb-3">{{ $user->email }}</p>
                            <div class="flex items-center gap-3 flex-wrap">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                        <i class="fas fa-check-circle" style="color: #2B9D8D;"></i>
                                        Active
                                    </span>
                                @elseif($user->status === 'suspended')
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                        <i class="fas fa-ban" style="color: #2B9D8D;"></i>
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">
                                        <i class="fas fa-circle text-gray-600"></i>
                                        {{ ucfirst($user->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Details Section -->
                <div class="p-6 lg:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email Card -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-envelope text-white text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600 mb-1">Email Address</p>
                                    <p class="text-lg font-semibold text-gray-900 break-all">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Points Card -->
                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-6 border border-teal-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-star text-white text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600 mb-1">Total Points</p>
                                    <p class="text-2xl font-bold text-teal-700">{{ number_format($user->points ?? 0) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks Completed Card -->
                        <div class="rounded-xl p-6 border hover:shadow-md transition-shadow" style="background-color: rgba(43, 157, 141, 0.1); border-color: #2B9D8D;">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center shadow-sm" style="background-color: #2B9D8D;">
                                    <i class="fas fa-check-circle text-white text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600 mb-1">Tasks Completed</p>
                                    <p class="text-2xl font-bold" style="color: #2B9D8D;">{{ number_format($tasksCompleted ?? 0) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks In Progress Card -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-tasks text-white text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600 mb-1">Tasks In Progress</p>
                                    <p class="text-2xl font-bold text-orange-700">{{ number_format($tasksInProgress ?? 0) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Tasks Assigned Card -->
                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-6 border border-teal-200 hover:shadow-md transition-shadow md:col-span-2">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-clipboard-list text-white text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600 mb-1">Total Tasks Assigned</p>
                                    <p class="text-2xl font-bold text-teal-700">{{ number_format($tasksAssigned ?? 0) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    @if(isset($user->date_registered))
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-gray-600 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600 mb-1">Registration Date</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($user->date_registered)->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        @if(session('status'))
                            <div class="mb-4 rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-800 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                {{ session('status') }}
                            </div>
                        @endif
                        
                        <div class="flex flex-wrap gap-4">
                            @if($user->status === 'active')
                                <form method="POST" action="{{ route('admin.users.suspend', $user) }}" id="suspend-user-form" class="flex-1 min-w-[200px]">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" onclick="showConfirmModal('Are you sure you want to suspend this user?', 'Suspend User', 'Suspend', 'Cancel', 'red').then(confirmed => { if(confirmed) document.getElementById('suspend-user-form').submit(); });" class="w-full px-6 py-3 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2"
                                            style="background-color: #2B9D8D;"
                                            onmouseover="this.style.backgroundColor='#248A7C'"
                                            onmouseout="this.style.backgroundColor='#2B9D8D'">
                                        <i class="fas fa-ban"></i>
                                        Suspend User
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.reactivate', $user) }}" id="reactivate-user-form" class="flex-1 min-w-[200px]">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" onclick="showConfirmModal('Are you sure you want to reactivate this user?', 'Reactivate User', 'Reactivate', 'Cancel', 'green').then(confirmed => { if(confirmed) document.getElementById('reactivate-user-form').submit(); });" class="w-full px-6 py-3 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2"
                                            style="background-color: #2B9D8D;"
                                            onmouseover="this.style.backgroundColor='#248A7C'"
                                            onmouseout="this.style.backgroundColor='#2B9D8D'">
                                        <i class="fas fa-check-circle"></i>
                                        Reactivate User
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>


