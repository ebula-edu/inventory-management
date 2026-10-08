<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class StockControllerTest
 *
 * Validates stock-in increments, stock-out dispatches, negative inventory prevention,
 * and automated reorder mechanisms.
 */
class StockControllerTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'name' => 'Logitech Webcam',
            'sku' => 'SKU-CAM-10',
            'category' => 'Electronics',
            'quantity' => 20,
            'reorder_point' => 10,
            'price' => 69.99,
        ]);
    }

    /**
     * Test stock in increases inventory quantity and writes a movement log.
     */
    public function test_stock_in_increases_quantity(): void
    {
        $response = $this->post(route('stock.in'), [
            'sku' => $this->product->sku,
            'quantity' => 30,
            'notes' => 'Bulk restock',
        ]);

        $response->assertRedirect(route('stock.index'));
        $response->assertSessionHas('success');

        $this->product->refresh();
        $this->assertEquals(50, $this->product->quantity);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'in',
            'quantity' => 30,
        ]);
    }

    /**
     * Test stock out decreases inventory quantity and writes a movement log.
     */
    public function test_stock_out_decreases_quantity(): void
    {
        $response = $this->post(route('stock.out'), [
            'sku' => $this->product->sku,
            'quantity' => 8,
            'notes' => 'Customer order #1001',
        ]);

        $response->assertRedirect(route('stock.index'));
        $response->assertSessionHas('success');

        $this->product->refresh();
        $this->assertEquals(12, $this->product->quantity);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'out',
            'quantity' => 8,
        ]);
    }

    /**
     * Test stock out is rejected when requesting more than available inventory.
     */
    public function test_stock_out_fails_on_insufficient_stock(): void
    {
        $response = $this->post(route('stock.out'), [
            'sku' => $this->product->sku,
            'quantity' => 99,
            'notes' => 'Excessive order',
        ]);

        $response->assertSessionHas('error');

        $this->product->refresh();
        $this->assertEquals(20, $this->product->quantity);
    }

    /**
     * Test reorder action increments stock and records the supplier in movement notes.
     */
    public function test_reorder_increases_stock(): void
    {
        $supplier = \App\Models\Supplier::create([
            'name'           => 'Apex Global Distribution',
            'email'          => 'contact@apexglobal.test',
            'phone'          => '+1 555 777 8888',
            'supplied_items' => 'Sensors, Keyboards',
        ]);

        $this->product->update(['supplier_id' => $supplier->id]);

        $response = $this->post(route('stock.reorder', $this->product->id));

        $response->assertSessionHas('success');
        $response->assertSessionHas('success', fn ($msg) => str_contains($msg, 'Apex Global Distribution'));

        $this->product->refresh();
        $this->assertGreaterThan(20, $this->product->quantity);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type'       => 'in',
            'notes'      => 'Reordered from supplier: Apex Global Distribution',
        ]);
    }
}
