@props(['type' => 'success', 'message' => '', 'duration' => 5000])

<div id="toast-notification" 
     class="fixed top-4 right-4 z-50 transform transition-all duration-300 ease-in-out translate-x-full opacity-0"
     x-data="{ 
         show: false, 
         type: '{{ $type }}', 
         message: '{{ $message }}',
         duration: {{ $duration }}
     }"
     x-init="
         if (message) {
             show = true;
             $nextTick(() => {
                 $el.classList.remove('translate-x-full', 'opacity-0');
                 $el.classList.add('translate-x-0', 'opacity-100');
             });
             setTimeout(() => {
                 $el.classList.remove('translate-x-0', 'opacity-100');
                 $el.classList.add('translate-x-full', 'opacity-0');
                 setTimeout(() => show = false, 300);
             }, duration);
         }
     "
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-x-full"
     x-transition:enter-end="opacity-100 transform translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform translate-x-0"
     x-transition:leave-end="opacity-0 transform translate-x-full">
    
    <div class="flex items-center p-4 mb-4 text-white rounded-lg shadow-lg max-w-sm w-full
        @if($type === 'success') bg-green-500 @endif
        @if($type === 'error') bg-red-500 @endif
        @if($type === 'warning') bg-yellow-500 @endif
        @if($type === 'info') bg-blue-500 @endif">
        
        <!-- Icon -->
        <div class="flex-shrink-0">
            @if($type === 'success')
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            @elseif($type === 'error')
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            @elseif($type === 'warning')
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            @else
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </div>
        
        <!-- Message -->
        <div class="ml-3 text-sm font-medium">
            {{ $message }}
        </div>
        
        <!-- Close Button -->
        <div class="ml-auto pl-3">
            <button @click="
                $el.closest('#toast-notification').classList.remove('translate-x-0', 'opacity-100');
                $el.closest('#toast-notification').classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => show = false, 300);
            " class="inline-flex text-white hover:text-gray-200 focus:outline-none focus:text-gray-200 transition ease-in-out duration-150">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
