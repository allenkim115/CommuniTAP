<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Incident Report Details') }}
        </h2>
    </x-slot>

    @php
        $statusClasses = [
            'pending' => 'badge-soft badge-soft-orange',
            'under_review' => 'badge-soft badge-soft-teal',
            'resolved' => 'badge-soft badge-soft-teal',
            'dismissed' => 'badge-soft badge-soft-slate',
        ];

        $actionTagClasses = [
            'warning' => 'bg-yellow-100 text-yellow-800',
            'suspension' => 'bg-red-100 text-red-800',
            'no_action' => 'bg-green-100 text-green-800',
            'dismissed' => 'bg-gray-100 text-gray-800',
        ];
    @endphp

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card-surface">
                <div class="p-6 lg:p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between mb-6">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Incident Report</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <h3 class="text-2xl font-semibold text-gray-900">#{{ $incidentReport->reportId }}</h3>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusClasses[$incidentReport->status] ?? 'badge-soft badge-soft-slate' }}">
                                    {{ ucwords(str_replace('_', ' ', $incidentReport->status)) }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-semibold bg-brand-teal/10 text-brand-teal-dark border border-brand-teal/30">
                                    <i class="fas fa-calendar-alt text-[11px]"></i>
                                    {{ $incidentReport->report_date->format('M d, Y \a\t g:i A') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Submitted {{ $incidentReport->report_date->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-brand-teal/10 text-brand-teal-dark text-xs font-semibold border border-brand-teal/20">
                                <i class="fas fa-flag"></i>
                                {{ ucwords(str_replace('_', ' ', $incidentReport->incident_type)) }}
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-brand-orange/10 text-brand-orange-dark text-xs font-semibold border border-brand-orange/20">
                                <i class="fas fa-user-shield"></i>
                                Reporter ID: {{ $incidentReport->reporter->userId }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Reporter -->
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 dark:bg-gray-700/40 p-5 shadow-sm">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Reporter</h4>
                            <div class="flex items-center gap-3">
                                <x-user-avatar
                                    :user="$incidentReport->reporter"
                                    size="h-12 w-12"
                                    text-size="text-base"
                                    class="bg-brand-teal/10 text-brand-teal-dark font-semibold"
                                />
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $incidentReport->reporter->fullName }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $incidentReport->reporter->email }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        User ID: {{ $incidentReport->reporter->userId }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reported User -->
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 dark:bg-gray-700/40 p-5 shadow-sm">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Reported User</h4>
                            <div class="flex items-center gap-3">
                                <x-user-avatar
                                    :user="$incidentReport->reportedUser"
                                    size="h-12 w-12"
                                    text-size="text-base"
                                    class="bg-brand-orange/20 text-brand-orange-dark font-semibold"
                                />
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $incidentReport->reportedUser->fullName }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $incidentReport->reportedUser->email }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        User ID: {{ $incidentReport->reportedUser->userId }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Incident Type -->
                        <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5 shadow-sm">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Incident Type</h4>
                            @php
                                $typeClass = in_array($incidentReport->incident_type, ['abuse', 'spam', 'harassment', 'inappropriate_content'])
                                    ? 'badge-soft badge-soft-teal'
                                    : 'badge-soft badge-soft-orange';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $typeClass }}">
                                {{ ucwords(str_replace('_', ' ', $incidentReport->incident_type)) }}
                            </span>
                        </div>

                        <!-- Related Task -->
                        <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5 shadow-sm">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Related Task</h4>
                            @if($incidentReport->task)
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('admin.tasks.show', $incidentReport->task) }}" class="text-brand-teal-dark hover:text-brand-teal">
                                            {{ $incidentReport->task->title }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Task ID: {{ $incidentReport->task->taskId }}
                                    </div>
                                </div>
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">No related task</span>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Description</h4>
                        <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5 shadow-sm">
                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $incidentReport->description }}</p>
                        </div>
                    </div>

                    <!-- Evidence -->
                    @if($incidentReport->evidence)
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Additional Evidence</h4>
                                <span class="text-xs font-medium text-gray-500">Click to enlarge</span>
                            </div>
                            <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5 shadow-sm space-y-4">
                                @php
                                    $evidenceLines = preg_split("/\r?\n/", (string) $incidentReport->evidence);
                                @endphp
                                @foreach($evidenceLines as $line)
                                    @php $trimmed = trim($line); @endphp
                                    @if($trimmed !== '')
                                        @if(strpos($trimmed, 'Image: ') === 0)
                                            @php $path = trim(substr($trimmed, 7)); @endphp
                                            <div class="relative group">
                                                @php $photoUrl = asset('storage/' . $path); @endphp
                                                <div class="relative overflow-hidden rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer" onclick="openImageModal('{{ $photoUrl }}')">
                                                    <img
                                                        src="{{ $photoUrl }}"
                                                        alt="Incident evidence image"
                                                        class="evidence-image w-full h-56 object-cover bg-white hover:scale-[1.02] transition-transform duration-200"
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
                                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $trimmed }}</p>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Moderation Details -->
                    @if($incidentReport->moderator)
                        <div class="mb-8">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Moderation Details</h4>
                            <div class="rounded-2xl border border-gray-100 bg-white dark:bg-gray-800/50 p-5 shadow-sm">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Moderator</h5>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $incidentReport->moderator->fullName }}</p>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Moderation Date</h5>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $incidentReport->moderation_date->format('F d, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($incidentReport->action_taken)
                                    <div class="mb-4">
                                        <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action Taken</h5>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $actionTagClasses[$incidentReport->action_taken] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucwords(str_replace('_', ' ', $incidentReport->action_taken)) }}
                                        </span>
                                    </div>
                                @endif

                                @if($incidentReport->moderator_notes)
                                    <div>
                                        <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Moderator Notes</h5>
                                        <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $incidentReport->moderator_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between pt-6 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('admin.incident-reports.index') }}" 
                           class="btn-muted">
                            Back to Reports
                        </a>
                        
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.incident-reports.edit', $incidentReport) }}" 
                               class="btn-brand">
                                {{ $incidentReport->isPending() ? 'Moderate Report' : 'Edit Report' }}
                            </a>
                        </div>
                    </div>
                </div>
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
</x-admin-layout>
