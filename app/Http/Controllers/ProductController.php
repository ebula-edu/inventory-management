<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ProductController
 *
 * Manages product catalog listings, item registration, and validation.
 */
class ProductController extends Controller
{
    /**
     * Display the products catalog tab.
     */
    public function index(Request $request, InventoryController $inventory): View
    {
        return $inventory->renderView($request, 'products');
    }

    /**
     * Display the add product registration tab.
     */
    public function create(Request $request, InventoryController $inventory): View
    {
        return $inventory->renderView($request, 'add-product');
    }

    /**
     * Store a newly registered product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if (empty($validated['reorder_point'])) {
            $validated['reorder_point'] = (int) session('inventory_low_stock_threshold', 10);
        }

        $product = Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', "Product '{$product->name}' ({$product->sku}) registered successfully.");
    }
}
