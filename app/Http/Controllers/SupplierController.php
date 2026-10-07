<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class SupplierController
 *
 * Handles supplier directory displays and vendor listings.
 */
class SupplierController extends Controller
{
    /**
     * Display the suppliers tab.
     */
    public function index(Request $request, InventoryController $inventory): View
    {
        return $inventory->renderView($request, 'suppliers');
    }
}
