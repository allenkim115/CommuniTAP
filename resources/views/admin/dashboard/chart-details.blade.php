<x-admin-layout>
    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ $pageTitle }}{{ $label ? ' – '.$label : '' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $description }}
                    </p>
                    <p class="mt-2 text-xs uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        Period: {{ $periodLabel }} ({{ $currentStart->format('M d, Y') }} – {{ $currentEnd->format('M d, Y') }})
                    </p>
                </div>
                <a href="{{ $breadcrumbRoute }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Back to Dashboard
                </a>
            </div>

            @if(empty($rows))
                <div class="bg-white/80 dark:bg-gray-800/80 border border-gray-200/50 dark:border-gray-700/50 rounded-2xl p-10 text-center shadow">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        No records match this selection. Adjust the filter period or choose a different chart point.
                    </p>
                </div>
            @else
                <div class="bg-white/80 dark:bg-gray-800/80 border border-gray-200/50 dark:border-gray-700/50 rounded-2xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-800/60">
                                <tr>
                                    @foreach($columns as $column)
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            {{ $column['label'] }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900/40 divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($rows as $row)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60 transition">
                                        @foreach($columns as $column)
                                            @php
                                                $value = $row[$column['key']] ?? '—';
                                            @endphp
<td class="px-6 py-3 whitespace-nowrap capitalize">
                                                {{ $value === '' ? '—' : $value }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

