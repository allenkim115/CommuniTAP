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

    <div class="py-4 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($statCards as $card)
                    <div class="stat-card flex items-start justify-between p-4 sm:p-5">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                            <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-semibold text-gray-900">{{ $card['value'] }}</p>
                            @if($card['context'])
                                <p class="text-[10px] sm:text-xs text-gray-500 mt-1 leading-tight">{{ $card['context'] }}</p>
                            @endif
                        </div>
                        <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl flex-shrink-0 ml-2 {{ $card['accent'] }}">
                            <i class="{{ $card['icon'] }} text-sm sm:text-base"></i>
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="card-surface">
                <div class="p-3 sm:p-6">
                    <div class="flex flex-col gap-3 sm:gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base sm:text-xl font-semibold text-gray-900">All Users</h3>
                            <p class="hidden sm:block text-xs sm:text-sm text-gray-500">Tap any row to view the full profile.</p>
                        </div>
                        <div class="relative w-full sm:w-72">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 text-gray-400">
                                <i class="fas fa-search h-4 w-4 sm:h-5 sm:w-5"></i>
                            </span>
                            <input id="user-search" type="text" placeholder="Search..." class="w-full rounded-lg sm:rounded-xl border border-gray-200 pl-10 sm:pl-12 pr-4 py-2 sm:py-2 text-sm focus:border-brand-teal focus:ring-2 focus:ring-brand-teal min-h-[40px] sm:min-h-[44px]">
                        </div>
                    </div>

                    <div class="mt-3 sm:mt-4 flex flex-wrap gap-2">
                        @php $baseQuery = request()->except('status_filter'); @endphp
                        @foreach($quickFilters as $filter)
                            @php
                                $isActiveFilter = ($statusFilter === $filter['value']) || (is_null($statusFilter) && is_null($filter['value']));
                                $filterUrl = $filter['value']
                                    ? route('admin.users.index', array_merge($baseQuery, ['status_filter' => $filter['value']]))
                                    : route('admin.users.index', $baseQuery);
                            @endphp
                            <a href="{{ $filterUrl }}" class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full px-3 py-1.5 sm:px-4 sm:py-1.5 text-sm font-semibold transition min-h-[36px] sm:min-h-[40px] @if($isActiveFilter) bg-brand-teal text-white shadow-sm @else border border-gray-200 text-gray-600 hover:bg-gray-50 @endif">
                                <i class="{{ $filter['icon'] }} text-xs sm:text-sm"></i>
                                <span class="hidden sm:inline">{{ $filter['label'] }}</span>
                                <span class="text-xs sm:text-xs font-bold {{ $isActiveFilter ? 'text-white/90' : 'text-gray-500' }}">({{ $filter['count'] }})</span>
                            </a>
                        @endforeach
                    </div>
                    
                        @if (session('status'))
                        <div class="mt-4 rounded-2xl border border-brand-teal-dark/30 bg-brand-teal/10 px-4 py-3 text-sm font-medium text-brand-teal-dark">
                                {{ session('status') }}
                            </div>
                        @endif

                    @if($displayUsers->isNotEmpty())
                        <!-- Mobile Card View -->
                        <div class="mt-6 grid grid-cols-1 sm:hidden gap-4" id="user-mobile-cards">
                            @foreach($displayUsers as $user)
                                @php
                                    $fullName = $user->full_name ?? ($user->firstName.' '.$user->lastName);
                                    $searchBlob = strtolower($fullName . ' ' . $user->email . ' ' . ($user->role ?? '') . ' ' . ($user->status ?? ''));
                                @endphp
                                <div
                                    data-user-row
                                    data-search="{{ $searchBlob }}"
                                    data-url="{{ route('admin.users.show', $user) }}"
                                    class="bg-white rounded-lg border border-gray-200 p-4 cursor-pointer transition-colors hover:bg-brand-teal/5 hover:border-brand-teal"
                                    onclick="window.location='{{ route('admin.users.show', $user) }}'"
                                >
                                    <div class="flex items-start gap-3 mb-3">
                                        <x-user-avatar
                                            :user="$user"
                                            size="h-12 w-12"
                                            text-size="text-sm"
                                            class="bg-brand-teal/10 text-brand-teal-dark font-semibold shadow-inner flex-shrink-0"
                                        />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $fullName }}</p>
                                            <p class="text-xs text-gray-600 truncate mt-1">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                        <div>
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
                                        </div>
                                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                                            <i class="fas fa-star text-brand-teal-dark"></i>
                                            {{ number_format($user->points ?? 0) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Desktop Table View -->
                        <div class="mt-6 overflow-x-auto hidden sm:block">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Name</th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Email</th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Points</th>
                                </tr>
                            </thead>
                                <tbody class="divide-y divide-gray-100 bg-white" id="user-table-body">
                                    @foreach($displayUsers as $user)
                                        @php
                                            $fullName = $user->full_name ?? ($user->firstName.' '.$user->lastName);
                                            $searchBlob = strtolower($fullName . ' ' . $user->email . ' ' . ($user->role ?? '') . ' ' . ($user->status ?? ''));
                                        @endphp
                                        <tr
                                            data-user-row
                                            data-search="{{ $searchBlob }}"
                                            data-url="{{ route('admin.users.show', $user) }}"
                                            class="group cursor-pointer transition-colors hover:bg-brand-teal/5"
                                            onclick="window.location='{{ route('admin.users.show', $user) }}'"
                                        >
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                <div class="flex items-center gap-3">
                                                    <x-user-avatar
                                                        :user="$user"
                                                        size="h-10 w-10"
                                                        text-size="text-sm"
                                                        class="bg-brand-teal/10 text-brand-teal-dark font-semibold shadow-inner"
                                                    />
                                                    <div>
                                                        <p>{{ $fullName }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $user->email }}
                                            </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
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
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
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
                        <div id="user-search-empty" class="hidden bg-white rounded-2xl border border-dashed border-gray-200 shadow-sm p-10 text-center mt-6">
                            <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-search text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No users match your search</h3>
                            <p class="text-gray-600 text-sm">Try a different keyword.</p>
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
        (() => {
            const searchInput = document.getElementById('user-search');
            const entries = Array.from(document.querySelectorAll('[data-user-row]'));
            const emptyState = document.getElementById('user-search-empty');

            if (!searchInput || entries.length === 0) {
                console.info('User search: missing input or entries', { hasInput: !!searchInput, entries: entries.length });
                return;
            }

            const applyFilter = () => {
                const query = (searchInput.value || '').trim().toLowerCase();
                let visibleCount = 0;

                entries.forEach(entry => {
                    const haystack = (entry.dataset.search || '').toLowerCase();
                    const matches = !query || haystack.includes(query);
                    entry.classList.toggle('hidden', !matches);
                    if (matches) visibleCount++;
                });

                if (emptyState) {
                    emptyState.classList.toggle('hidden', visibleCount !== 0);
                }
            };

            const navigateToFirstVisible = () => {
                const firstVisible = entries.find(entry => !entry.classList.contains('hidden') && entry.dataset.url);
                if (firstVisible && firstVisible.dataset.url) {
                    window.location = firstVisible.dataset.url;
                }
            };

            searchInput.addEventListener('input', applyFilter);
            searchInput.addEventListener('keyup', applyFilter);
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    navigateToFirstVisible();
                }
            });
            applyFilter();
        })();
    </script>
    @endpush
</x-admin-layout>
