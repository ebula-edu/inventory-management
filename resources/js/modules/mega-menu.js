/**
 * @file mega-menu.js
 * @description Controls the desktop inventory dropdown mega-menu and dismissal behaviors.
 */

/**
 * Toggles the visibility of the desktop mega-menu dropdown.
 *
 * @param {Event} [event] - Optional triggering event to stop propagation.
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
 * Closes the desktop mega-menu dropdown if currently opened.
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
