<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;

/**
 * Class NotificationController
 *
 * FILE OVERVIEW:
 * Ito ang API controller na nagbibigay ng dynamic notification data sa topbar panel.
 * Pinagsasama nito ang:
 * 1. Low-stock alerts (mga item na kailangan nang i-reorder).
 * 2. Pinakabagong stock movement transactions (mga bagong dating o na-dispatch na gamit).
 *
 * KAILAN ITO TINATAWAG:
 * - Tinatawag ito via asynchronous AJAX/Fetch ng topbar.js tuwing naglo-load ang page
 *   at tuwing 60 segundo sa background (`GET /api/notifications`).
 */
class NotificationController extends Controller
{
    /**
     * Ibalik ang JSON payload ng kasalukuyang mga notifications.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // 1. Kumuha ng mga produkto na nasa Low-Stock level (quantity <= reorder_point)
        $lowStock = Product::where('quantity', '<=', \DB::raw('reorder_point'))
            ->orderBy('quantity')
            ->take(8)
            ->get(['id', 'name', 'sku', 'quantity', 'reorder_point'])
            ->map(fn (Product $p) => [
                'type'    => 'low_stock',
                'icon'    => 'fa-triangle-exclamation',
                'colour'  => 'warning',
                'title'   => "Low stock: {$p->name}",
                'body'    => "{$p->quantity} pcs natitira (reorder kapag {$p->reorder_point})",
                'time'    => now()->toISOString(),
                'page'    => 'low-stock',
            ]);

        // 2. Kumuha ng pinakabagong 6 stock movements (stock-in at stock-out transactions)
        $movements = StockMovement::with('product:id,name,sku')
            ->latest()
            ->take(6)
            ->get()
            ->map(fn (StockMovement $m) => [
                'type'    => $m->type === 'in' ? 'stock_in' : 'stock_out',
                'icon'    => $m->type === 'in' ? 'fa-arrow-down-to-line' : 'fa-arrow-up-from-line',
                'colour'  => $m->type === 'in' ? 'success' : 'info',
                'title'   => $m->type === 'in'
                    ? "Stock In: {$m->product->name}"
                    : "Stock Out: {$m->product->name}",
                'body'    => ($m->type === 'in' ? '+' : '−') . "{$m->quantity} pcs · {$m->notes}",
                'time'    => $m->created_at->toISOString(),
                'page'    => 'stock',
            ]);

        // Pagsamahin at i-sort ayon sa pinakabagong oras
        $all = $lowStock->concat($movements)
            ->sortByDesc('time')
            ->values();

        return response()->json([
            'count'         => $all->count(),
            'unread'        => $lowStock->count(),
            'notifications' => $all,
        ]);
    }
}
