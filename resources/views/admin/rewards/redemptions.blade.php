<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Reward Redemptions') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('status'))
                        <div class="mb-4 text-green-600">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 text-red-600">{{ session('error') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b dark:border-gray-700">
                                    <th class="py-2">User</th>
                                    <th class="py-2">Reward</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Requested</th>
                                    <th class="py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($redemptions as $red)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="py-2">{{ $red->user?->name }}</td>
                                        <td class="py-2">{{ $red->reward?->reward_name }}</td>
                                        <td class="py-2 capitalize">{{ $red->status }}</td>
                                        <td class="py-2">{{ optional($red->redemption_date)->format('Y-m-d H:i') }}</td>
                                        <td class="py-2">
                                            @if($red->status === 'pending')
                                                <form action="{{ route('admin.redemptions.approve', $red) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
                                                </form>
                                                <form action="{{ route('admin.redemptions.reject', $red) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">No actions</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>


