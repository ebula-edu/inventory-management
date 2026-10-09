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
 * FILE OVERVIEW:
 * Ito ang controller na namamahala sa lahat ng operasyon sa stock at bodega:
 * 1. Stock In (Papasok na produkto mula sa supplier o shipment).
 * 2. Stock Out (Palabas na produkto para sa order ng customer).
 * 3. Reorder (Awtomatikong pag-order muli kapag low stock ang produkto sa nakatakdang supplier).
 *
 * KAILAN ITO TINATAWAG:
 * - Kapag nag-submit ng Stock In form (`POST /stock/in`).
 * - Kapag nag-submit ng Stock Out form (`POST /stock/out`).
 * - Kapag pinindot ang 'Reorder' button sa Low Stock Alerts o Stock Management (`POST /stock/{product}/reorder`).
 */
class StockController extends Controller
{
    /**
     * Ipinapakita ang Stock Management view na may listahan ng kasalukuyang dami ng mga gamit.
     *
     * @param Request $request
     * @param InventoryController $inventory
     * @return View
     */
    public function index(Request $request, InventoryController $inventory): View
    {
        return $inventory->renderView($request, 'stock');
    }

    /**
     * Stock In: Nagre-record ng bagong dating na supply at nagdadagdag sa quantity ng produkto.
     * Gumagamit ng DB::transaction para kung magka-error, hindi mapuputol sa gitna ang data.
     *
     * @param RecordStockInRequest $request
     * @return RedirectResponse
     */
    public function stockIn(RecordStockInRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $product = Product::where('sku', $validated['sku'])->firstOrFail();

        DB::transaction(function () use ($product, $validated) {
            // Dagdagan ang dami ng produkto sa inventory
            $product->increment('quantity', $validated['quantity']);

            // Magtala ng record sa history table (stock_movements)
            StockMovement::create([
                'product_id' => $product->id,
                'type'       => 'in',
                'quantity'   => $validated['quantity'],
                'notes'      => $validated['notes'] ?? 'Stock In entry',
            ]);
        });

        return redirect()
            ->route('stock.index')
            ->with('success', "Stock In recorded: +{$validated['quantity']} pcs for '{$product->name}'.");
    }

    /**
     * Stock Out: Nagre-record ng na-dispatch o naibentang produkto at nagbabawas sa imbentaryo.
     * May validation para hindi pwedeng maging negative ang stock.
     *
     * @param RecordStockOutRequest $request
     * @return RedirectResponse
     */
    public function stockOut(RecordStockOutRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $product = Product::where('sku', $validated['sku'])->firstOrFail();

        // Safety check: Hindi pwedeng mag-dispatch nang mas marami kaysa sa available stock
        if ($product->quantity < $validated['quantity']) {
            return back()->with('error', "Kulang ang stock para sa '{$product->name}'. Available: {$product->quantity} pcs, hinihingi: {$validated['quantity']} pcs.");
        }

        DB::transaction(function () use ($product, $validated) {
            // Bawasan ang dami ng produkto sa inventory
            $product->decrement('quantity', $validated['quantity']);

            // Magtala ng record sa stock_movements history
            StockMovement::create([
                'product_id' => $product->id,
                'type'       => 'out',
                'quantity'   => $validated['quantity'],
                'notes'      => $validated['notes'] ?? 'Stock Out entry',
            ]);
        });

        return redirect()
            ->route('stock.index')
            ->with('success', "Stock Out recorded: -{$validated['quantity']} pcs for '{$product->name}'.");
    }

    /**
     * Reorder Process: Nagpapadala ng reorder restock para sa mga produktong nasa Low Stock status.
     * Awtomatikong tinutukoy kung SINO ANG OFFICIAL SUPPLIER ng produkto para malinaw kung kanino bibili!
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function reorder(Product $product): RedirectResponse
    {
        // Kalkulahin ang dami ng kailangang i-order (doble ng reorder point o minimum 20 pcs)
        $reorderQty = max($product->reorder_point * 2, 20);

        // Alamin kung sino ang official supplier ng produktong ito
        $supplier = $product->supplier;
        $supplierName = $supplier ? $supplier->name : 'General Warehouse Supplier';

        DB::transaction(function () use ($product, $reorderQty, $supplierName) {
            // Dagdagan muli ang stock dahil nag-reorder
            $product->increment('quantity', $reorderQty);

            // Itala sa history kung saang supplier inorder ang shipment
            StockMovement::create([
                'product_id' => $product->id,
                'type'       => 'in',
                'quantity'   => $reorderQty,
                'notes'      => "Reordered from supplier: {$supplierName}",
            ]);
        });

        return back()->with('success', "Reordered +{$reorderQty} pcs for '{$product->name}' from supplier: {$supplierName}. Stock updated.");
    }
}
