<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-3xl text-gray-900">
                {{ __('Create Task Proposal') }}
            </h2>
            <a href="{{ route('tasks.my-uploads') }}"
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
                <div class="rounded-xl p-5 shadow-sm border" style="background: linear-gradient(135deg, rgba(43, 157, 141, 0.08) 0%, rgba(43, 157, 141, 0.03) 100%); border-color: rgba(43, 157, 141, 0.2);">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full" style="background-color: rgba(43, 157, 141, 0.15);">
                            <svg class="w-5 h-5" fill="none" stroke="#2B9D8D" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Task Proposal Guidelines</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Follow these tips for better approval chances</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-700">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">All proposals require <strong class="font-semibold" style="color: #2B9D8D;">admin approval</strong> before publishing</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-700">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Provide <strong class="font-semibold" style="color: #2B9D8D;">clear descriptions</strong> for better chances</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-700">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Include <strong class="font-semibold" style="color: #2B9D8D;">specific location</strong> & meeting points</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-700">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Set <strong class="font-semibold" style="color: #2B9D8D;">realistic limits</strong> based on task scope</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-700">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Choose <strong class="font-semibold" style="color: #2B9D8D;">appropriate points</strong> matching effort</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-700">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Allow <strong class="font-semibold" style="color: #2B9D8D;">sufficient time</strong> for preparation</span>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <form action="{{ route('tasks.store') }}" method="POST" class="space-y-8" novalidate>
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left column -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-6 border-2 border-gray-200 dark:border-gray-700 space-y-6 shadow-sm">
                            <h4 class="text-lg font-bold text-gray-900" style="color: #2B9D8D;">Basic Information</h4>

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
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Min 10, max 100 characters.</p>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Description <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="7" minlength="10" maxlength="1000"
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
                            <h4 class="text-lg font-bold text-gray-900" style="color: #2B9D8D;">Schedule & Rewards</h4>

                            <!-- Points Category -->
                            <div>
                                <label for="points_awarded" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Points Category <span class="text-red-500">*</span>
                                </label>
                                
                                <!-- Point Categories Guide (Collapsible) - Above dropdown -->
                                <div class="mb-3">
                                    <button type="button" onclick="togglePointsGuide()" class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full transition-all duration-200 border" style="color: #F3A261; background-color: rgba(243, 162, 97, 0.08); border-color: rgba(243, 162, 97, 0.2);" onmouseover="this.style.backgroundColor='rgba(243, 162, 97, 0.15)'" onmouseout="this.style.backgroundColor='rgba(243, 162, 97, 0.08)'">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <span>Point Categories Guide</span>
                                        <svg id="points-guide-icon" class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div id="points-guide" class="hidden mt-3 rounded-xl p-4 border shadow-sm dark:shadow-none" style="background: linear-gradient(135deg, rgba(243, 162, 97, 0.05) 0%, rgba(43, 157, 141, 0.05) 100%); border-color: rgba(243, 162, 97, 0.2);">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                            <!-- 5 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <svg class="w-5 h-5" fill="#9CA3AF" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    </svg>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #9CA3AF;">5 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Quick</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 dark:bg-gray-600 px-1.5 py-0.5 rounded">~5-10 mins</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Attendance, brief meetings, signing docs.</p>
                                                </div>
                                            </div>
                                            <!-- 10 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <svg class="w-5 h-5" fill="#60A5FA" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    </svg>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #60A5FA;">10 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Light</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 dark:bg-gray-600 px-1.5 py-0.5 rounded">~15-30 mins</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Basic cleanup, distributing materials.</p>
                                                </div>
                                            </div>
                                            <!-- 25 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <svg class="w-5 h-5" fill="#34D399" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    </svg>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #34D399;">25 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Standard</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 dark:bg-gray-600 px-1.5 py-0.5 rounded">~30-60 mins</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Community cleanup, event setup.</p>
                                                </div>
                                            </div>
                                            <!-- 40 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <svg class="w-5 h-5" fill="#FBBF24" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    </svg>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #FBBF24;">40 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Moderate</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 dark:bg-gray-600 px-1.5 py-0.5 rounded">~1-2 hours</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Full cleanup, organizing, coordination.</p>
                                                </div>
                                            </div>
                                            <!-- 60 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <svg class="w-5 h-5" fill="#F97316" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    </svg>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #F97316;">60 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Substantial</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 dark:bg-gray-600 px-1.5 py-0.5 rounded">~2-3 hours</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Major projects, workshops, beautification.</p>
                                                </div>
                                            </div>
                                            <!-- 80 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <svg class="w-5 h-5" fill="#EF4444" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    </svg>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #EF4444;">80 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Major</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 dark:bg-gray-600 px-1.5 py-0.5 rounded">~3-4 hours</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Large events, community drives.</p>
                                                </div>
                                            </div>
                                            <!-- 100 Points - Champion -->
                                            <div class="flex items-start gap-3 p-3 rounded-lg border-2 col-span-1 sm:col-span-2" style="background: linear-gradient(135deg, rgba(243, 162, 97, 0.1) 0%, rgba(43, 157, 141, 0.1) 100%); border-color: rgba(243, 162, 97, 0.3);">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <div class="relative">
                                                        <svg class="w-6 h-6" fill="#F3A261" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                        </svg>
                                                        <svg class="w-3 h-3 absolute -top-1 left-1/2 -translate-x-1/2" fill="#2B9D8D" viewBox="0 0 24 24">
                                                            <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-[10px] font-bold mt-0.5" style="background: linear-gradient(135deg, #F3A261, #2B9D8D); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">100 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <span class="font-bold text-sm" style="color: #2B9D8D;">Community Champion</span>
                                                        <span class="text-[10px] font-medium px-1.5 py-0.5 rounded" style="background-color: rgba(43, 157, 141, 0.15); color: #2B9D8D;">4+ hours</span>
                                                    </div>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">Emergency response, project leadership, exceptional service, full-day events.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100 dark:border-gray-600">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="#2B9D8D" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-[11px] text-gray-500 dark:text-gray-400">
                                                <span class="font-medium">Max cap:</span> 500 points per user. Points awarded upon task completion verification.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <select name="points_awarded" id="points_awarded"
                                    class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white transition-all"
                                    required onchange="updatePointDescription(this)">
                                    <option value="" disabled {{ old('points_awarded') ? '' : 'selected' }} hidden>Select Point Category</option>
                                    <option value="5" {{ old('points_awarded') == '5' ? 'selected' : '' }} data-desc="Quick Task (~5-10 mins): Simple attendance, brief meetings">
                                        5 pts - Quick Task
                                    </option>
                                    <option value="10" {{ old('points_awarded') == '10' ? 'selected' : '' }} data-desc="Light Task (~15-30 mins): Basic cleanup, distributing materials">
                                        10 pts - Light Task
                                    </option>
                                    <option value="25" {{ old('points_awarded') == '25' ? 'selected' : '' }} data-desc="Standard Task (~30-60 mins): Community cleanup, event setup">
                                        25 pts - Standard Task
                                    </option>
                                    <option value="40" {{ old('points_awarded') == '40' ? 'selected' : '' }} data-desc="Moderate Task (~1-2 hours): Full cleanup sessions, coordination">
                                        40 pts - Moderate Task
                                    </option>
                                    <option value="60" {{ old('points_awarded') == '60' ? 'selected' : '' }} data-desc="Substantial Task (~2-3 hours): Major projects, workshop facilitation">
                                        60 pts - Substantial Task
                                    </option>
                                    <option value="80" {{ old('points_awarded') == '80' ? 'selected' : '' }} data-desc="Major Task (~3-4 hours): Large events, community drives">
                                        80 pts - Major Task
                                    </option>
                                    <option value="100" {{ old('points_awarded') == '100' ? 'selected' : '' }} data-desc="Community Champion (4+ hours): Emergency response, leadership roles">
                                        100 pts - Community Champion
                                    </option>
                                </select>
                                <p id="points-description" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Select a point category based on task effort and duration.
                                </p>
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
                                    placeholder="Leave blank for no limit" min="1">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('tasks.my-uploads') }}"
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

            // Initialize points description on page load
            const pointsSelect = document.getElementById('points_awarded');
            if (pointsSelect && pointsSelect.value) {
                updatePointDescription(pointsSelect);
            }
        })();

        function updatePointDescription(select) {
            const descEl = document.getElementById('points-description');
            if (!descEl) return;
            
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.dataset.desc) {
                descEl.textContent = selectedOption.dataset.desc;
                descEl.classList.remove('text-gray-500');
                descEl.classList.add('text-gray-700', 'dark:text-gray-300', 'font-medium');
            } else {
                descEl.textContent = 'Select a point category based on task effort and duration.';
                descEl.classList.add('text-gray-500');
                descEl.classList.remove('text-gray-700', 'dark:text-gray-300', 'font-medium');
            }
        }

        function togglePointsGuide() {
            const guide = document.getElementById('points-guide');
            const icon = document.getElementById('points-guide-icon');
            if (guide && icon) {
                guide.classList.toggle('hidden');
                icon.style.transform = guide.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        }
    </script>
</x-app-layout>
