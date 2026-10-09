/**
 * @file events.js
 * @description Global event listeners para sa system:
 * - Outside-click at Escape key dismissal para sa mobile sidebar.
 * - Auto-dismiss at manual close button para sa flash notifications (alerts).
 */

import { closeSidebar } from './sidebar.js';

/**
 * Isara at tanggalin ang isang alert box nang may smooth fade-out animation.
 *
 * @param {HTMLElement} btn - Ang close button na pinindot sa loob ng alert.
 * @returns {void}
 */
export function closeAlert(btn) {
    const alertEl = btn.closest('.alert');
    if (!alertEl) return;

    alertEl.classList.add('alert-fade-out');
    setTimeout(() => {
        alertEl.remove();
    }, 300);
}

// Expose sa window para matawag ng inline onclick="closeAlert(this)"
window.closeAlert = closeAlert;

/**
 * Auto-hide ng success notifications matapos ang 4 segundo.
 * Para hindi nakaharang o makalat sa screen ng user.
 *
 * @returns {void}
 */
export function initAlertAutoDismiss() {
    const autoDismissAlerts = document.querySelectorAll('.alert[data-auto-dismiss="true"]');

    autoDismissAlerts.forEach((alertEl) => {
        setTimeout(() => {
            if (document.body.contains(alertEl)) {
                alertEl.classList.add('alert-fade-out');
                setTimeout(() => {
                    alertEl.remove();
                }, 300);
            }
        }, 4000);
    });
}

/**
 * Initializes global keyboard at outside-click listeners para sa sidebar at alert lifecycle.
 *
 * @returns {void}
 */
export function initGlobalListeners() {
    // Isara ang mobile sidebar kapag nag-click sa labas nito (i-ignore ang hamburger button at mismong sidebar)
    document.addEventListener('click', (event) => {
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburgerBtn');
        if (!sidebar || !sidebar.classList.contains('active')) {
            return;
        }
        const target = /** @type {Node} */ (event.target);
        if (hamburger && (hamburger === target || hamburger.contains(target))) {
            return;
        }
        if (!sidebar.contains(target)) {
            closeSidebar();
        }
    });

    // Escape key listener: isara ang sidebar kung bukas
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    // Simulan ang auto-dismiss para sa mga toast o alert cards
    initAlertAutoDismiss();
}
