<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class InventoryControllerTest
 *
 * Verifies that all primary inventory navigation endpoints return successful HTTP 200 responses.
 */
class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Product::create([
            'name' => 'Test Mouse',
            'sku' => 'SKU-TEST-1',
            'category' => 'Electronics',
            'quantity' => 15,
            'reorder_point' => 10,
            'price' => 20.00,
        ]);

        Supplier::create([
            'name' => 'Test Vendor',
            'email' => 'vendor@test.com',
            'phone' => '123456789',
            'supplied_items' => 'Electronics',
        ]);
    }

    /**
     * Test the dashboard endpoint.
     */
    public function test_dashboard_renders_successfully(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Test Mouse');
        $response->assertSee('SKU-TEST-1');
    }

    /**
     * Test the inventory overview endpoint.
     */
    public function test_inventory_overview_renders_successfully(): void
    {
        $response = $this->get(route('inventory.overview'));
        $response->assertStatus(200);
        $response->assertSee('Inventory Overview');
    }

    /**
     * Test the products list endpoint.
     */
    public function test_products_catalog_renders_successfully(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Products List');
    }

    /**
     * Test the stock management endpoint.
     */
    public function test_stock_management_renders_successfully(): void
    {
        $response = $this->get(route('stock.index'));
        $response->assertStatus(200);
        $response->assertSee('Stock Management');
    }

    /**
     * Test the suppliers endpoint.
     */
    public function test_suppliers_directory_renders_successfully(): void
    {
        $response = $this->get(route('suppliers.index'));
        $response->assertStatus(200);
        $response->assertSee('Test Vendor');
    }

    /**
     * Test the reports endpoint.
     */
    public function test_reports_analytics_renders_successfully(): void
    {
        $response = $this->get(route('reports.index'));
        $response->assertStatus(200);
        $response->assertSee('Reports & Analytics', false);
    }

    /**
     * Test the settings endpoint.
     */
    public function test_settings_renders_successfully(): void
    {
        $response = $this->get(route('settings.index'));
        $response->assertStatus(200);
        $response->assertSee('Settings');
    }
}
