<x-admin-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- 🔹 Dashboard Section -->
            <section class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow space-y-8">

                <!-- Header: Title + PDF Button -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Admin Dashboard Overview
                    </h2>
                    <a href="{{ route('admin.dashboard.pdf') }}"
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2">
                        <i class="fas fa-file-pdf"></i>
                        Download PDF
                    </a>
                </div>

                <!-- Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Users -->
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow hover:shadow-lg transition">
                        <div class="text-4xl font-extrabold text-blue-500">{{ $totalUsers ?? 128 }}</div>
                        <p class="text-gray-600 dark:text-gray-300 font-semibold mt-1">Total Users</p>
                        <a href="{{ route('admin.users.index') }}"
                           class="text-blue-600 dark:text-blue-400 hover:underline font-medium mt-3 inline-block">
                            Manage Users →
                        </a>
                    </div>

                    <!-- Total Tasks -->
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow hover:shadow-lg transition">
                        <div class="text-4xl font-extrabold text-orange-500">{{ $totalTasks ?? 340 }}</div>
                        <p class="text-gray-600 dark:text-gray-300 font-semibold mt-1">Total Tasks</p>
                        <a href="{{ route('admin.tasks.index') }}"
                           class="text-orange-600 dark:text-orange-400 hover:underline font-medium mt-3 inline-block">
                            View Tasks →
                        </a>
                    </div>

                    <!-- Total Incidents -->
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow hover:shadow-lg transition">
                        <div class="text-4xl font-extrabold text-purple-500">{{ $totalPoints ?? 890 }}</div>
                        <p class="text-gray-600 dark:text-gray-300 font-semibold mt-1">Total Incidents</p>
                        <a href="{{ route('admin.incident-reports.index') }}"
                           class="text-purple-600 dark:text-purple-400 hover:underline font-medium mt-3 inline-block">
                            View Points →
                        </a>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Growth Line Chart -->
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-3">
                            User Growth (Static)
                        </h3>
                        <canvas id="userGrowthChart"></canvas>
                    </div>

                    <!-- Task Distribution Pie Chart -->
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-3">
                            Task Distribution (Static)
                        </h3>
                        <canvas id="taskDistributionChart"></canvas>
                    </div>
                </div>

            </section>

            <!-- 🔹 Leaderboard Section -->
            <section class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">🏆 Leaderboard</h3>
                    <button class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                        View All
                    </button>
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                Rank
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                Points
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-orange-500">1</td>
                            <td class="px-6 py-4 whitespace-nowrap">James Jone</td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold">120</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-orange-400">2</td>
                            <td class="px-6 py-4 whitespace-nowrap">Michael Angelo</td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold">97</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-orange-300">3</td>
                            <td class="px-6 py-4 whitespace-nowrap">Bon Jovi</td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold">68</td>
                        </tr>
                    </tbody>
                </table>
            </section>

        </div>
    </div>

    <!-- 🔹 Chart.js CDN + Static Data -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Line Chart (User Growth)
        const ctx1 = document.getElementById('userGrowthChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Users Joined',
                    data: [10, 25, 40, 55, 70, 90],
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Pie Chart (Task Distribution)
        const ctx2 = document.getElementById('taskDistributionChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'In Progress'],
                datasets: [{
                    data: [45, 25, 30],
                    backgroundColor: ['#22c55e', '#f97316', '#3b82f6'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#9ca3af' }
                    }
                }
            }
        });
    </script>
</x-admin-layout>
