<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ProductControllerTest
 *
 * Tests product registration, validation rules, and persistence in the database.
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
}
