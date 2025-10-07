<x-admin-layout>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Two-column layout: left stats + leaderboard, right rewards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left column: Available Rewards -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Available Rewards</h3>
                            <div class="flex space-x-3 mb-6">
                                <button class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-sm font-semibold">All</button>
                                <button class="px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold">Available</button>
                                <button class="px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold">Out of Stock</button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden shadow-sm">
                                    <div class="px-4 pt-4 flex items-center justify-between text-xs text-gray-500">
                                        <div class="flex items-center space-x-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                                        </div>
                                        <div class="font-semibold">100 Points</div>
                                    </div>
                                    <div class="mt-3 h-56 bg-gray-200 dark:bg-gray-700"></div>
                                    <div class="p-5">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">GET FREE DRINKS</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Attend activities and have free drinks from Uncle Brew - Mambaling.</p>
                                        <div class="mt-4 flex justify-end space-x-3 text-gray-500">
                                            <button class="p-2 hover:text-red-600" title="Delete"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 7h4m-1-2h-2a2 2 0 00-2 2h6a2 2 0 00-2-2z"/></svg></button>
                                            <button class="p-2 hover:text-teal-600" title="Edit"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 20h4l10.586-10.586a2 2 0 10-2.828-2.828L5.172 17.172A4 4 0 004 20z"/></svg></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden shadow-sm">
                                    <div class="px-4 pt-4 flex items-center justify-between text-xs text-gray-500">
                                        <div class="flex items-center space-x-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                                        </div>
                                        <div class="font-semibold">100 Points</div>
                                    </div>
                                    <div class="mt-3 h-56 bg-blue-600 flex items-center justify-center"><span class="text-white font-bold text-2xl">GCash</span></div>
                                    <div class="p-5">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">50 FREE GCASH</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Attend activities and win free gcash.</p>
                                        <div class="mt-4 flex justify-end space-x-3 text-gray-500">
                                            <button class="p-2 hover:text-red-600" title="Delete"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 7h4m-1-2h-2a2 2 0 00-2 2h6a2 2 0 00-2-2z"/></svg></button>
                                            <button class="p-2 hover:text-teal-600" title="Edit"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 20h4l10.586-10.586a2 2 0 10-2.828-2.828L5.172 17.172A4 4 0 004 20z"/></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right column: Stats + Leaderboards -->
                <div class="space-y-8 lg:col-span-1">
                    <!-- Stats stacked -->
                    <div class="grid grid-cols-1 gap-6">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl">
                            <div class="p-8">
                                <div class="text-5xl font-extrabold text-orange-400">{{ $totalUsers ?? 0 }}</div>
                                <div class="mt-2 text-lg font-semibold text-gray-700 dark:text-gray-300">Users</div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl">
                            <div class="p-8">
                                <div class="text-5xl font-extrabold text-orange-400">{{ $activeUsers ?? 0 }}</div>
                                <div class="mt-2 text-lg font-semibold text-gray-700 dark:text-gray-300">Active</div>
                            </div>
                        </div>
                    </div>

                    <!-- Leaderboards below stats -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Leaderboards</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Rank</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Points</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap"><span class="font-bold">1</span></td>
                                            <td class="px-6 py-4 whitespace-nowrap">James Jone</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold">120</td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap"><span class="font-bold">2</span></td>
                                            <td class="px-6 py-4 whitespace-nowrap">Michael Angelo</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold">97</td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap"><span class="font-bold">3</span></td>
                                            <td class="px-6 py-4 whitespace-nowrap">Bon Jovi</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold">68</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
