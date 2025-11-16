<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Review Task Submission') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Submitted by {{ $submission->user->name }} on {{ is_string($submission->submitted_at) ? \Carbon\Carbon::parse($submission->submitted_at)->format('M j, Y \a\t g:i A') : $submission->submitted_at->format('M j, Y \a\t g:i A') }}
                </p>
            </div>
            <a href="{{ route('admin.task-submissions.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Submissions
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <!-- Toast Notifications -->
        <x-session-toast />
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Task Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Task Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Task Title</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $submission->task->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Task Type</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ ucfirst(str_replace('_', ' ', $submission->task->task_type)) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Points Awarded</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $submission->task->points_awarded }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white mt-1">
                                        @if($submission->task->due_date)
                                            {{ is_string($submission->task->due_date) ? \Carbon\Carbon::parse($submission->task->due_date)->format('M j, Y') : $submission->task->due_date->format('M j, Y') }}
                                        @else
                                            No due date
                                        @endif
                                    </dd>
                                </div>
                            </div>
                            <div class="mt-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $submission->task->description }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- User's Submission -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">User's Submission</h3>
                            
                            @if($submission->completion_notes)
                                <div class="mb-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Completion Notes</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                                        {{ $submission->completion_notes }}
                                    </dd>
                                </div>
                            @endif

                            @if($submission->photos && count($submission->photos) > 0)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Proof Photos ({{ count($submission->photos) }})</dt>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($submission->photos as $index => $photo)
                                            <div class="relative group">
                                                @php
                                                    $photoUrl = Storage::url($photo);
                                                @endphp
                                                <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200" onclick="openImageModal('{{ $photoUrl }}')">
                                                    <img src="{{ $photoUrl }}" 
                                                         alt="Task completion proof {{ $index + 1 }}" 
                                                         class="w-full h-32 object-cover cursor-pointer hover:scale-105 transition-transform duration-200"
                                                         data-photo-url="{{ $photoUrl }}"
                                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0zMCAzMEg3MFY3MEgzMFYzMFoiIHN0cm9rZT0iIzlDQTNBRiIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPHBhdGggZD0iTTQwIDQwTDUwIDUwTDYwIDQwIiBzdHJva2U9IiM5Q0EzQUYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxjaXJjbGUgY3g9IjQ1IiBjeT0iNDUiIHI9IjMiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+'; console.log('Image failed to load:', '{{ $photoUrl }}');">
                                                    
                                                    <!-- Overlay with zoom icon -->
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                                                        <div class="bg-white bg-opacity-90 rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Image number badge -->
                                                    <div class="absolute top-2 left-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded-full">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    
                                                    <!-- Click to enlarge text -->
                                                    <div class="absolute bottom-2 left-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded text-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                        Click to enlarge
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p>No photos submitted</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- User Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">User Information</h3>
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="h-12 w-12 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <span class="text-lg font-medium text-gray-700 dark:text-gray-300">
                                        {{ substr($submission->user->name, 0, 2) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $submission->user->email }}</div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Current Points:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->user->points ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Points to Award:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->task->points_awarded }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Rejection Count:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->rejection_count ?? 0 }}/3</span>
                                </div>
                                @if($submission->rejection_reason)
                                <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                    <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-1">Last Rejection Reason:</p>
                                    <p class="text-sm text-red-700 dark:text-red-300">{{ $submission->rejection_reason }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Review Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Review Actions</h3>
                            
                            @php
                                $isClosed = $submission->status === 'completed' || $submission->rejection_count >= 3;
                            @endphp

                            @if($isClosed)
                                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-300 dark:border-gray-600">
                                    @if($submission->status === 'completed')
                                        <div class="flex items-center text-green-600 dark:text-green-400 mb-2">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-medium">Submission Approved</span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            This submission has been approved and is closed. No further actions can be taken.
                                        </p>
                                    @elseif($submission->rejection_count >= 3)
                                        <div class="flex items-center text-red-600 dark:text-red-400 mb-2">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span class="font-medium">Submission Closed</span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            This submission has reached the maximum number of rejection attempts (3) and is closed. No further actions can be taken.
                                        </p>
                                    @endif
                                </div>
                            @else
                                <!-- Approve Form -->
                                <form action="{{ route('admin.task-submissions.approve', $submission) }}" method="POST" class="mb-4">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Admin Notes (Optional)
                                        </label>
                                        <textarea 
                                            id="admin_notes" 
                                            name="admin_notes" 
                                            rows="3" 
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white"
                                            placeholder="Add notes about this approval..."></textarea>
                                    </div>
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Approve Submission
                                    </button>
                                </form>

                                <!-- Reject Form -->
                                <form action="{{ route('admin.task-submissions.reject', $submission) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Rejection Reason <span class="text-red-500">*</span>
                                        </label>
                                        <textarea 
                                            id="rejection_reason" 
                                            name="rejection_reason" 
                                            rows="3" 
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                            placeholder="Explain why this submission is being rejected..."></textarea>
                                    </div>
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
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
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4" style="display: none;">
        <div class="relative max-w-7xl max-h-full w-full h-full flex items-center justify-center">
            <!-- Close Button -->
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Navigation Buttons -->
            <button id="prevButton" onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-3 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <button id="nextButton" onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-3 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <!-- Image Container -->
            <div class="flex items-center justify-center w-full h-full">
                <img id="modalImage" src="" alt="Task completion proof" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>
            
            <!-- Image Counter -->
            <div id="imageCounter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm">
                <span id="currentImageIndex">1</span> / <span id="totalImages">1</span>
            </div>
            
            <!-- Download Button -->
            <button id="downloadButton" onclick="downloadImage()" class="absolute bottom-4 right-4 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
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
