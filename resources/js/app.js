import './bootstrap';

import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

window.Alpine = Alpine;
window.ApexCharts = ApexCharts;

Alpine.start();

// Modal utility functions
window.showAlert = function(message, title = 'Alert', type = 'info', callback = null) {
    window.alertCallback = callback;
    const event = new CustomEvent('show-alert', {
        detail: { message, title, type }
    });
    window.dispatchEvent(event);
};

window.showConfirm = function(message, title = 'Confirm Action', callback = null, confirmText = 'Confirm', cancelText = 'Cancel', confirmColor = 'blue') {
    return new Promise((resolve) => {
        window.confirmCallback = () => {
            resolve(true);
            window.confirmCallback = null;
        };
        window.confirmCancelCallback = () => {
            resolve(false);
            window.confirmCancelCallback = null;
        };
        const event = new CustomEvent('show-confirm', {
            detail: { message, title, confirmText, cancelText, confirmColor }
        });
        window.dispatchEvent(event);
    });
};
