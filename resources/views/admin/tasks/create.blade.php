<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('Create Task') }}
            </h2>
            <a href="{{ route('admin.tasks.index') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-white text-sm font-semibold py-3 sm:py-2 px-4 rounded-lg transition-colors min-h-[44px]"
                style="background-color: #2B9D8D;"
                onmouseover="this.style.backgroundColor='#248A7C'"
                onmouseout="this.style.backgroundColor='#2B9D8D'">
                <i class="fas fa-arrow-left text-base"></i>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">
                
                <!-- Guidelines Section -->
                <div class="rounded-xl p-5 shadow-sm border" style="background: linear-gradient(135deg, rgba(43, 157, 141, 0.08) 0%, rgba(43, 157, 141, 0.03) 100%); border-color: rgba(43, 157, 141, 0.2);">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full" style="background-color: rgba(43, 157, 141, 0.15);">
                            <svg class="w-5 h-5" fill="none" stroke="#2B9D8D" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Admin Task Creation Guidelines</h3>
                            <p class="text-xs text-gray-500">Best practices for creating community tasks</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 border border-gray-100">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700"><strong class="font-semibold" style="color: #2B9D8D;">Daily Tasks:</strong> Recurring tasks that reset and appear daily</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 border border-gray-100">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700"><strong class="font-semibold" style="color: #2B9D8D;">One-Time Tasks:</strong> Special events, weekly or monthly activities</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 border border-gray-100">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Set <strong class="font-semibold" style="color: #2B9D8D;">appropriate points</strong> matching task effort & duration</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 border border-gray-100">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Define <strong class="font-semibold" style="color: #2B9D8D;">clear locations</strong> with specific meeting points</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 border border-gray-100">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Set <strong class="font-semibold" style="color: #2B9D8D;">realistic participant limits</strong> based on task scope</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 border border-gray-100">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Write <strong class="font-semibold" style="color: #2B9D8D;">detailed descriptions</strong> with instructions</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 border border-gray-100">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Allow <strong class="font-semibold" style="color: #2B9D8D;">sufficient lead time</strong> for participants to prepare</span>
                        </div>
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-white/60 border border-gray-100">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="#2B9D8D" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Choose to <strong class="font-semibold" style="color: #2B9D8D;">publish now or save</strong> as draft for later</span>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <form action="{{ route('admin.tasks.store') }}" method="POST" class="space-y-8" novalidate>
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left column -->
                        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm space-y-6">
                            <h4 class="text-lg font-semibold text-gray-900">Basic Information</h4>

                            <!-- Task Type -->
                            <div>
                                <label for="task_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Type <span class="text-red-500">*</span>
                                </label>
                                <select name="task_type" id="task_type" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white" 
                                    required>
                                    <option value="">Select Task Type</option>
                                    <option value="daily" {{ old('task_type') === 'daily' ? 'selected' : '' }}>Daily Task</option>
                                    <option value="one_time" {{ old('task_type') === 'one_time' ? 'selected' : '' }}>One-Time Task (Weekly/Monthly)</option>
                                </select>
                                @error('task_type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
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
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
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
                        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm space-y-6">
                            <h4 class="text-lg font-semibold text-gray-900">Schedule & Rewards</h4>

                            <!-- Points Category -->
                            <div>
                                <label for="points_awarded" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Points Category <span class="text-red-500">*</span>
                                </label>
                                
                                <!-- Point Categories Guide (Collapsible) - Above dropdown -->
                                <div class="mb-3">
                                    <button type="button" onclick="togglePointsGuide()" class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full transition-all duration-200 border" style="color: #F3A261; background-color: rgba(243, 162, 97, 0.08); border-color: rgba(243, 162, 97, 0.2);" onmouseover="this.style.backgroundColor='rgba(243, 162, 97, 0.15)'" onmouseout="this.style.backgroundColor='rgba(243, 162, 97, 0.08)'">
                                        <i class="fas fa-star text-[10px]"></i>
                                        <span>Point Categories Guide</span>
                                        <i id="points-guide-icon" class="fas fa-chevron-down text-[10px] transition-transform duration-200"></i>
                                    </button>
                                    <div id="points-guide" class="hidden mt-3 rounded-xl p-4 border shadow-sm" style="background: linear-gradient(135deg, rgba(243, 162, 97, 0.05) 0%, rgba(43, 157, 141, 0.05) 100%); border-color: rgba(243, 162, 97, 0.2);">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                            <!-- 5 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 border border-gray-100 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <i class="fas fa-star text-lg" style="color: #9CA3AF;"></i>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #9CA3AF;">5 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 text-sm">Quick</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">~5-10 mins</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Attendance, brief meetings, signing docs.</p>
                                                </div>
                                            </div>
                                            <!-- 10 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 border border-gray-100 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <i class="fas fa-star text-lg" style="color: #60A5FA;"></i>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #60A5FA;">10 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 text-sm">Light</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">~15-30 mins</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Basic cleanup, distributing materials.</p>
                                                </div>
                                            </div>
                                            <!-- 25 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 border border-gray-100 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <i class="fas fa-star text-lg" style="color: #34D399;"></i>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #34D399;">25 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 text-sm">Standard</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">~30-60 mins</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Community cleanup, event setup.</p>
                                                </div>
                                            </div>
                                            <!-- 40 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 border border-gray-100 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <i class="fas fa-star text-lg" style="color: #FBBF24;"></i>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #FBBF24;">40 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 text-sm">Moderate</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">~1-2 hours</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Full cleanup, organizing, coordination.</p>
                                                </div>
                                            </div>
                                            <!-- 60 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 border border-gray-100 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <i class="fas fa-star text-lg" style="color: #F97316;"></i>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #F97316;">60 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 text-sm">Substantial</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">~2-3 hours</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Major projects, workshops, beautification.</p>
                                                </div>
                                            </div>
                                            <!-- 80 Points -->
                                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-white/70 border border-gray-100 hover:shadow-sm transition-shadow">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <i class="fas fa-star text-lg" style="color: #EF4444;"></i>
                                                    <span class="text-[10px] font-bold mt-0.5" style="color: #EF4444;">80 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-gray-800 text-sm">Major</span>
                                                        <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">~3-4 hours</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Large events, community drives.</p>
                                                </div>
                                            </div>
                                            <!-- 100 Points - Champion -->
                                            <div class="flex items-start gap-3 p-3 rounded-lg border-2 col-span-1 sm:col-span-2" style="background: linear-gradient(135deg, rgba(243, 162, 97, 0.1) 0%, rgba(43, 157, 141, 0.1) 100%); border-color: rgba(243, 162, 97, 0.3);">
                                                <div class="flex flex-col items-center flex-shrink-0">
                                                    <div class="relative">
                                                        <i class="fas fa-star text-xl" style="color: #F3A261;"></i>
                                                        <i class="fas fa-crown text-[10px] absolute -top-1.5 left-1/2 -translate-x-1/2" style="color: #2B9D8D;"></i>
                                                    </div>
                                                    <span class="text-[10px] font-bold mt-0.5" style="background: linear-gradient(135deg, #F3A261, #2B9D8D); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">100 pts</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <span class="font-bold text-sm" style="color: #2B9D8D;">Community Champion</span>
                                                        <span class="text-[10px] font-medium px-1.5 py-0.5 rounded" style="background-color: rgba(43, 157, 141, 0.15); color: #2B9D8D;">4+ hours</span>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">Emergency response, project leadership, exceptional service, full-day events.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                                            <i class="fas fa-info-circle text-xs" style="color: #2B9D8D;"></i>
                                            <p class="text-[11px] text-gray-500">
                                                <span class="font-medium">Max cap:</span> 500 points per user. Points awarded upon task completion verification.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <select name="points_awarded" id="points_awarded"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
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
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                    min="{{ date('Y-m-d') }}" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Due date must be today or later.</p>
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start & End Time -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Start Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white @error('start_time') border-red-500 @enderror"
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
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white @error('end_time') border-red-500 @enderror"
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
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                    required>
                                    <option value="" disabled selected hidden>Select a sitio</option>
                                    @foreach(['Pig Vendor','Ermita Proper','Kastilaan','Sitio Bato','YHC','Eyeseekers','Panagdait','Kawit'] as $loc)
                                        <option value="{{ $loc }}" {{ old('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Participant Limit -->
                            <div>
                                <label for="max_participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Participant Limit
                                </label>
                                <input type="number" name="max_participants" id="max_participants"
                                    value="{{ old('max_participants') }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Leave blank for no limit" min="1">
                                @error('max_participants')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.tasks.index') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 sm:py-2.5 rounded-lg text-sm font-semibold border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition min-h-[44px]">
                            Cancel
                        </a>
                        <button type="submit" name="action" value="create"
                            class="w-full sm:w-auto px-6 py-3 sm:py-2.5 text-sm font-semibold rounded-lg text-white shadow-md transition focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 min-h-[44px] whitespace-nowrap"
                            style="background-color: #F3A261;"
                            onmouseover="this.style.backgroundColor='#E8944F'"
                            onmouseout="this.style.backgroundColor='#F3A261'">
                            Create Task
                        </button>
                        <button type="submit" name="action" value="create_and_publish"
                            class="w-full sm:w-auto px-6 py-3 sm:py-2.5 text-sm font-semibold rounded-lg text-white shadow-md transition focus:ring-2 focus:ring-teal-400 focus:ring-offset-2 min-h-[44px] whitespace-nowrap"
                            style="background-color: #2B9D8D;"
                            onmouseover="this.style.backgroundColor='#248A7C'"
                            onmouseout="this.style.backgroundColor='#2B9D8D'">
                            <span class="hidden sm:inline">Create and Publish Task</span>
                            <span class="sm:hidden">Create & Publish</span>
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
                descEl.classList.add('text-gray-700', 'font-medium');
            } else {
                descEl.textContent = 'Select a point category based on task effort and duration.';
                descEl.classList.add('text-gray-500');
                descEl.classList.remove('text-gray-700', 'font-medium');
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
</x-admin-layout>
