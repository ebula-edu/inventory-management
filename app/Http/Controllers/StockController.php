<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordStockInRequest;
use App\Http\Requests\RecordStockOutRequest;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Class StockController
 *
 * Handles stock audits, stock-in receiving, stock-out dispatches, and reordering.
 */
class StockController extends Controller
{
    /**
     * Display the stock management tab.
     */
    public function index(Request $request, InventoryController $inventory): View
    {
        return $inventory->renderView($request, 'stock');
    }

    /**
     * Record an inbound stock shipment and increment the available quantity.
     */
    public function stockIn(RecordStockInRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $product = Product::where('sku', $validated['sku'])->firstOrFail();

        DB::transaction(function () use ($product, $validated) {
            $product->increment('quantity', $validated['quantity']);
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'notes' => $validated['notes'] ?? 'Stock In entry',
            ]);
        });

        return redirect()
            ->route('stock.index')
            ->with('success', "Stock In recorded: +{$validated['quantity']} pcs for '{$product->name}'.");
    }

    /**
     * Record an outbound stock dispatch and decrement the available quantity.
     */
    public function stockOut(RecordStockOutRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $product = Product::where('sku', $validated['sku'])->firstOrFail();

        if ($product->quantity < $validated['quantity']) {
            return back()->with('error', "Insufficient stock for '{$product->name}'. Available: {$product->quantity} pcs, requested: {$validated['quantity']} pcs.");
        }

        DB::transaction(function () use ($product, $validated) {
            $product->decrement('quantity', $validated['quantity']);
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $validated['quantity'],
                'notes' => $validated['notes'] ?? 'Stock Out entry',
            ]);
        });

        return redirect()
            ->route('stock.index')
            ->with('success', "Stock Out recorded: -{$validated['quantity']} pcs for '{$product->name}'.");
    }

    /**
     * Process an automated reorder for a low-stock product.
     */
    public function reorder(Product $product): RedirectResponse
    {
        $reorderQty = max($product->reorder_point * 2, 20);

        DB::transaction(function () use ($product, $reorderQty) {
            $product->increment('quantity', $reorderQty);
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $reorderQty,
                'notes' => 'Low-stock automated reorder restock',
            ]);
        });

        return back()->with('success', "Reordered +{$reorderQty} pcs for '{$product->name}'. Stock updated.");
    }
}
