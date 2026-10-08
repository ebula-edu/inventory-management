/**
 * @file product-manager.js
 * @description Nagbibigay ng interactive search filter, barcode scanning,
 * at modal lifecycle para sa Product Catalog.
 *
 * KAILAN ITO GINAGAMIT:
 * - Sa tuwing nagta-type sa product search bar o nagpapalit ng category filter.
 * - Kapag pinipindot ang 'Edit' button sa Products table para mag-pop up ang modal dialog.
 */

/**
 * Filter ng table rows base sa search keyword (Pangalan o SKU) at category.
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
 * Buksan at punan ang Edit Product modal dialog gamit ang kasalukuyang datos ng produkto.
 *
 * @param {Object} product - Attribute details ng produkto
 * @param {number|string} product.id
 * @param {string} product.name
 * @param {string} product.sku
 * @param {string} product.category
 * @param {number|string|null} [product.supplier_id]
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
    setVal('editProdSupplier', product.supplier_id ?? '');
    setVal('editProdLocation', product.location || '');
    setVal('editProdQty', product.quantity);
    setVal('editProdReorderPoint', product.reorder_point);
    setVal('editProdPrice', product.price);

    modal.classList.add('show');
}

/**
 * Isara ang Edit Product modal dialog.
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
 * Simulan ang event listeners para sa search, filter, at modal backdrop clicks.
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

    // Modal background click dismissal: kapag nag-click sa labas ng card, isara
    const modal = document.getElementById('editProductModal');
    if (modal) {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeEditProductModal();
            }
        });
    }
}
