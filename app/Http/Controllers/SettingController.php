<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class SettingController
 *
 * Manages inventory system preferences including currency and low-stock thresholds.
 */
class SettingController extends Controller
{
    /**
     * Display the settings tab.
     */
    public function index(Request $request, InventoryController $inventory): View
    {
        return $inventory->renderView($request, 'settings');
    }

    /**
     * Update system preferences in session storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'currency' => ['required', 'string', 'max:20'],
            'low_stock_threshold' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        session([
            'inventory_currency' => $validated['currency'],
            'inventory_low_stock_threshold' => (int) $validated['low_stock_threshold'],
        ]);

        return redirect()
            ->route('settings.index')
            ->with('success', 'System settings updated successfully.');
    }
}
