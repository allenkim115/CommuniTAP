<x-admin-layout>
    @php
        $userCollection = collect($users ?? [])->reject(fn ($user) => strtolower($user->role ?? '') === 'admin')->values();
        $totalUsers = $userCollection->count();
        $activeUsers = $userCollection->where('status', 'active')->count();
        $suspendedUsers = $userCollection->where('status', 'suspended')->count();
        $averagePoints = $userCollection->avg('points') ?? 0;

        $statusFilter = request('status_filter');
        if ($statusFilter === 'all') {
            $statusFilter = null;
        }

        $displayUsers = $userCollection->when(
            $statusFilter && in_array($statusFilter, ['active', 'suspended']),
            fn ($collection) => $collection->where('status', $statusFilter),
            fn ($collection) => $collection
        )->values();

        $quickFilters = [
            [
                'label' => 'All users',
                'value' => null,
                'count' => $totalUsers,
                'icon' => 'fas fa-users',
            ],
            [
                'label' => 'Active',
                'value' => 'active',
                'count' => $activeUsers,
                'icon' => 'fas fa-user-check',
            ],
            [
                'label' => 'Suspended',
                'value' => 'suspended',
                'count' => $suspendedUsers,
                'icon' => 'fas fa-user-slash',
            ],
        ];

        $statCards = [
            [
                'label' => 'Total Users',
                'value' => $totalUsers,
                'context' => null,
                'icon' => 'fas fa-users',
                'accent' => 'bg-brand-teal/10 text-brand-teal-dark',
            ],
            [
                'label' => 'Active',
                'value' => $activeUsers,
                'context' => ($totalUsers ? round(($activeUsers / max($totalUsers, 1)) * 100) : 0) . '% of users',
                'icon' => 'fas fa-user-check',
                'accent' => 'bg-brand-teal/10 text-brand-teal-dark',
            ],
            [
                'label' => 'Suspended',
                'value' => $suspendedUsers,
                'context' => 'Require follow-up',
                'icon' => 'fas fa-user-slash',
                'accent' => 'bg-brand-peach text-brand-orange-dark',
            ],
            [
                'label' => 'Average Points',
                'value' => number_format($averagePoints, 0),
                'context' => 'Across active users',
                'icon' => 'fas fa-star',
                'accent' => 'bg-yellow-100 text-yellow-600',
            ],
        ];
    @endphp

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                @foreach($statCards as $card)
                    <div class="stat-card flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $card['value'] }}</p>
                            @if($card['context'])
                                <p class="text-xs text-gray-500 mt-1">{{ $card['context'] }}</p>
                            @endif
                        </div>
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl {{ $card['accent'] }}">
                            <i class="{{ $card['icon'] }} text-base"></i>
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="card-surface">
                <div class="p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">All Users</h3>
                            <p class="text-sm text-gray-500">Tap any row to view the full profile.</p>
                        </div>
                        <div class="relative w-full md:w-72">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-search h-4 w-4"></i>
                            </span>
                            <input id="user-search" type="text" placeholder="Search name, email..." class="w-full rounded-xl border border-gray-200 pl-9 pr-3 py-2 text-sm focus:border-brand-teal focus:ring-brand-teal">
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @php $baseQuery = request()->except('status_filter'); @endphp
                        @foreach($quickFilters as $filter)
                            @php
                                $isActiveFilter = ($statusFilter === $filter['value']) || (is_null($statusFilter) && is_null($filter['value']));
                                $filterUrl = $filter['value']
                                    ? route('admin.users.index', array_merge($baseQuery, ['status_filter' => $filter['value']]))
                                    : route('admin.users.index', $baseQuery);
                            @endphp
                            <a href="{{ $filterUrl }}" class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-medium transition @if($isActiveFilter) bg-brand-teal text-white shadow-sm @else border border-gray-200 text-gray-600 hover:bg-gray-50 @endif">
                                <i class="{{ $filter['icon'] }}"></i>
                                {{ $filter['label'] }}
                                <span class="text-xs font-semibold {{ $isActiveFilter ? 'text-white/80' : 'text-gray-400' }}">({{ $filter['count'] }})</span>
                            </a>
                        @endforeach
                    </div>
                    
                        @if (session('status'))
                        <div class="mt-4 rounded-2xl border border-brand-teal-dark/30 bg-brand-teal/10 px-4 py-3 text-sm font-medium text-brand-teal-dark">
                                {{ session('status') }}
                            </div>
                        @endif

                    @if($displayUsers->isNotEmpty())
                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Points</th>
                                </tr>
                            </thead>
                                <tbody class="divide-y divide-gray-100 bg-white" id="user-table-body">
                                    @foreach($displayUsers as $user)
                                        @php
                                            $fullName = $user->full_name ?? ($user->firstName.' '.$user->lastName);
                                            $searchBlob = strtolower($fullName . ' ' . $user->email . ' ' . ($user->role ?? '') . ' ' . ($user->status ?? ''));
                                        @endphp
                                        <tr data-user-row data-search="{{ $searchBlob }}" class="group cursor-pointer transition-colors hover:bg-brand-teal/5" onclick="window.location='{{ route('admin.users.show', $user) }}'">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                <div class="flex items-center gap-3">
                                                    <x-user-avatar
                                                        :user="$user"
                                                        size="h-10 w-10"
                                                        text-size="text-sm"
                                                        class="bg-brand-teal/10 text-brand-teal-dark font-semibold shadow-inner"
                                                    />
                                                    <div>
                                                        <p>{{ $fullName }}</p>
                                                        @if($user->role)
                                                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-500 capitalize">
                                                                <i class="fas fa-user text-[10px]"></i>
                                                                {{ $user->role }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $user->email }}
                                            </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->status === 'active')
                                                <span class="inline-flex items-center gap-2 badge-soft badge-soft-teal text-xs font-semibold">
                                                    <i class="fas fa-check-circle"></i>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-2 badge-soft badge-soft-orange text-xs font-semibold">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Suspended
                                                </span>
                                            @endif
                                        </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-star text-brand-teal-dark"></i>
                                                    {{ number_format($user->points ?? 0) }}
                                                </div>
                                            </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                        <div id="user-search-empty" class="hidden border border-dashed border-gray-200 rounded-2xl text-center py-8 mt-6 text-sm text-gray-500">
                            No users match your current filters.
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            No users found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('user-search');
            const rows = document.querySelectorAll('[data-user-row]');
            const emptyState = document.getElementById('user-search-empty');

            if (!searchInput || rows.length === 0) {
                return;
            }

            function applyFilter() {
                const query = searchInput.value.trim().toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const haystack = row.dataset.search || '';
                    const matches = !query || haystack.includes(query);
                    row.classList.toggle('hidden', !matches);
                    if (matches) {
                        visibleCount++;
                    }
                });

                if (emptyState) {
                    emptyState.classList.toggle('hidden', visibleCount !== 0);
                }
            }

            searchInput.addEventListener('input', applyFilter);
            applyFilter();
        });
    </script>
    @endpush
</x-admin-layout>
