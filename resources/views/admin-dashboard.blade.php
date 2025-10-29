<x-admin-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- 🔹 Dashboard Section -->
            <section class="backdrop-blur-xl bg-white/80 dark:bg-gray-800/80 p-8 rounded-3xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300">

                <!-- Header: Title + PDF Button -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            Admin Dashboard
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Real-time insights and system overview</p>
                    </div>
                    <a href="{{ route('admin.dashboard.pdf') }}"
                       class="group bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2.5 shadow-lg hover:shadow-red-600/25 transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-file-pdf text-sm"></i>
                        <span>Download Report</span>
                    </a>
                </div>

                <!-- Overview Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                    <!-- Total Users -->
                    <div class="group relative bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-white/10 rounded-2xl blur-xl group-hover:blur-2xl transition"></div>
                        <div class="relative z-10">
                            <div class="text-5xl font-black text-white">{{ $totalUsers ?? 128 }}</div>
                            <p class="text-blue-100 font-semibold mt-1 text-sm">Total Users</p>
                            <a href="{{ route('admin.users.index') }}"
                               class="text-white/90 hover:text-white font-medium text-sm mt-3 inline-flex items-center gap-1 transition">
                                Manage Users <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Total Tasks -->
                    <div class="group relative bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-white/10 rounded-2xl blur-xl group-hover:blur-2xl transition"></div>
                        <div class="relative z-10">
                            <div class="text-5xl font-black text-white">{{ $totalTasks ?? 340 }}</div>
                            <p class="text-orange-100 font-semibold mt-1 text-sm">Total Tasks</p>
                            <a href="{{ route('admin.tasks.index') }}"
                               class="text-white/90 hover:text-white font-medium text-sm mt-3 inline-flex items-center gap-1 transition">
                                View Tasks <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Total Incidents -->
                    <div class="group relative bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-white/10 rounded-2xl blur-xl group-hover:blur-2xl transition"></div>
                        <div class="relative z-10">
                            <div class="text-5xl font-black text-white">{{ $totalPoints ?? 890 }}</div>
                            <p class="text-purple-100 font-semibold mt-1 text-sm">Total Incidents</p>
                            <a href="{{ route('admin.incident-reports.index') }}"
                               class="text-white/90 hover:text-white font-medium text-sm mt-3 inline-flex items-center gap-1 transition">
                                View Incidents <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- User Growth Line Chart -->
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-gray-200/30 dark:border-gray-700/30">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">User Growth Trend</h3>
                            <span class="text-xs text-green-600 dark:text-green-400 font-medium bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full">+28% ↑</span>
                        </div>
                        <canvas id="userGrowthChart" class="mt-2"></canvas>
                    </div>

                    <!-- Task Distribution Pie Chart -->
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-gray-200/30 dark:border-gray-700/30">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Task Status</h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Last 30 days</span>
                        </div>
                        <canvas id="taskDistributionChart" class="mt-2"></canvas>
                    </div>
                </div>
            </section>

            <!-- 🔹 Leaderboard Section -->
            <section class="backdrop-blur-xl bg-white/80 dark:bg-gray-800/80 p-6 rounded-3xl shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="text-yellow-500">🏆</span> Top Performers
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Leaderboard by points</p>
                    </div>
                    <button class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-5 py-2 rounded-xl text-sm font-medium shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                        View All
                    </button>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50/80 dark:bg-gray-700/50 backdrop-blur-sm">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Rank
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Points
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-orange-400 to-orange-500 text-white font-bold text-sm shadow-md">
                                        1
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-medium">James Jone</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300">
                                        120 pts
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-orange-300 to-orange-400 text-white font-bold text-sm shadow-md">
                                        2
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-medium">Michael Angelo</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400">
                                        97 pts
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 to-amber-500 text-white font-bold text-sm shadow-md">
                                        3
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-medium">Bon Jovi</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">
                                        68 pts
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </div>

    <!-- 🔹 Enhanced Chart.js with Modern Styling -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
        Chart.defaults.color = '#6b7280';

       // Line Chart - User Growth
const ctx1 = document.getElementById('userGrowthChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Users Joined',
            data: @json($userGrowth),
            borderColor: '#f97316',
            backgroundColor: 'rgba(249, 115, 22, 0.15)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#f97316',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Doughnut Chart - Task Distribution
const ctx2 = document.getElementById('taskDistributionChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Pending', 'In Progress'],
        datasets: [{
            data: [{{ $tasksCompleted }}, {{ $tasksPending }}, {{ $tasksInProgress }}],
            backgroundColor: ['#22c55e', '#f97316', '#3b82f6'],
            borderColor: '#fff',
            borderWidth: 3,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

    </script>

    <style>
        canvas {
            height: 220px !important;
        }
        @media (max-width: 640px) {
            canvas {
                height: 180px !important;
            }
        }
    </style>
</x-admin-layout>