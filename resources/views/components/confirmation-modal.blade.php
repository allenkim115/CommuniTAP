<div id="confirm-modal" class="fixed inset-0 z-50 hidden" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-message">
    <!-- Backdrop with blur effect -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" id="confirm-backdrop" onclick="closeConfirmModal(false)"></div>
    
    <!-- Modal Container -->
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div id="confirm-modal-content" class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 pointer-events-auto border border-gray-100 overflow-hidden">
            <!-- Close Button -->
            <button 
                type="button"
                onclick="closeConfirmModal(false)"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 rounded-lg p-1 z-10"
                aria-label="Close">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="p-8">
                <!-- Icon Container -->
                <div class="flex justify-center mb-6">
                    <div id="confirm-icon" class="flex items-center justify-center h-16 w-16 rounded-full shadow-lg transform transition-transform duration-300" style="background-color: #2B9D8D;">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                
                <!-- Title -->
                <h3 id="confirm-title" class="text-2xl font-bold text-gray-900 mb-3 text-center">Confirm Action</h3>
                
                <!-- Message -->
                <p id="confirm-message" class="text-base text-gray-600 mb-8 text-center whitespace-pre-line leading-relaxed"></p>
                
                <!-- Action Buttons -->
                <div class="flex justify-center gap-3">
                    <button 
                        type="button"
                        onclick="closeConfirmModal(false)"
                        id="confirm-cancel"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-base font-semibold transition-all duration-200 transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 shadow-md hover:shadow-lg min-w-[100px]">
                        Cancel
                    </button>
                    <button 
                        type="button"
                        onclick="closeConfirmModal(true)"
                        id="confirm-ok"
                        class="px-6 py-3 text-white rounded-xl text-base font-semibold transition-all duration-200 transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-lg hover:shadow-xl min-w-[100px]"
                        style="background-color: #F3A261;"
                        onmouseover="this.style.backgroundColor='#E8944F'"
                        onmouseout="this.style.backgroundColor='#F3A261'">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const colorMap = {
    blue: {
        iconBg: '#2B9D8D',
        buttonBg: '#2B9D8D',
        buttonHover: '#248A7C'
    },
    red: {
        iconBg: '#2B9D8D',
        buttonBg: '#2B9D8D',
        buttonHover: '#248A7C'
    },
    green: {
        iconBg: '#2B9D8D',
        buttonBg: '#2B9D8D',
        buttonHover: '#248A7C'
    },
    orange: {
        iconBg: '#F3A261',
        buttonBg: '#F3A261',
        buttonHover: '#E8944F'
    },
    yellow: {
        iconBg: '#FED2B3',
        buttonBg: '#FED2B3',
        buttonHover: '#E8C19F'
    },
    purple: {
        iconBg: '#2B9D8D',
        buttonBg: '#2B9D8D',
        buttonHover: '#248A7C'
    }
};

function showConfirmModal(message, title = 'Confirm Action', confirmText = 'Confirm', cancelText = 'Cancel', confirmColor = 'blue', formId = null) {
    return new Promise((resolve) => {
        const modal = document.getElementById('confirm-modal');
        const titleEl = document.getElementById('confirm-title');
        const messageEl = document.getElementById('confirm-message');
        const okButton = document.getElementById('confirm-ok');
        const cancelButton = document.getElementById('confirm-cancel');
        const iconEl = document.getElementById('confirm-icon');
        const backdrop = document.getElementById('confirm-backdrop');
        const content = document.getElementById('confirm-modal-content');
        
        titleEl.textContent = title;
        messageEl.textContent = message;
        okButton.textContent = confirmText;
        cancelButton.textContent = cancelText;
        
        // Set colors
        const color = colorMap[confirmColor] || colorMap.blue;
        iconEl.style.backgroundColor = color.iconBg;
        iconEl.querySelector('svg').className = 'h-8 w-8 text-white';
        okButton.style.backgroundColor = color.buttonBg;
        okButton.onmouseover = function() { this.style.backgroundColor = color.buttonHover; };
        okButton.onmouseout = function() { this.style.backgroundColor = color.buttonBg; };
        
        window.confirmResolve = resolve;
        window.confirmFormId = formId;
        
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
        
        // Focus management - focus on cancel button first (safer default)
        cancelButton.focus();
        
        // ESC key handler
        const escHandler = (e) => {
            if (e.key === 'Escape') {
                closeConfirmModal(false);
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);
        window.confirmEscHandler = escHandler;
    });
}

function closeConfirmModal(confirmed) {
    const modal = document.getElementById('confirm-modal');
    const backdrop = document.getElementById('confirm-backdrop');
    const content = document.getElementById('confirm-modal-content');
    
    // Animate out
    backdrop.style.opacity = '0';
    content.style.opacity = '0';
    content.style.transform = 'scale(0.95) translateY(-10px)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        if (window.confirmResolve) {
            if (confirmed && window.confirmFormId) {
                const form = document.getElementById(window.confirmFormId);
                if (form) {
                    form.submit();
                }
            }
            window.confirmResolve(confirmed);
            window.confirmResolve = null;
            window.confirmFormId = null;
        }
        
        if (window.confirmEscHandler) {
            document.removeEventListener('keydown', window.confirmEscHandler);
            window.confirmEscHandler = null;
        }
    }, 300);
}

// Override native confirm
window.confirm = function(message) {
    return showConfirmModal(message, 'Confirm Action');
};
</script>

<style>
#confirm-modal-content {
    opacity: 0;
    transform: scale(0.95) translateY(-10px);
}

#confirm-backdrop {
    opacity: 0;
}
</style>
