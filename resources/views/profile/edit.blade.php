<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Profile Settings') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 via-teal-50/20 to-orange-50/20 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(request('notice') === 'incident_warning')
                <div class="mb-4 rounded-xl border border-orange-200 bg-orange-50 text-orange-800 px-4 py-3 flex items-start gap-3 shadow-sm">
                    <div class="mt-0.5 text-orange-600">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="text-sm leading-relaxed">
                        <p class="font-semibold">Account Warning</p>
                        <p>Please review the community guidelines. Repeated violations may result in account suspension.</p>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Left Column: Profile Card -->
                <div class="lg:col-span-1">
                    <!-- Profile Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl sticky top-6">
                        <!-- Profile Picture Section -->
                        <div class="bg-gradient-to-br from-orange-500/10 via-teal-500/10 to-orange-500/10 p-6 pb-8">
                            <div class="flex justify-center">
                                <div class="relative group">
                                    <div class="relative w-36 h-36 rounded-full overflow-hidden bg-gradient-to-br from-orange-400 to-teal-500 shadow-lg ring-4 ring-white dark:ring-gray-800 transition-transform duration-300 group-hover:scale-105">
                                        @if($user->profile_picture_url)
                                            <img src="{{ $user->profile_picture_url }}" alt="{{ $user->firstName }}'s profile picture" class="w-full h-full object-cover profile-picture-preview">
                                        @else
                                            <img src="" alt="" class="w-full h-full object-cover profile-picture-preview hidden">
                                        @endif
                                        <div class="profile-initials w-full h-full absolute inset-0 flex items-center justify-center text-white text-5xl font-bold {{ $user->profile_picture_url ? 'hidden' : '' }}">
                                            {{ $user->initials }}
                                        </div>
                                    </div>
                                    <label for="profile_picture" class="absolute inset-0 flex items-center justify-center bg-black/70 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 cursor-pointer z-10 backdrop-blur-sm">
                                        <div class="text-white text-center transform group-hover:scale-110 transition-transform">
                                            <svg class="w-7 h-7 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-xs font-semibold">Change Photo</span>
                                        </div>
                                    </label>
                                    <!-- Hidden file input -->
                                    <input type="file"
                                           id="profile_picture"
                                           name="profile_picture"
                                           accept="image/*,.webp,image/webp"
                                           class="hidden"
                                           form="profile-update-form"
                                           onchange="previewImage(this)">
                                </div>
                            </div>
                            @if ($errors->has('profile_picture'))
                                <p class="mt-4 text-sm text-red-600 dark:text-red-400 text-center">
                                    {{ $errors->first('profile_picture') }}
                                </p>
                            @endif
                        </div>

                        <!-- Name and Email -->
                        <div class="px-6 pt-4 pb-6 text-center">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $user->fullName }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate max-w-[200px]">{{ $user->email }}</span>
                            </p>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-gradient-to-r from-orange-100 to-teal-100 dark:from-orange-900/30 dark:to-teal-900/30 rounded-full border border-orange-200/50 dark:border-orange-800/50">
                                <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-xs font-semibold text-orange-700 dark:text-orange-300">{{ ucfirst($user->role ?? 'user') }}</span>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 dark:border-gray-700"></div>

                        <!-- Stats Section -->
                        <div class="p-6">
                            <div class="mb-4">
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Statistics
                                </p>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2.5">
                                            <span class="text-lg">💰</span> 
                                            <span>Points</span>
                                        </span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white bg-gradient-to-r from-orange-500 to-orange-600 bg-clip-text text-transparent">{{ number_format($user->points ?? 0) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2.5">
                                            <span class="text-lg">✅</span> 
                                            <span>Completed Tasks</span>
                                        </span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->completedTasks()->where('tasks.status', '!=', 'inactive')->count() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2.5">
                                            <span class="text-lg">🔄</span> 
                                            <span>Ongoing Tasks</span>
                                        </span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->ongoingTasks()->where('tasks.status', '!=', 'inactive')->count() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2.5">
                                            <span class="text-lg">👏</span> 
                                            <span>Nominations</span>
                                        </span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->nominationsReceived()->count() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2.5">
                                            <span class="text-lg">📝</span> 
                                            <span>Reports Made</span>
                                        </span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->incidentReportsMade()->count() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2.5">
                                            <span class="text-lg">📅</span> 
                                            <span>Member Since</span>
                                        </span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->date_registered ? \Carbon\Carbon::parse($user->date_registered)->format('M Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Forms -->
                <div class="lg:col-span-2">
                    <!-- Combined Profile & Security Settings Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <div class="p-1.5 bg-white/20 rounded-lg backdrop-blur-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                Profile Settings
                            </h2>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const headerImg = document.querySelector('.profile-picture-preview');
                    const headerInitials = document.querySelector('.profile-initials');
                    
                    if (headerImg) {
                        headerImg.src = e.target.result;
                        headerImg.classList.remove('hidden');
                    }
                    
                    if (headerInitials) {
                        headerInitials.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
