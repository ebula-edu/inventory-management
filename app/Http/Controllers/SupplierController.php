<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Class SupplierController
 *
 * FILE OVERVIEW:
 * Controller para sa pamamahala ng mga Suppliers at Vendors:
 * - Pagpapakita ng listahan ng mga suppliers (index)
 * - Pag-record ng bagong supplier na may validation (store)
 * - Pag-edit ng contact at supplied items (update)
 * - Pagtanggal ng supplier sa directory (destroy)
 *
 * KAILAN ITO TINATAWAG:
 * - Kapag binuksan ang tab na 'Suppliers' sa sidebar (`GET /suppliers`).
 * - Kapag nag-save ng bagong supplier mula sa modal form (`POST /suppliers`).
 * - Kapag nag-update ng supplier (`PUT /suppliers/{supplier}`).
 * - Kapag nag-delete ng supplier (`DELETE /suppliers/{supplier}`).
 */
class SupplierController extends Controller
{
    /**
     * Ipinapakita ang Suppliers Directory tab.
     *
     * @param Request $request
     * @param InventoryController $inventory
     * @return View
     */
    public function index(Request $request, InventoryController $inventory): View
    {
        return $inventory->renderView($request, 'suppliers');
    }

    /**
     * Mag-save ng bagong supplier sa database.
     * Sinisigurado ng validation na may pangalan, email, phone, at listahan ng items.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255', 'unique:suppliers,name'],
            'email'          => ['required', 'email', 'max:255'],
            'phone'          => ['required', 'string', 'max:50'],
            'supplied_items' => ['required', 'string', 'max:500'],
        ]);

        $supplier = Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', "Matagumpay na nairehistro ang supplier na '{$supplier->name}'.");
    }

    /**
     * I-update ang detalye ng existing supplier.
     *
     * @param Request $request
     * @param Supplier $supplier
     * @return RedirectResponse
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255', Rule::unique('suppliers')->ignore($supplier->id)],
            'email'          => ['required', 'email', 'max:255'],
            'phone'          => ['required', 'string', 'max:50'],
            'supplied_items' => ['required', 'string', 'max:500'],
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', "Na-update ang detalye ng supplier na '{$supplier->name}'.");
    }

    /**
     * Tanggalin ang supplier sa database.
     *
     * @param Supplier $supplier
     * @return RedirectResponse
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $name = $supplier->name;
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', "Nabura na ang supplier na '{$name}'.");
    }
}
