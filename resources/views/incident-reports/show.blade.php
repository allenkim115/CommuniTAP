<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Incident Report Details') }}
        </h2>
    </x-slot>

    @php
        $statusClasses = [
            'pending' => 'badge-soft badge-soft-amber',
            'under_review' => 'badge-soft badge-soft-teal',
            'resolved' => 'badge-soft badge-soft-teal',
            'dismissed' => 'badge-soft badge-soft-slate',
        ];
        $typeClasses = [
            'non_participation' => 'badge-soft badge-soft-amber',
            'abuse' => 'badge-soft badge-soft-orange',
            'spam' => 'badge-soft badge-soft-teal',
            'inappropriate_content' => 'badge-soft badge-soft-orange',
            'harassment' => 'badge-soft badge-soft-orange',
        ];

        $timeline = [
            [
                'label' => 'Report submitted',
                'date' => $incidentReport->report_date,
                'description' => 'You shared details about this incident.',
                'complete' => true,
            ],
            [
                'label' => 'Moderation in progress',
                'date' => $incidentReport->moderation_date,
                'description' => $incidentReport->moderator ? 'A moderator is reviewing your report.' : 'Waiting for a moderator to review.',
                'complete' => in_array($incidentReport->status, ['under_review', 'resolved', 'dismissed']),
            ],
            [
                'label' => 'Outcome shared',
                'date' => $incidentReport->moderation_date,
                'description' => $incidentReport->action_taken ? 'Moderation outcome recorded.' : 'Pending final decision.',
                'complete' => in_array($incidentReport->status, ['resolved', 'dismissed']),
            ],
        ];
    @endphp

    <div class="py-6 sm:py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            <div class="flex justify-end">
                <a href="{{ route('incident-reports.index') }}" class="btn-muted text-sm sm:text-base">
                    Back to reports
                </a>
            </div>

            <div class="card-surface p-4 sm:p-6 lg:p-8 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 sm:pb-6 md:flex-row md:items-start md:justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="section-heading text-xs sm:text-sm">Incident Report #{{ $incidentReport->reportId }}</p>
                        <h3 class="mt-2 text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">Detailed summary</h3>
                        <p class="mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            Submitted on {{ $incidentReport->report_date->format('F d, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <div class="flex flex-col items-start gap-3 md:items-end md:flex-shrink-0">
                        <span class="{{ $statusClasses[$incidentReport->status] ?? 'badge-soft badge-soft-slate' }} text-xs sm:text-sm">
                            {{ ucwords(str_replace('_', ' ', $incidentReport->status)) }}
                        </span>
                        <div class="flex flex-wrap gap-2 w-full md:w-auto">
                            @if($incidentReport->isPending() && Auth::user() && Auth::user()->isAdmin())
                                <a href="{{ route('admin.incident-reports.edit', $incidentReport) }}" class="btn-brand text-sm sm:text-base w-full md:w-auto">
                                    Moderate report
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-6 sm:mt-8 grid gap-4 sm:gap-6 md:grid-cols-3">
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-3 sm:p-4 md:col-span-2">
                        <p class="section-heading text-xs sm:text-sm">Reported user</p>
                        <div class="mt-3 flex items-center gap-3 sm:gap-4">
                            <x-user-avatar
                                :user="$incidentReport->reportedUser"
                                size="h-10 w-10 sm:h-12 sm:w-12"
                                text-size="text-sm sm:text-base"
                                class="bg-brand-peach/60 text-brand-orange-dark font-semibold flex-shrink-0"
                            />
                            <div class="min-w-0 flex-1">
                                <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white truncate">{{ $incidentReport->reportedUser->fullName }}</p>
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">{{ $incidentReport->reportedUser->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-3 sm:p-4">
                        <p class="section-heading text-xs sm:text-sm">Incident type</p>
                        <span class="{{ $typeClasses[$incidentReport->incident_type] ?? 'badge-soft badge-soft-slate' }} mt-3 inline-flex text-xs sm:text-sm">
                            {{ ucwords(str_replace('_', ' ', $incidentReport->incident_type)) }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 sm:mt-8 grid gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 p-3 sm:p-4">
                        <p class="section-heading text-xs sm:text-sm">Reported on</p>
                        <p class="mt-3 text-sm sm:text-base font-semibold text-gray-900 dark:text-white">{{ $incidentReport->report_date->format('M d, Y') }}</p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $incidentReport->report_date->format('g:i A') }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 p-3 sm:p-4">
                        <p class="section-heading text-xs sm:text-sm">Current status</p>
                        <p class="mt-3 text-sm sm:text-base font-semibold text-gray-900 dark:text-white">{{ ucwords(str_replace('_', ' ', $incidentReport->status)) }}</p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">We'll notify you when this updates.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 p-3 sm:p-4 sm:col-span-2 lg:col-span-1">
                        <p class="section-heading text-xs sm:text-sm">Moderator assigned</p>
                        <p class="mt-3 text-sm sm:text-base font-semibold text-gray-900 dark:text-white">
                            {{ $incidentReport->moderator ? $incidentReport->moderator->fullName : 'Pending assignment' }}
                        </p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            {{ $incidentReport->moderator ? 'Review in progress' : 'Awaiting moderator' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 sm:mt-10">
                    <p class="section-heading mb-3 sm:mb-4 text-xs sm:text-sm">Review timeline</p>
                    <ol class="relative space-y-4 sm:space-y-6 border-l border-gray-200 dark:border-gray-700 pl-4 sm:pl-6">
                        @foreach($timeline as $step)
                            <li class="ml-3 sm:ml-4">
                                <span class="absolute -left-2.5 sm:-left-3 flex h-5 w-5 sm:h-6 sm:w-6 items-center justify-center rounded-full {{ $step['complete'] ? 'bg-brand-teal text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' }}">
                                    <svg class="h-2.5 w-2.5 sm:h-3 sm:w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['complete'] ? 'M5 13l4 4L19 7' : 'M12 8v4l2 2' }}"></path>
                                    </svg>
                                </span>
                                <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/40 p-3 sm:p-4">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white">{{ $step['label'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap sm:ml-2">
                                            {{ $step['date'] ? $step['date']->format('M d, Y g:i A') : 'Pending' }}
                                        </p>
                                    </div>
                                    <p class="mt-1 text-xs sm:text-sm text-gray-600 dark:text-gray-300">{{ $step['description'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                @if($incidentReport->task)
                    <div class="mt-6 sm:mt-10">
                        <p class="section-heading mb-3 text-xs sm:text-sm">Related task</p>
                        <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4 sm:p-5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('tasks.show', $incidentReport->task) }}" class="text-sm sm:text-base font-semibold text-brand-teal hover:text-brand-teal-dark dark:text-brand-peach dark:hover:text-brand-peach-dark break-words">
                                        {{ $incidentReport->task->title }}
                                    </a>
                                    <p class="mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-300">{{ Str::limit($incidentReport->task->description, 220) }}</p>
                                </div>
                                <span class="badge-soft badge-soft-teal text-xs sm:text-sm flex-shrink-0 mt-2 md:mt-0">{{ ucwords(str_replace('_', ' ', $incidentReport->task->task_type)) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-6 sm:mt-10">
                    <p class="section-heading mb-3 text-xs sm:text-sm">Description</p>
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/60 p-4 sm:p-5">
                        <p class="text-xs sm:text-sm leading-relaxed text-gray-800 dark:text-gray-100 whitespace-pre-wrap break-words">{{ $incidentReport->description }}</p>
                    </div>
                </div>

                @if($incidentReport->evidence)
                    <div class="mt-6 sm:mt-10">
                        <p class="section-heading mb-3 text-xs sm:text-sm">Additional evidence</p>
                        <div class="grid gap-3 sm:gap-4 sm:grid-cols-2">
                            @php
                                $evidenceLines = preg_split("/\r?\n/", (string) $incidentReport->evidence);
                            @endphp
                            @foreach($evidenceLines as $line)
                                @php $trimmed = trim($line); @endphp
                                @if($trimmed !== '')
                                    @if(strpos($trimmed, 'Image: ') === 0)
                                        @php $path = trim(substr($trimmed, 7)); @endphp
                                        @php $photoUrl = asset('storage/' . $path); @endphp
                                        <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-3 sm:p-4 sm:col-span-2 lg:col-span-1">
                                            <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer group"
                                                 onclick="openImageModal('{{ $photoUrl }}')">
                                                <img
                                                    src="{{ $photoUrl }}"
                                                    alt="Incident evidence image"
                                                    class="w-full h-48 sm:h-64 object-cover bg-white rounded-2xl transition-transform duration-200 group-hover:scale-[1.02]"
                                                    data-photo-url="{{ $photoUrl }}"
                                                    onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0zMCAzMEg3MFY3MEgzMFYzMFoiIHN0cm9rZT0iIzlDQTNBRiIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPHBhdGggZD0iTTQwIDQwTDUwIDUwTDYwIDQwIiBzdHJva2U9IiM5Q0EzQUYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxjaXJjbGUgY3g9IjQ1IiBjeT0iNDUiIHI9IjMiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+';"
                                                >
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 flex items-center justify-center">
                                                    <div class="bg-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                        <i class="fas fa-search-plus text-gray-800"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/60 p-3 sm:p-4 sm:col-span-2">
                                            <p class="text-xs sm:text-sm text-gray-800 dark:text-gray-100 whitespace-pre-wrap break-words">{{ $trimmed }}</p>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($incidentReport->moderator)
                    <div class="mt-6 sm:mt-10">
                        <p class="section-heading mb-3 text-xs sm:text-sm">Moderation details</p>
                        <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/60 p-4 sm:p-5 space-y-4 sm:space-y-6">
                            <div class="grid gap-4 sm:gap-6 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Moderator</p>
                                    <p class="mt-1 text-xs sm:text-sm text-gray-900 dark:text-white break-words">{{ $incidentReport->moderator->fullName }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Moderation date</p>
                                    <p class="mt-1 text-xs sm:text-sm text-gray-900 dark:text-white">{{ $incidentReport->moderation_date->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>

                            @if($incidentReport->action_taken)
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Action taken</p>
                                    <span class="mt-2 inline-flex text-xs sm:text-sm {{ in_array($incidentReport->action_taken, ['suspension', 'no_action']) ? 'badge-soft badge-soft-teal' : 'badge-soft badge-soft-orange' }}">
                                        {{ ucwords(str_replace('_', ' ', $incidentReport->action_taken)) }}
                                    </span>
                                </div>
                            @endif

                            @if($incidentReport->moderator_notes)
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Moderator notes</p>
                                    <p class="mt-2 text-xs sm:text-sm text-gray-800 dark:text-gray-100 whitespace-pre-wrap break-words">{{ $incidentReport->moderator_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden flex items-center justify-center p-4" style="display: none;">
        <div class="relative max-w-7xl max-h-full w-full h-full flex items-center justify-center">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:bg-white hover:bg-opacity-20 z-10 bg-black bg-opacity-60 rounded-full p-3 transition-all">
                <span class="sr-only">Close</span>
                <i class="fas fa-times text-lg"></i>
            </button>

            <button id="prevButton" onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:bg-white hover:bg-opacity-20 z-10 bg-black bg-opacity-60 rounded-full p-3 transition-all">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>

            <button id="nextButton" onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:bg-white hover:bg-opacity-20 z-10 bg-black bg-opacity-60 rounded-full p-3 transition-all">
                <i class="fas fa-chevron-right text-lg"></i>
            </button>

            <div class="flex items-center justify-center w-full h-full">
                <img id="modalImage" src="" alt="Incident evidence" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>

            <div id="imageCounter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-70 px-4 py-2 rounded-full text-sm font-medium">
                <span id="currentImageIndex">1</span> / <span id="totalImages">1</span>
            </div>

            <button id="downloadButton" onclick="downloadImage()" class="absolute bottom-4 right-4 text-white hover:bg-white hover:bg-opacity-20 z-10 bg-black bg-opacity-60 rounded-full p-3 transition-all">
                <i class="fas fa-download text-lg"></i>
            </button>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentImageIndex = 0;
        let imageSources = [];

        document.addEventListener('DOMContentLoaded', function() {
            const imageElements = document.querySelectorAll('img[data-photo-url]');
            imageSources = Array.from(imageElements).map(img => img.getAttribute('data-photo-url'));

            imageElements.forEach((img) => {
                img.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const photoUrl = this.getAttribute('data-photo-url');
                    openImageModal(photoUrl);
                });
            });
        });

        function openImageModal(imageSrc) {
            currentImageIndex = imageSources.indexOf(imageSrc);
            if (currentImageIndex === -1) currentImageIndex = 0;

            updateModalImage();
            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        function nextImage() {
            if (imageSources.length > 1) {
                currentImageIndex = (currentImageIndex + 1) % imageSources.length;
                updateModalImage();
            }
        }

        function previousImage() {
            if (imageSources.length > 1) {
                currentImageIndex = (currentImageIndex - 1 + imageSources.length) % imageSources.length;
                updateModalImage();
            }
        }

        function updateModalImage() {
            const modalImage = document.getElementById('modalImage');
            const currentIndexSpan = document.getElementById('currentImageIndex');
            const totalImagesSpan = document.getElementById('totalImages');
            const prevButton = document.getElementById('prevButton');
            const nextButton = document.getElementById('nextButton');

            if (imageSources.length > 0 && modalImage) {
                modalImage.src = imageSources[currentImageIndex];
                if (currentIndexSpan) currentIndexSpan.textContent = currentImageIndex + 1;
                if (totalImagesSpan) totalImagesSpan.textContent = imageSources.length;

                if (imageSources.length === 1) {
                    if (prevButton) prevButton.style.display = 'none';
                    if (nextButton) nextButton.style.display = 'none';
                } else {
                    if (prevButton) prevButton.style.display = 'block';
                    if (nextButton) nextButton.style.display = 'block';
                }
            }
        }

        function downloadImage() {
            if (imageSources.length > 0) {
                const link = document.createElement('a');
                link.href = imageSources[currentImageIndex];
                link.download = `incident-evidence-${currentImageIndex + 1}.jpg`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        document.addEventListener('click', function(e) {
            const modal = document.getElementById('imageModal');
            if (modal && e.target === modal) {
                closeImageModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('imageModal');
            if (modal && !modal.classList.contains('hidden')) {
                switch(e.key) {
                    case 'Escape':
                        closeImageModal();
                        break;
                    case 'ArrowLeft':
                        previousImage();
                        break;
                    case 'ArrowRight':
                        nextImage();
                        break;
                }
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.id === 'modalImage') {
                e.stopPropagation();
            }
        });
    </script>
    @endpush
</x-app-layout>
