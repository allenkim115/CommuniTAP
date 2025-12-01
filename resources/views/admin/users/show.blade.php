<x-admin-layout>
    @php
        $fullName = $user->full_name ?? ($user->firstName.' '.$user->lastName);
        $statusBadges = [
            'active' => 'badge-soft badge-soft-teal',
            'suspended' => 'badge-soft badge-soft-orange',
        ];
        $statusIndicatorMap = [
            'active' => 'bg-brand-teal',
            'suspended' => 'bg-brand-orange-dark',
        ];
        $statusIndicator = $statusIndicatorMap[$user->status] ?? 'bg-gray-400';
    @endphp

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <a href="{{ route('admin.users.index') }}" class="btn-muted inline-flex w-auto gap-2">
                <i class="fas fa-arrow-left text-sm"></i>
                Back to users
            </a>

            @if(session('status'))
                <div class="rounded-2xl border border-brand-teal-dark/30 bg-brand-teal/10 px-4 py-3 text-sm font-medium text-brand-teal-dark">
                    {{ session('status') }}
            </div>
            @endif

            <div class="card-surface">
                <div class="p-6 lg:p-8 flex flex-col gap-6 md:flex-row md:items-center">
                    <div class="relative">
                        <x-user-avatar
                            :user="$user"
                            size="h-24 w-24"
                            text-size="text-3xl"
                            class="bg-gradient-to-br from-brand-orange to-brand-teal text-white font-bold shadow-lg"
                        />
                        <span class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full border-4 border-white {{ $statusIndicator }}"></span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm uppercase tracking-wide text-gray-500">Account Profile</p>
                        <h1 class="mt-1 text-3xl font-semibold text-gray-900">{{ $fullName }}</h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="fas fa-envelope text-brand-teal-dark"></i>
                            {{ $user->email }}
                        </p>
                        <div class="mt-3 flex flex-wrap gap-3">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $statusBadges[$user->status] ?? 'badge-soft badge-soft-slate' }}">
                                <i class="fas fa-check-circle"></i>
                                {{ ucfirst($user->status) }}
                            </span>
                            @if($user->role)
                                <span class="inline-flex items-center gap-2 badge-soft badge-soft-slate text-xs font-semibold capitalize">
                                    <i class="fas fa-user"></i>
                                    {{ $user->role }}
                                </span>
                            @endif
                        </div>
                            </div>
                    <div class="text-left md:text-right">
                        <p class="text-sm text-gray-500 flex items-center gap-2 justify-start md:justify-end">
                            <i class="fas fa-star text-brand-teal-dark"></i>
                            Total Points
                        </p>
                        <p class="text-3xl font-semibold text-brand-teal-dark">{{ number_format($user->points ?? 0) }}</p>
                    </div>
                </div>
                                </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm flex items-start gap-4">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-peach text-brand-orange-dark">
                        <i class="fas fa-envelope-open-text"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">Email address</p>
                                    <p class="text-lg font-semibold text-gray-900 break-all">{{ $user->email }}</p>
                            </div>
                        </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm flex items-start gap-4">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-teal/10 text-brand-teal-dark">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">Tasks completed</p>
                        <p class="text-2xl font-semibold text-brand-teal-dark">{{ number_format($tasksCompleted ?? 0) }}</p>
                            </div>
                        </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm flex items-start gap-4">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-peach text-brand-orange-dark">
                        <i class="fas fa-tasks"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">Tasks in progress</p>
                        <p class="text-2xl font-semibold text-brand-orange-dark">{{ number_format($tasksInProgress ?? 0) }}</p>
                            </div>
                        </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm flex items-start gap-4">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-teal/10 text-brand-teal-dark">
                        <i class="fas fa-clipboard-list"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">Total tasks assigned</p>
                        <p class="text-2xl font-semibold text-brand-teal-dark">{{ number_format($tasksAssigned ?? 0) }}</p>
                            </div>
                        </div>

                    @if(isset($user->date_registered))
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm md:col-span-2 flex items-start gap-4">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <div>
                            <p class="text-sm text-gray-500">Registration date</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($user->date_registered)->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>
                @endif
                        </div>

            <div class="card-surface">
                <div class="p-6 lg:p-8">
                    <div class="flex flex-col gap-2">
                        <h3 class="text-xl font-semibold text-gray-900">Account actions</h3>
                        <p class="text-sm text-gray-500">Suspend or reactivate the account. Changes take effect immediately.</p>
                    </div>

                    <div class="mt-6 flex flex-col gap-4 md:flex-row">
                            @if($user->status === 'active')
                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}" id="suspend-user-form" class="flex-1" novalidate>
                                    @csrf
                                    @method('PATCH')
                                <button type="button"
                                        onclick="showConfirmModal('Are you sure you want to suspend this user?', 'Suspend User', 'Suspend', 'Cancel', 'red').then(confirmed => { if(confirmed) document.getElementById('suspend-user-form').submit(); });"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-6 py-3 font-semibold text-red-600 transition hover:bg-red-100">
                                    Suspend user
                                    </button>
                                </form>
                            @else
                            <form method="POST" action="{{ route('admin.users.reactivate', $user) }}" id="reactivate-user-form" class="flex-1" novalidate>
                                    @csrf
                                    @method('PATCH')
                                <button type="button"
                                        onclick="showConfirmModal('Are you sure you want to reactivate this user?', 'Reactivate User', 'Reactivate', 'Cancel', 'green').then(confirmed => { if(confirmed) document.getElementById('reactivate-user-form').submit(); });"
                                        class="btn-brand w-full justify-center">
                                    Reactivate user
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
        </div>
    </div>
</x-admin-layout>
