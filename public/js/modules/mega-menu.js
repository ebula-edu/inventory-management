/**
 * @file mega-menu.js
 * @description Controls the desktop mega-menu dropdown for inventory sections.
 */

/**
 * Toggles desktop mega-menu dropdown visibility.
 *
 * @param {Event} [event] - Optional click event to prevent bubbling.
 * @returns {void}
 */
export function toggleMegaMenu(event) {
    if (event) {
        event.stopPropagation();
    }

    const mega = document.getElementById('megaMenu');
    const parentItem = document.getElementById('desktopInventoryItem');

    if (mega) {
        const isOpen = mega.classList.toggle('show');
        if (parentItem) {
            parentItem.classList.toggle('open', isOpen);
        }
    }
}

/**
 * Closes desktop mega-menu dropdown.
 *
 * @returns {void}
 */
export function closeMegaMenu() {
    const mega = document.getElementById('megaMenu');
    const parentItem = document.getElementById('desktopInventoryItem');

    if (mega) {
        mega.classList.remove('show');
    }
    if (parentItem) {
        parentItem.classList.remove('open');
    }
}
