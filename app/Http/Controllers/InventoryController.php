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
 * Coordinates inventory dashboard metrics, overview lists, and recent activity.
 */
class InventoryController extends Controller
{
    /**
     * Display the inventory dashboard with real-time statistics and recent movements.
     */
    public function index(Request $request): View
    {
        return $this->renderView($request, 'dashboard');
    }

    /**
     * Display the full inventory overview listing warehouse locations and items.
     */
    public function overview(Request $request): View
    {
        return $this->renderView($request, 'inventory');
    }

    /**
     * Helper to load standard inventory datasets and render the view.
     */
    public function renderView(Request $request, string $activePage = 'dashboard'): View
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        $totalItems = $products->count();
        $totalQuantity = $products->sum('quantity');
        $lowStockProducts = $products->filter(fn (Product $p) => $p->isLowStock());
        $lowStockCount = $lowStockProducts->count();

        $stockInToday = StockMovement::where('type', 'in')
            ->whereDate('created_at', today())
            ->sum('quantity');

        $recentMovements = StockMovement::with('product')
            ->latest()
            ->take(8)
            ->get();

        $currency = session('inventory_currency', 'USD ($)');
        $lowStockThreshold = session('inventory_low_stock_threshold', 10);

        return view('inventory', [
            'activePage' => $activePage,
            'products' => $products,
            'suppliers' => $suppliers,
            'totalItems' => $totalItems,
            'totalQuantity' => $totalQuantity,
            'lowStockCount' => $lowStockCount,
            'lowStockProducts' => $lowStockProducts,
            'stockInToday' => $stockInToday,
            'recentMovements' => $recentMovements,
            'currency' => $currency,
            'lowStockThreshold' => $lowStockThreshold,
        ]);
    }
}
