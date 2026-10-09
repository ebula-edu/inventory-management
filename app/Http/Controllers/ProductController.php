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
 * FILE OVERVIEW:
 * Controller para sa pamamahala ng mga produkto sa catalog:
 * - Paglista ng lahat ng produkto (index)
 * - Pagdagdag ng bagong produkto (create, store)
 * - Barcode/SKU lookup via JSON API (lookup)
 * - Pag-update ng detalye ng produkto (update)
 * - Pagtanggal ng produkto sa imbentaryo (destroy)
 *
 * KAILAN ITO TINATAWAG:
 * - Kapag binuksan ang tab na 'Products' o 'Add Product'.
 * - Kapag nag-submit ng Add/Edit Product form (`POST /products`, `PUT /products/{product}`).
 * - Kapag nag-scan ng barcode o nag-search gamit ang SKU lookup API (`GET /products/lookup/{sku}`).
 */
class ProductController extends Controller
{
    /**
     * Ipinapakita ang buong listahan ng mga produkto sa Products catalog view.
     *
     * @param Request $request
     * @param InventoryController $inventory
     * @return View
     */
    public function index(Request $request, InventoryController $inventory): View
    {
        return $inventory->renderView($request, 'products');
    }

    /**
     * Ipinapakita ang 'Add Product' form tab.
     *
     * @param Request $request
     * @param InventoryController $inventory
     * @return View
     */
    public function create(Request $request, InventoryController $inventory): View
    {
        return $inventory->renderView($request, 'add-product');
    }

    /**
     * I-save ang bagong produkto sa database gamit ang na-validate na datos mula sa StoreProductRequest.
     *
     * @param StoreProductRequest $request
     * @return RedirectResponse
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
            ->with('success', "Ang produktong '{$product->name}' ({$product->sku}) ay matagumpay na naitala.");
    }

    /**
     * Ibalik ang impormasyon ng produkto bilang JSON (ginagamit para sa REST API).
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    /**
     * Hanapin ang produkto gamit ang SKU o Barcode scanner.
     * Nagbabalik ng JSON payload para magamit sa client-side JavaScript.
     *
     * @param string $sku
     * @return JsonResponse
     */
    public function lookup(string $sku): JsonResponse
    {
        $product = Product::with('supplier')->where('sku', $sku)->first();

        if (! $product) {
            return response()->json([
                'found'   => false,
                'message' => "Walang nahanap na produkto na may SKU '{$sku}'",
            ], 404);
        }

        return response()->json([
            'found'   => true,
            'product' => $product,
        ]);
    }

    /**
     * I-update ang datos ng produkto (hal. presyo, quantity, category, supplier, o warehouse location).
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return RedirectResponse
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
            ->with('success', "Na-update ang produktong '{$product->name}' ({$product->sku}).");
    }

    /**
     * Tanggalin ang produkto sa database.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $sku  = $product->sku;
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', "Nabura na sa imbentaryo ang produktong '{$name}' ({$sku}).");
    }
}
