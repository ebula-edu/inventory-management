/**
 * @file product-manager.js
 * @description Provides interactive client-side product catalog filtering, edit modal handling, and SKU barcode lookups.
 */

/**
 * Filters the product table rows based on search text and category dropdown value.
 *
 * @returns {void}
 */
export function filterProductsTable() {
    const searchInput = /** @type {HTMLInputElement|null} */ (document.getElementById('productSearchInput'));
    const categorySelect = /** @type {HTMLSelectElement|null} */ (document.getElementById('productCategoryFilter'));
    const rows = document.querySelectorAll('#productsTable tbody tr.product-row');

    const query = (searchInput?.value || '').toLowerCase().trim();
    const selectedCategory = (categorySelect?.value || 'ALL').toUpperCase();

    let visibleCount = 0;

    rows.forEach((row) => {
        const name = (row.getAttribute('data-name') || '').toLowerCase();
        const sku = (row.getAttribute('data-sku') || '').toLowerCase();
        const category = (row.getAttribute('data-category') || '').toUpperCase();

        const matchesQuery = !query || name.includes(query) || sku.includes(query);
        const matchesCategory = selectedCategory === 'ALL' || category === selectedCategory;

        if (matchesQuery && matchesCategory) {
            /** @type {HTMLElement} */ (row).style.display = '';
            visibleCount++;
        } else {
            /** @type {HTMLElement} */ (row).style.display = 'none';
        }
    });

    const emptyRow = document.getElementById('productsEmptyFilterRow');
    if (emptyRow) {
        emptyRow.style.display = visibleCount === 0 ? '' : 'none';
    }
}

/**
 * Opens and populates the Edit Product modal dialog.
 *
 * @param {Object} product - Product attributes to edit.
 * @param {number|string} product.id
 * @param {string} product.name
 * @param {string} product.sku
 * @param {string} product.category
 * @param {string} [product.location]
 * @param {number|string} product.quantity
 * @param {number|string} product.reorder_point
 * @param {number|string} product.price
 * @returns {void}
 */
export function openEditProductModal(product) {
    const modal = document.getElementById('editProductModal');
    const form = /** @type {HTMLFormElement|null} */ (document.getElementById('editProductForm'));

    if (!modal || !form) return;

    form.action = `/products/${product.id}`;

    const setVal = (id, val) => {
        const input = /** @type {HTMLInputElement|HTMLSelectElement|null} */ (document.getElementById(id));
        if (input) input.value = val ?? '';
    };

    setVal('editProdName', product.name);
    setVal('editProdSku', product.sku);
    setVal('editProdCategory', product.category);
    setVal('editProdLocation', product.location || '');
    setVal('editProdQty', product.quantity);
    setVal('editProdReorderPoint', product.reorder_point);
    setVal('editProdPrice', product.price);

    modal.classList.add('show');
}

/**
 * Closes the Edit Product modal dialog.
 *
 * @returns {void}
 */
export function closeEditProductModal() {
    const modal = document.getElementById('editProductModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

/**
 * Initializes real-time search and barcode quick-lookup event listeners.
 *
 * @returns {void}
 */
export function initProductManager() {
    const searchInput = document.getElementById('productSearchInput');
    const categorySelect = document.getElementById('productCategoryFilter');

    if (searchInput) {
        searchInput.addEventListener('input', filterProductsTable);
    }
    if (categorySelect) {
        categorySelect.addEventListener('change', filterProductsTable);
    }

    // Modal background click dismissal
    const modal = document.getElementById('editProductModal');
    if (modal) {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeEditProductModal();
            }
        });
    }
}
