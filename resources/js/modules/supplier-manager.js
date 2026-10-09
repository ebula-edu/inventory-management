/**
 * @file supplier-manager.js
 * @description Provides interactive modal management and helpers for adding and editing suppliers.
 */

/**
 * Opens the Add Supplier modal dialog.
 *
 * @returns {void}
 */
export function openAddSupplierModal() {
    const modal = document.getElementById('addSupplierModal');
    if (modal) {
        modal.classList.add('show');
        const firstInput = /** @type {HTMLInputElement|null} */ (document.getElementById('addSupplierName'));
        firstInput?.focus();
    }
}

/**
 * Closes the Add Supplier modal dialog.
 *
 * @returns {void}
 */
export function closeAddSupplierModal() {
    const modal = document.getElementById('addSupplierModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

/**
 * Opens and populates the Edit Supplier modal dialog.
 *
 * @param {Object} supplier - Supplier attributes to edit.
 * @param {number|string} supplier.id
 * @param {string} supplier.name
 * @param {string} supplier.email
 * @param {string} supplier.phone
 * @param {string} supplier.supplied_items
 * @returns {void}
 */
export function openEditSupplierModal(supplier) {
    const modal = document.getElementById('editSupplierModal');
    const form  = /** @type {HTMLFormElement|null} */ (document.getElementById('editSupplierForm'));

    if (!modal || !form) return;

    form.action = `/suppliers/${supplier.id}`;

    const setVal = (id, val) => {
        const input = /** @type {HTMLInputElement|HTMLTextAreaElement|null} */ (document.getElementById(id));
        if (input) input.value = val ?? '';
    };

    setVal('editSupplierName', supplier.name);
    setVal('editSupplierEmail', supplier.email);
    setVal('editSupplierPhone', supplier.phone);
    setVal('editSupplierItems', supplier.supplied_items);

    modal.classList.add('show');
    document.getElementById('editSupplierName')?.focus();
}

/**
 * Closes the Edit Supplier modal dialog.
 *
 * @returns {void}
 */
export function closeEditSupplierModal() {
    const modal = document.getElementById('editSupplierModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

/**
 * Initializes event listeners for supplier modals.
 *
 * @returns {void}
 */
export function initSupplierManager() {
    // Backdrop click dismissal for supplier modals
    ['addSupplierModal', 'editSupplierModal'].forEach((modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.classList.remove('show');
                }
            });
        }
    });

    // Escape key dismissal
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAddSupplierModal();
            closeEditSupplierModal();
        }
    });
}
