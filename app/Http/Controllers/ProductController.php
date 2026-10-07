<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ProductController
 *
 * Manages product catalog listings, item registration, updates, and deletion.
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

    /**
     * Display the specified product as JSON.
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    /**
     * Look up a product by its SKU or barcode code.
     */
    public function lookup(string $sku): JsonResponse
    {
        $product = Product::where('sku', $sku)->first();

        if (! $product) {
            return response()->json([
                'found' => false,
                'message' => "No product found with SKU '{$sku}'",
            ], 404);
        }

        return response()->json([
            'found' => true,
            'product' => $product,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();
        if (empty($validated['reorder_point'])) {
            $validated['reorder_point'] = (int) session('inventory_low_stock_threshold', 10);
        }

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', "Product '{$product->name}' ({$product->sku}) updated successfully.");
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $sku = $product->sku;
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', "Product '{$name}' ({$sku}) has been deleted from inventory.");
    }
}
