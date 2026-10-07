<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * Populates the application database with initial inventory products,
 * suppliers, and recent stock movements.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Wireless Mouse M100',
                'sku' => 'SKU-8921',
                'category' => 'Electronics',
                'location' => 'Aisle 2, Bin A',
                'quantity' => 142,
                'reorder_point' => 20,
                'price' => 29.99,
            ],
            [
                'name' => 'Mechanical Keyboard RGB',
                'sku' => 'SKU-4310',
                'category' => 'Electronics',
                'location' => 'Aisle 2, Bin C',
                'quantity' => 8,
                'reorder_point' => 20,
                'price' => 89.99,
            ],
            [
                'name' => 'USB-C Fast Cable 1m',
                'sku' => 'SKU-1029',
                'category' => 'Accessories',
                'location' => 'Aisle 1, Bin B',
                'quantity' => 320,
                'reorder_point' => 50,
                'price' => 12.50,
            ],
            [
                'name' => 'Ergonomic Desk Mat',
                'sku' => 'SKU-5520',
                'category' => 'Office Supplies',
                'location' => 'Aisle 4, Bin A',
                'quantity' => 45,
                'reorder_point' => 15,
                'price' => 24.99,
            ],
            [
                'name' => '27" IPS Monitor',
                'sku' => 'SKU-7721',
                'category' => 'Electronics',
                'location' => 'Aisle 3, Bin B',
                'quantity' => 24,
                'reorder_point' => 10,
                'price' => 249.00,
            ],
            [
                'name' => 'Bluetooth Speaker',
                'sku' => 'SKU-9902',
                'category' => 'Electronics',
                'location' => 'Aisle 1, Bin D',
                'quantity' => 5,
                'reorder_point' => 15,
                'price' => 59.99,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(['sku' => $data['sku']], $data);
        }

        $suppliers = [
            [
                'name' => 'Apex Electronics Co.',
                'email' => 'orders@apexelectronics.com',
                'phone' => '+1 (555) 019-2834',
                'supplied_items' => 'Peripherals, Cables',
            ],
            [
                'name' => 'Global Pack & Ship Ltd.',
                'email' => 'support@globalpack.com',
                'phone' => '+1 (555) 014-9921',
                'supplied_items' => 'Packaging, Boxes',
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::firstOrCreate(['name' => $sup['name']], $sup);
        }

        $mouse = Product::where('sku', 'SKU-8921')->first();
        if ($mouse && StockMovement::count() === 0) {
            StockMovement::create([
                'product_id' => $mouse->id,
                'type' => 'in',
                'quantity' => 50,
                'notes' => 'Supplier restock',
            ]);
            StockMovement::create([
                'product_id' => $mouse->id,
                'type' => 'out',
                'quantity' => 12,
                'notes' => 'Customer order #108',
            ]);
        }
    }
}
