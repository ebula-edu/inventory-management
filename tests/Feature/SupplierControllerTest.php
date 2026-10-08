<?php

namespace Tests\Feature;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful creation of a new supplier.
     */
    public function test_can_create_supplier_with_valid_data(): void
    {
        $payload = [
            'name'           => 'Acme Tech Supply',
            'email'          => 'sales@acmetech.com',
            'phone'          => '+1 555 987 6543',
            'supplied_items' => 'Monitors, Docks, Adapters',
        ];

        $response = $this->post(route('suppliers.store'), $payload);

        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('suppliers', [
            'name'  => 'Acme Tech Supply',
            'email' => 'sales@acmetech.com',
        ]);
    }

    /**
     * Test validation failure when required supplier fields are missing.
     */
    public function test_fails_when_required_fields_missing(): void
    {
        $response = $this->post(route('suppliers.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'phone', 'supplied_items']);
        $this->assertEquals(0, Supplier::count());
    }

    /**
     * Test duplicate supplier name rejection.
     */
    public function test_fails_when_name_is_not_unique(): void
    {
        Supplier::create([
            'name'           => 'Unique Vendor',
            'email'          => 'first@vendor.com',
            'phone'          => '1234567890',
            'supplied_items' => 'Items A',
        ]);

        $response = $this->post(route('suppliers.store'), [
            'name'           => 'Unique Vendor',
            'email'          => 'second@vendor.com',
            'phone'          => '0987654321',
            'supplied_items' => 'Items B',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertEquals(1, Supplier::count());
    }

    /**
     * Test updating an existing supplier.
     */
    public function test_can_update_supplier(): void
    {
        $supplier = Supplier::create([
            'name'           => 'Old Supplier Name',
            'email'          => 'old@supplier.com',
            'phone'          => '111-222-3333',
            'supplied_items' => 'Packaging',
        ]);

        $response = $this->put(route('suppliers.update', $supplier->id), [
            'name'           => 'Updated Supplier Name',
            'email'          => 'new@supplier.com',
            'phone'          => '444-555-6666',
            'supplied_items' => 'Boxes, Tape, Bubble Wrap',
        ]);

        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('suppliers', [
            'id'    => $supplier->id,
            'name'  => 'Updated Supplier Name',
            'email' => 'new@supplier.com',
        ]);
    }

    /**
     * Test removing a supplier.
     */
    public function test_can_delete_supplier(): void
    {
        $supplier = Supplier::create([
            'name'           => 'To Be Deleted',
            'email'          => 'delete@supplier.com',
            'phone'          => '000-000-0000',
            'supplied_items' => 'Deprecated stock',
        ]);

        $response = $this->delete(route('suppliers.destroy', $supplier->id));

        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('suppliers', [
            'id' => $supplier->id,
        ]);
    }
}
