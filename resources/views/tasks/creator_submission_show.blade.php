@php
    // Reuse the admin submission detail UI but within the user layout and creator routes
@endphp
<x-app-layout>
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
            <a href="{{ route('tasks.creator.submissions') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md">
                Back to Submissions
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <x-session-toast />
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
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

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">User's Submission</h3>
                            @if($submission->completion_notes)
                                <div class="mb-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Completion Notes</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-4 rounded-md">{{ $submission->completion_notes }}</dd>
                                </div>
                            @endif
                            @if($submission->photos && count($submission->photos) > 0)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Proof Photos ({{ count($submission->photos) }})</dt>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($submission->photos as $index => $photo)
                                            @php $photoUrl = Storage::url($photo); @endphp
                                            <div class="relative group">
                                                <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200" onclick="openImageModal('{{ $photoUrl }}')">
                                                    <img src="{{ $photoUrl }}" 
                                                         alt="Task completion proof {{ $index + 1 }}" 
                                                         class="w-full h-32 object-cover cursor-pointer hover:scale-105 transition-transform duration-200"
                                                         data-photo-url="{{ $photoUrl }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">No photos submitted</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
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
                                <form action="{{ route('tasks.creator.approve', $submission) }}" method="POST" class="mb-4" id="approve-submission-form">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (optional)</label>
                                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white"></textarea>
                                    </div>
                                    <button type="button" onclick="showConfirmModal('Approve this submission?', 'Approve Submission', 'Approve', 'Cancel', 'green').then(confirmed => { if(confirmed) document.getElementById('approve-submission-form').submit(); });" class="w-full px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white">Approve Submission</button>
                                </form>
                                <form action="{{ route('tasks.creator.reject', $submission) }}" method="POST" id="reject-submission-form">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason *</label>
                                        <textarea name="rejection_reason" required rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white"></textarea>
                                    </div>
                                    <button type="button" onclick="showConfirmModal('Reject this submission?', 'Reject Submission', 'Reject', 'Cancel', 'red').then(confirmed => { if(confirmed) document.getElementById('reject-submission-form').submit(); });" class="w-full px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white">Reject Submission</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Image Modal (same behavior as admin) -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4" style="display: none;">
    <div class="relative max-w-7xl max-h-full w-full h-full flex items-center justify-center">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <button id="prevButton" onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-3 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button id="nextButton" onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-3 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
        <div class="flex items-center justify-center w-full h-full">
            <img id="modalImage" src="" alt="Task completion proof" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        </div>
        <div id="imageCounter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm">
            <span id="currentImageIndex">1</span> / <span id="totalImages">1</span>
        </div>
        <button id="downloadButton" onclick="downloadImage()" class="absolute bottom-4 right-4 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </button>
    </div>
    <script>
        let currentImageIndex = 0;
        let imageSources = [];
        document.addEventListener('DOMContentLoaded', function() {
            const imageElements = document.querySelectorAll('img[data-photo-url]');
            imageSources = Array.from(imageElements).map(img => img.getAttribute('data-photo-url'));
        });
        function openImageModal(imageSrc) {
            currentImageIndex = imageSources.indexOf(imageSrc);
            if (currentImageIndex === -1) currentImageIndex = 0;
            updateModalImage();
            const modal = document.getElementById('imageModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        function nextImage() { if (imageSources.length > 1) { currentImageIndex = (currentImageIndex + 1) % imageSources.length; updateModalImage(); } }
        function previousImage() { if (imageSources.length > 1) { currentImageIndex = (currentImageIndex - 1 + imageSources.length) % imageSources.length; updateModalImage(); } }
        function updateModalImage() {
            const modalImage = document.getElementById('modalImage');
            const currentIndexSpan = document.getElementById('currentImageIndex');
            const totalImagesSpan = document.getElementById('totalImages');
            const prevButton = document.getElementById('prevButton');
            const nextButton = document.getElementById('nextButton');
            if (imageSources.length > 0) {
                modalImage.src = imageSources[currentImageIndex];
                currentIndexSpan.textContent = currentImageIndex + 1;
                totalImagesSpan.textContent = imageSources.length;
                if (imageSources.length === 1) { prevButton.style.display = 'none'; nextButton.style.display = 'none'; }
                else { prevButton.style.display = 'block'; nextButton.style.display = 'block'; }
            }
        }
        function downloadImage() { if (imageSources.length > 0) { const link = document.createElement('a'); link.href = imageSources[currentImageIndex]; link.download = `task-proof-${currentImageIndex + 1}.jpg`; document.body.appendChild(link); link.click(); document.body.removeChild(link); } }
        document.addEventListener('click', function(e) { const modal = document.getElementById('imageModal'); if (e.target === modal) { closeImageModal(); } });
        document.addEventListener('keydown', function(e) { const modal = document.getElementById('imageModal'); if (modal && !modal.classList.contains('hidden')) { if (e.key === 'Escape') closeImageModal(); if (e.key === 'ArrowLeft') previousImage(); if (e.key === 'ArrowRight') nextImage(); } });
    </script>
</div>


