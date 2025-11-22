<div id="alert-modal" class="fixed inset-0 z-50 hidden" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="alert-title" aria-describedby="alert-message">
    <!-- Backdrop with blur effect -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" id="alert-backdrop" onclick="closeAlertModal()"></div>
    
    <!-- Modal Container -->
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div id="alert-modal-content" class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 pointer-events-auto border border-gray-100 overflow-hidden">
            <!-- Close Button -->
            <button 
                type="button"
                onclick="closeAlertModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 rounded-lg p-1 z-10"
                aria-label="Close">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="p-8">
                <!-- Icon Container -->
                <div class="flex justify-center mb-6">
                    <div id="alert-icon" class="flex items-center justify-center h-16 w-16 rounded-full shadow-lg transform transition-transform duration-300"></div>
                </div>
                
                <!-- Title -->
                <h3 id="alert-title" class="text-2xl font-bold text-gray-900 mb-3 text-center"></h3>
                
                <!-- Message -->
                <p id="alert-message" class="text-base text-gray-600 mb-8 text-center whitespace-pre-line leading-relaxed"></p>
                
                <!-- Action Button -->
                <div class="flex justify-center">
                    <button 
                        type="button"
                        onclick="closeAlertModal()"
                        id="alert-button"
                        class="px-8 py-3 rounded-xl text-base font-semibold transition-all duration-200 transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-lg hover:shadow-xl min-w-[120px]">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showAlertModal(message, title = 'Alert', type = 'info', buttonText = 'OK', callback = null) {
    const modal = document.getElementById('alert-modal');
    const titleEl = document.getElementById('alert-title');
    const messageEl = document.getElementById('alert-message');
    const buttonEl = document.getElementById('alert-button');
    const iconEl = document.getElementById('alert-icon');
    const backdrop = document.getElementById('alert-backdrop');
    const content = document.getElementById('alert-modal-content');
    
    titleEl.textContent = title;
    messageEl.textContent = message;
    buttonEl.textContent = buttonText;
    
    // Set icon and colors based on type
    const colors = {
        info: { 
            bg: 'blue', 
            bgLight: 'bg-blue-50',
            iconBg: 'bg-gradient-to-br from-blue-500 to-blue-600',
            iconColor: 'text-white',
            button: 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white focus:ring-blue-500',
            icon: '<svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' 
        },
        warning: { 
            bg: 'yellow', 
            bgLight: 'bg-yellow-50',
            iconBg: 'bg-gradient-to-br from-yellow-400 to-yellow-500',
            iconColor: 'text-white',
            button: 'bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white focus:ring-yellow-500',
            icon: '<svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>' 
        },
        error: { 
            bg: 'red', 
            bgLight: 'bg-red-50',
            iconBg: 'bg-gradient-to-br from-red-500 to-red-600',
            iconColor: 'text-white',
            button: 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white focus:ring-red-500',
            icon: '<svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' 
        },
        success: { 
            bg: 'green', 
            bgLight: 'bg-green-50',
            iconBg: 'bg-gradient-to-br from-green-500 to-green-600',
            iconColor: 'text-white',
            button: 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white focus:ring-green-500',
            icon: '<svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' 
        }
    };
    
    const color = colors[type] || colors.info;
    iconEl.className = `flex items-center justify-center h-16 w-16 rounded-full ${color.iconBg} shadow-lg transform transition-transform duration-300`;
    iconEl.innerHTML = color.icon;
    buttonEl.className = `px-8 py-3 rounded-xl text-base font-semibold transition-all duration-200 transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 ${color.button} shadow-lg hover:shadow-xl min-w-[120px]`;
    
    window.alertCallback = callback;
    
    // Show modal with animation
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Trigger animations
    requestAnimationFrame(() => {
        backdrop.style.opacity = '1';
        content.style.opacity = '1';
        content.style.transform = 'scale(1) translateY(0)';
    });
    
    // Focus management
    buttonEl.focus();
    
    // ESC key handler
    const escHandler = (e) => {
        if (e.key === 'Escape') {
            closeAlertModal();
            document.removeEventListener('keydown', escHandler);
        }
    };
    document.addEventListener('keydown', escHandler);
    window.alertEscHandler = escHandler;
}

function closeAlertModal() {
    const modal = document.getElementById('alert-modal');
    const backdrop = document.getElementById('alert-backdrop');
    const content = document.getElementById('alert-modal-content');
    
    // Animate out
    backdrop.style.opacity = '0';
    content.style.opacity = '0';
    content.style.transform = 'scale(0.95) translateY(-10px)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        if (window.alertCallback) {
            window.alertCallback();
            window.alertCallback = null;
        }
        
        if (window.alertEscHandler) {
            document.removeEventListener('keydown', window.alertEscHandler);
            window.alertEscHandler = null;
        }
    }, 300);
}

// Override native alert
window.alert = function(message) {
    showAlertModal(message, 'Alert', 'info');
};
</script>

<style>
#alert-modal-content {
    opacity: 0;
    transform: scale(0.95) translateY(-10px);
}

#alert-backdrop {
    opacity: 0;
}
</style>
