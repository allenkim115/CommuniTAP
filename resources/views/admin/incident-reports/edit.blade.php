<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Moderate Incident Report') }}
        </h2>
    </x-slot>

    @php
        $statusClasses = [
            'pending' => 'badge-soft badge-soft-orange',
            'under_review' => 'badge-soft badge-soft-teal',
            'resolved' => 'badge-soft badge-soft-teal',
            'dismissed' => 'badge-soft badge-soft-slate',
        ];
    @endphp

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card-surface">
                <div class="p-6 lg:p-8 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-wide text-gray-500">Report</p>
                            <h3 class="text-2xl font-semibold text-gray-900">#{{ $incidentReport->reportId }}</h3>
                            <p class="text-sm text-gray-500">
                                Submitted {{ $incidentReport->report_date->format('F d, Y \a\t g:i A') }}
                        </p>
                        </div>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold {{ $statusClasses[$incidentReport->status] ?? 'badge-soft badge-soft-slate' }}">
                            {{ ucwords(str_replace('_', ' ', $incidentReport->status)) }}
                        </span>
                    </div>

                    <!-- Report Details Summary -->
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 dark:bg-gray-700/40 p-6 mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Report Summary</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reporter</h5>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $incidentReport->reporter->fullName }}</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reported User</h5>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $incidentReport->reportedUser->fullName }}</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Incident Type</h5>
                                @php
                                    $typeClass = in_array($incidentReport->incident_type, ['abuse', 'spam', 'harassment', 'inappropriate_content'])
                                        ? 'badge-soft badge-soft-teal'
                                        : 'badge-soft badge-soft-orange';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $typeClass }}">
                                    {{ ucwords(str_replace('_', ' ', $incidentReport->incident_type)) }}
                                </span>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Current Status</h5>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $statusClasses[$incidentReport->status] ?? 'badge-soft badge-soft-slate' }}">
                                    {{ ucwords(str_replace('_', ' ', $incidentReport->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Report Description</h4>
                        <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5">
                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $incidentReport->description }}</p>
                        </div>
                    </div>

                    <!-- Evidence -->
                    @if($incidentReport->evidence)
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Additional Evidence</h4>
                            <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5 space-y-3">
                                @php
                                    $evidenceLines = preg_split("/\r?\n/", (string) $incidentReport->evidence);
                                @endphp
                                @foreach($evidenceLines as $line)
                                    @php $trimmed = trim($line); @endphp
                                    @if($trimmed !== '')
                                        @if(strpos($trimmed, 'Image: ') === 0)
                                            @php $path = trim(substr($trimmed, 7)); @endphp
                                            <div>
                                                <img src="{{ asset('storage/' . $path) }}" alt="Incident evidence image" class="max-h-80 rounded border border-gray-200 dark:border-gray-600">
                                                <div class="mt-1 text-xs text-gray-500 break-all">{{ $path }}</div>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $trimmed }}</p>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Moderation Form -->
                    <form method="POST" action="{{ route('admin.incident-reports.update', $incidentReport) }}" novalidate>
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full rounded-xl border-gray-200 focus:border-brand-teal focus:ring-brand-teal sm:text-sm" required>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ old('status', $incidentReport->status) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <!-- Action Taken -->
                            <div>
                                <x-input-label for="action_taken" :value="__('Action Taken')" />
                                <select id="action_taken" name="action_taken" class="mt-1 block w-full rounded-xl border-gray-200 focus:border-brand-teal focus:ring-brand-teal sm:text-sm">
                                    <option value="">Select action...</option>
                                    @foreach($actionsTaken as $key => $label)
                                        <option value="{{ $key }}" {{ old('action_taken', $incidentReport->action_taken) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('action_taken')" />
                            </div>
                        </div>

                        <!-- Moderator Notes -->
                        <div class="mb-6">
                            <x-input-label for="moderator_notes" :value="__('Moderator Notes')" />
                            <textarea id="moderator_notes" 
                                      name="moderator_notes" 
                                      rows="4" 
                                      class="mt-1 block w-full rounded-xl border-gray-200 focus:border-brand-teal focus:ring-brand-teal sm:text-sm" 
                                      placeholder="Add your moderation notes here...">{{ old('moderator_notes', $incidentReport->moderator_notes) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                These notes will be visible to the reporter and may be used for internal documentation.
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('moderator_notes')" />
                        </div>

                        <!-- Warning for Suspension -->
                        <div id="suspension-warning" class="hidden mb-6 rounded-2xl border border-brand-orange-dark/40 bg-brand-peach/60 p-5 text-brand-orange-dark">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-semibold">
                                        Warning: User Suspension
                                    </h3>
                                    <div class="mt-2 text-sm">
                                        <p>Selecting "User Suspended" will immediately suspend the reported user's account. This action should only be taken after careful consideration of the evidence.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-end">
                            <a href="{{ route('admin.incident-reports.show', $incidentReport) }}" 
                               class="btn-muted">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="btn-brand">
                                Update Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show/hide suspension warning
        document.getElementById('action_taken').addEventListener('change', function() {
            const warning = document.getElementById('suspension-warning');
            if (this.value === 'suspension') {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        });

        // Initialize warning visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            const actionTaken = document.getElementById('action_taken');
            const warning = document.getElementById('suspension-warning');
            if (actionTaken.value === 'suspension') {
                warning.classList.remove('hidden');
            }
        });
    </script>
    @endpush
</x-admin-layout>
