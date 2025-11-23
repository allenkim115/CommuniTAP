<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Review Task Submission') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Submitted by <span class="font-medium">{{ $submission->user->name }}</span>
                    @if($submission->submitted_at) 
                        on {{ is_string($submission->submitted_at) ? \Carbon\Carbon::parse($submission->submitted_at)->format('M j, Y \a\t g:i A') : $submission->submitted_at->format('M j, Y \a\t g:i A') }}
                    @endif
                </p>
            </div>
            <a href="{{ route('admin.task-submissions.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Submissions
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Toast Notifications -->
        <x-session-toast />
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Task Information -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-tasks mr-2 text-blue-600"></i>
                                Task Information
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Task Title</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $submission->task->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Task Type</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $submission->task->task_type)) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Points Awarded</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-semibold">
                                            <i class="fas fa-star mr-1 text-xs"></i>
                                            {{ $submission->task->points_awarded }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Due Date</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        @if($submission->task->due_date)
                                            {{ is_string($submission->task->due_date) ? \Carbon\Carbon::parse($submission->task->due_date)->format('M j, Y') : $submission->task->due_date->format('M j, Y') }}
                                        @else
                                            <span class="text-gray-400">No due date</span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Description</dt>
                                <dd class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $submission->task->description }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- User's Submission -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-file-upload mr-2 text-green-600"></i>
                                User's Submission
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($submission->completion_notes)
                                <div class="mb-6">
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Completion Notes</dt>
                                    <dd class="text-sm text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-200 leading-relaxed whitespace-pre-wrap">
                                        {{ $submission->completion_notes }}
                                    </dd>
                                </div>
                            @endif

                            @if($submission->photos && count($submission->photos) > 0)
                                <div>
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center">
                                        <i class="fas fa-images mr-2"></i>
                                        Proof Photos ({{ count($submission->photos) }})
                                    </dt>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($submission->photos as $index => $photo)
                                            <div class="relative group">
                                                @php
                                                    $photoUrl = Storage::url($photo);
                                                @endphp
                                                <div class="relative overflow-hidden rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer" onclick="openImageModal('{{ $photoUrl }}')">
                                                    <img src="{{ $photoUrl }}" 
                                                         alt="Task completion proof {{ $index + 1 }}" 
                                                         class="w-full h-32 object-cover hover:scale-105 transition-transform duration-200"
                                                         data-photo-url="{{ $photoUrl }}"
                                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0zMCAzMEg3MFY3MEgzMFYzMFoiIHN0cm9rZT0iIzlDQTNBRiIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPHBhdGggZD0iTTQwIDQwTDUwIDUwTDYwIDQwIiBzdHJva2U9IiM5Q0EzQUYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxjaXJjbGUgY3g9IjQ1IiBjeT0iNDUiIHI9IjMiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+'; console.log('Image failed to load:', '{{ $photoUrl }}');">
                                                    
                                                    <!-- Overlay with zoom icon -->
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-200 flex items-center justify-center">
                                                        <div class="bg-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                            <i class="fas fa-search-plus text-gray-800"></i>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Image number badge -->
                                                    <div class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">No photos submitted</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- User Information -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-user mr-2 text-blue-600"></i>
                                User Information
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="h-14 w-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                    <span class="text-lg font-bold text-white">
                                        {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-semibold text-gray-900 truncate">{{ $submission->user->name }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $submission->user->email }}</div>
                                </div>
                            </div>
                            <div class="space-y-3 pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Current Points</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $submission->user->points ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Points to Award</span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-bold bg-blue-50 text-blue-700">
                                        <i class="fas fa-star mr-1 text-xs"></i>
                                        {{ $submission->task->points_awarded }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Rejection Count</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $submission->rejection_count ?? 0 }}/3
                                    </span>
                                </div>
                                @if($submission->rejection_reason)
                                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-xs font-semibold text-red-800 mb-1 uppercase tracking-wide">Last Rejection Reason</p>
                                    <p class="text-sm text-red-700 leading-relaxed">{{ $submission->rejection_reason }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Review Actions -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-gavel mr-2 text-blue-600"></i>
                                Review Actions
                            </h3>
                        </div>
                        <div class="p-6">
                            @php
                                $isClosed = $submission->status === 'completed' || $submission->rejection_count >= 3;
                            @endphp

                            @if($isClosed)
                                <div class="p-4 rounded-lg border-2
                                    @if($submission->status === 'completed') bg-green-50 border-green-200
                                    @else bg-red-50 border-red-200
                                    @endif">
                                    @if($submission->status === 'completed')
                                        <div class="flex items-center text-green-700 mb-2">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            <span class="font-semibold">Submission Approved</span>
                                        </div>
                                        <p class="text-sm text-green-600">
                                            This submission has been approved and is closed. No further actions can be taken.
                                        </p>
                                    @elseif($submission->rejection_count >= 3)
                                        <div class="flex items-center text-red-700 mb-2">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            <span class="font-semibold">Submission Closed</span>
                                        </div>
                                        <p class="text-sm text-red-600">
                                            This submission has reached the maximum number of rejection attempts (3) and is closed. No further actions can be taken.
                                        </p>
                                    @endif
                                </div>
                            @else
                                <!-- Approve Form -->
                                <form action="{{ route('admin.task-submissions.approve', $submission) }}" method="POST" class="mb-6">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="admin_notes" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                                            Admin Notes <span class="text-gray-400 normal-case">(Optional)</span>
                                        </label>
                                        <textarea 
                                            id="admin_notes" 
                                            name="admin_notes" 
                                            rows="3" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                                            placeholder="Add notes about this approval..."></textarea>
                                    </div>
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors shadow-sm">
                                        <i class="fas fa-check mr-2"></i>
                                        Approve Submission
                                    </button>
                                </form>

                                <!-- Reject Form -->
                                <form action="{{ route('admin.task-submissions.reject', $submission) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rejection_reason" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                                            Rejection Reason <span class="text-red-500">*</span>
                                        </label>
                                        <textarea 
                                            id="rejection_reason" 
                                            name="rejection_reason" 
                                            rows="3" 
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                            placeholder="Explain why this submission is being rejected..."></textarea>
                                    </div>
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors shadow-sm">
                                        <i class="fas fa-times mr-2"></i>
                                        Reject Submission
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden flex items-center justify-center p-4" style="display: none;">
        <div class="relative max-w-7xl max-h-full w-full h-full flex items-center justify-center">
            <!-- Close Button -->
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:bg-white hover:bg-opacity-20 z-10 bg-black bg-opacity-60 rounded-full p-3 transition-all">
                <i class="fas fa-times text-lg"></i>
            </button>
            
            <!-- Navigation Buttons -->
            <button id="prevButton" onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:bg-white hover:bg-opacity-20 z-10 bg-black bg-opacity-60 rounded-full p-3 transition-all">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            
            <button id="nextButton" onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:bg-white hover:bg-opacity-20 z-10 bg-black bg-opacity-60 rounded-full p-3 transition-all">
                <i class="fas fa-chevron-right text-lg"></i>
            </button>
            
            <!-- Image Container -->
            <div class="flex items-center justify-center w-full h-full">
                <img id="modalImage" src="" alt="Task completion proof" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>
            
            <!-- Image Counter -->
            <div id="imageCounter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-70 px-4 py-2 rounded-full text-sm font-medium">
                <span id="currentImageIndex">1</span> / <span id="totalImages">1</span>
            </div>
            
            <!-- Download Button -->
            <button id="downloadButton" onclick="downloadImage()" class="absolute bottom-4 right-4 text-white hover:bg-white hover:bg-opacity-20 z-10 bg-black bg-opacity-60 rounded-full p-3 transition-all">
                <i class="fas fa-download text-lg"></i>
            </button>
        </div>
    </div>

    <script>
        let currentImageIndex = 0;
        let imageSources = [];

        // Initialize image sources from the page
        document.addEventListener('DOMContentLoaded', function() {
            // Get all images that have the data-photo-url attribute
            const imageElements = document.querySelectorAll('img[data-photo-url]');
            imageSources = Array.from(imageElements).map(img => img.getAttribute('data-photo-url'));
            
            // Add click event listeners to all photo images
            imageElements.forEach((img, index) => {
                img.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const photoUrl = this.getAttribute('data-photo-url');
                    openImageModal(photoUrl);
                });
                
                // Also add a simple onclick as backup
                img.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const photoUrl = this.getAttribute('data-photo-url');
                    openImageModal(photoUrl);
                };
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
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Restore scrolling
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
            const downloadButton = document.getElementById('downloadButton');

            if (imageSources.length > 0 && modalImage) {
                modalImage.src = imageSources[currentImageIndex];
                if (currentIndexSpan) currentIndexSpan.textContent = currentImageIndex + 1;
                if (totalImagesSpan) totalImagesSpan.textContent = imageSources.length;

                // Show/hide navigation buttons based on number of images
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
                link.download = `task-proof-${currentImageIndex + 1}.jpg`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }



        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('imageModal');
            if (modal && e.target === modal) {
                closeImageModal();
            }
        });

        // Keyboard navigation
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

        // Prevent modal from closing when clicking on the image
        document.addEventListener('click', function(e) {
            if (e.target.id === 'modalImage') {
                e.stopPropagation();
            }
        });
    </script>
</x-admin-layout>
