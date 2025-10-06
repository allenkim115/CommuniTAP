<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('My Redemptions') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('status'))
                        <div class="mb-4 text-green-600">{{ session('status') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b dark:border-gray-700">
                                    <th class="py-2">Reward</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Requested</th>
                                    <th class="py-2">Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($redemptions as $r)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="py-2">{{ $r->reward->reward_name }}</td>
                                        <td class="py-2 capitalize">{{ $r->status }}</td>
                                        <td class="py-2">{{ optional($r->redemption_date)->format('Y-m-d H:i') }}</td>
                                        <td class="py-2">{{ optional($r->approval_date)->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4">No redemptions yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


