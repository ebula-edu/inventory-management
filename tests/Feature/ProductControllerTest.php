<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ProductControllerTest
 *
 * Tests product catalog registration, SKU lookup, updating, and deletion.
 */
class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful creation of a new product.
     */
    public function test_can_create_product_with_valid_data(): void
    {
        $payload = [
            'name' => 'Studio Headphones',
            'sku' => 'SKU-HD-99',
            'category' => 'Electronics',
            'location' => 'Aisle 5, Bin B',
            'quantity' => 25,
            'reorder_point' => 10,
            'price' => 149.99,
        ];

        $response = $this->post(route('products.store'), $payload);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-HD-99',
            'name' => 'Studio Headphones',
            'quantity' => 25,
        ]);
    }

    /**
     * Test validation failure when required fields are missing.
     */
    public function test_fails_when_required_fields_missing(): void
    {
        $response = $this->post(route('products.store'), []);

        $response->assertSessionHasErrors(['name', 'sku', 'category', 'quantity']);
    }

    /**
     * Test validation failure for duplicate SKU code.
     */
    public function test_fails_when_sku_is_not_unique(): void
    {
        Product::create([
            'name' => 'Original Item',
            'sku' => 'SKU-DUP-1',
            'category' => 'Electronics',
            'quantity' => 10,
            'reorder_point' => 5,
            'price' => 15.00,
        ]);

        $response = $this->post(route('products.store'), [
            'name' => 'Duplicate Attempt',
            'sku' => 'SKU-DUP-1',
            'category' => 'Electronics',
            'quantity' => 5,
            'price' => 20.00,
        ]);

        $response->assertSessionHasErrors(['sku']);
    }

    /**
     * Test quick lookup by barcode or SKU.
     */
    public function test_can_lookup_product_by_sku(): void
    {
        Product::create([
            'name' => 'Barcode Item',
            'sku' => 'SKU-LOOKUP-123',
            'category' => 'Electronics',
            'quantity' => 14,
            'reorder_point' => 5,
            'price' => 45.00,
        ]);

        $response = $this->getJson(route('products.lookup', 'SKU-LOOKUP-123'));

        $response->assertStatus(200);
        $response->assertJson([
            'found' => true,
            'product' => [
                'sku' => 'SKU-LOOKUP-123',
                'name' => 'Barcode Item',
            ],
        ]);
    }

    /**
     * Test lookup returns 404 when SKU does not exist.
     */
    public function test_lookup_returns_404_for_unknown_sku(): void
    {
        $response = $this->getJson(route('products.lookup', 'SKU-NON-EXISTENT'));

        $response->assertStatus(404);
        $response->assertJson(['found' => false]);
    }

    /**
     * Test updating product with valid data.
     */
    public function test_can_update_product(): void
    {
        $product = Product::create([
            'name' => 'Old Mouse',
            'sku' => 'SKU-OLD-1',
            'category' => 'Electronics',
            'quantity' => 10,
            'reorder_point' => 5,
            'price' => 20.00,
        ]);

        $response = $this->put(route('products.update', $product->id), [
            'name' => 'Updated Mouse Pro',
            'sku' => 'SKU-OLD-1', // keeping same SKU is allowed
            'category' => 'Accessories',
            'location' => 'Aisle 1, Bin Z',
            'quantity' => 35,
            'reorder_point' => 8,
            'price' => 29.99,
        ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $product->refresh();
        $this->assertEquals('Updated Mouse Pro', $product->name);
        $this->assertEquals('Accessories', $product->category);
        $this->assertEquals(35, $product->quantity);
    }

    /**
     * Test deleting a product removes it from database.
     */
    public function test_can_delete_product(): void
    {
        $product = Product::create([
            'name' => 'Item to Delete',
            'sku' => 'SKU-DEL-99',
            'category' => 'Hardware',
            'quantity' => 2,
            'reorder_point' => 1,
            'price' => 5.00,
        ]);

        $response = $this->delete(route('products.destroy', $product->id));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'sku' => 'SKU-DEL-99',
        ]);
    }
}
