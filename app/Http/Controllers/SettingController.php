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
            'admin_name' => ['nullable', 'string', 'max:100'],
            'admin_email' => ['nullable', 'email', 'max:255'],
            'currency' => ['required', 'string', 'max:20'],
            'low_stock_threshold' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $updates = [
            'inventory_currency' => $validated['currency'],
            'inventory_low_stock_threshold' => (int) $validated['low_stock_threshold'],
        ];

        if (!empty($validated['admin_name'])) {
            $updates['admin_name'] = $validated['admin_name'];
        }
        if (!empty($validated['admin_email'])) {
            $updates['admin_email'] = $validated['admin_email'];
        }

        session($updates);

        return redirect()
            ->route('settings.index')
            ->with('success', 'System settings updated successfully.');
    }
}
