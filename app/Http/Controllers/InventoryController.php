<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class InventoryController
 *
 * FILE OVERVIEW:
 * Ito ang central hub o dashboard controller ng buong inventory system.
 * Dito kinakalkula ang lahat ng real-time statistics:
 * - Kabuuang bilang ng mga produkto at kabuuang bilang ng units sa bodega.
 * - Bilang ng mga low-stock alerts.
 * - Dami ng stock-in transactions ngayong araw.
 * - Buwanang takbo ng stock movements para sa visual charts.
 * - Dynamic listahan ng mga suppliers at categories.
 *
 * KAILAN ITO TINATAWAG:
 * - Kapag binuksan ang main dashboard (`GET /` o `GET /dashboard`).
 * - Kapag binuksan ang full overview (`GET /inventory`).
 * - Tinatawag din ito bilang helper service ng ibang controllers (ProductController,
 *   StockController, SupplierController, etc.) para mag-render ng iisang blade view.
 */
class InventoryController extends Controller
{
    /**
     * Ipinapakita ang dashboard kasama ang real-time statistics cards at recent activities.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        return $this->renderView($request, 'dashboard');
    }

    /**
     * Ipinapakita ang Inventory Overview view kasama ang buong listahan ng items at locations.
     *
     * @param Request $request
     * @return View
     */
    public function overview(Request $request): View
    {
        return $this->renderView($request, 'inventory');
    }

    /**
     * Helper Method: Naglo-load ng real dynamic datasets mula sa SQL database
     * at ipinapasa ang mga ito sa Blade template para ma-render sa UI.
     *
     * @param Request $request
     * @param string $activePage - Ang tab na dapat naka-activate sa navigation
     * @return View
     */
    public function renderView(Request $request, string $activePage = 'dashboard'): View
    {
        // Kukunin ang lahat ng produkto kasama ang kanilang official supplier (Eager Loading)
        $products = Product::with('supplier')->orderBy('name')->get();

        // Kukunin ang lahat ng rehistradong suppliers
        $suppliers = Supplier::orderBy('name')->get();

        // Kalkulahin ang kabuuang bilang ng produkto at kabuuang piraso sa bodega
        $totalItems = $products->count();
        $totalQuantity = $products->sum('quantity');

        // Hanapin ang mga produktong mababa na sa safety threshold (low stock)
        $lowStockProducts = $products->filter(fn (Product $p) => $p->isLowStock());
        $lowStockCount = $lowStockProducts->count();

        // Bilang ng mga pumasok na units ngayong araw
        $stockInToday = StockMovement::where('type', 'in')
            ->whereDate('created_at', today())
            ->sum('quantity');

        // Pinakahuling 8 stock movement transactions
        $recentMovements = StockMovement::with('product')
            ->latest()
            ->take(8)
            ->get();

        // Currency at threshold preferences mula sa session
        $currency = session('inventory_currency', 'USD ($)');
        $lowStockThreshold = session('inventory_low_stock_threshold', 10);

        // Buwanang aggregate ng stock movements para sa kasalukuyang taon (Real Database Data)
        $currentYear = now()->year;
        $monthlyMovements = collect(range(1, 12))->map(function (int $month) use ($currentYear) {
            $inUnits = (int) StockMovement::where('type', 'in')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('quantity');

            $outUnits = (int) StockMovement::where('type', 'out')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('quantity');

            $monthLabel = \Carbon\Carbon::create($currentYear, $month, 1)->format('M');

            return [
                'month'     => $monthLabel,
                'month_num' => $month,
                'in'        => $inUnits,
                'out'       => $outUnits,
            ];
        });

        // Pinakamataas na dami ng movement para magamit na basehan sa percentage height ng chart bars
        $maxMovementQty = max(1, (int) $monthlyMovements->max(fn ($m) => max($m['in'], $m['out'])));

        // Kumuha ng mga natatanging kategorya mula sa catalog para sa dynamic dropdowns
        $categories = Product::select('category')->distinct()->pluck('category')->filter()->values();
        if ($categories->isEmpty()) {
            $categories = collect(['Electronics', 'Accessories', 'Office Supplies', 'Hardware']);
        }

        return view('inventory', [
            'activePage'        => $activePage,
            'products'          => $products,
            'suppliers'         => $suppliers,
            'totalItems'        => $totalItems,
            'totalQuantity'     => $totalQuantity,
            'lowStockCount'     => $lowStockCount,
            'lowStockProducts'  => $lowStockProducts,
            'stockInToday'      => $stockInToday,
            'recentMovements'   => $recentMovements,
            'currency'          => $currency,
            'lowStockThreshold' => $lowStockThreshold,
            'monthlyMovements'  => $monthlyMovements,
            'maxMovementQty'    => $maxMovementQty,
            'currentYear'       => $currentYear,
            'categories'        => $categories,
        ]);
    }
}
