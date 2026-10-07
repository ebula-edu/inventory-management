<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ReportController
 *
 * Computes monthly stock movements and category distribution analytics.
 */
class ReportController extends Controller
{
    /**
     * Display the reports and analytics tab.
     */
    public function index(Request $request, InventoryController $inventory): View
    {
        return $inventory->renderView($request, 'reports');
    }
}
