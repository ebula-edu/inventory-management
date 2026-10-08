<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Product
 *
 * FILE OVERVIEW:
 * Ito ang Eloquent Model para sa table na `products`.
 * Kumakatawan ito sa bawat paninda o item sa warehouse catalog.
 *
 * KAILAN ITO TINATAWAG (WHEN IT IS CALLED):
 * - Tinatawag ito ng ProductController, StockController, at InventoryController
 *   tuwing kailangan mag-create, mag-update, mag-audit ng stock, o mag-reorder.
 *
 * @property int $id - Primary key ng produkto
 * @property string $name - Pangalan ng produkto (hal. Wireless Mouse M100)
 * @property string $sku - Stock Keeping Unit (unique code, hal. SKU-8921)
 * @property string $category - Kategorya (Electronics, Office Supplies, etc.)
 * @property int|null $supplier_id - Foreign key papunta sa official supplier
 * @property string|null $location - Lokasyon sa warehouse (Aisle 1, Shelf B)
 * @property int $quantity - Kasalukuyang bilang ng piraso sa imbentaryo
 * @property int $reorder_point - Critical threshold; kapag umabot dito, magiging 'Low Stock'
 * @property float $price - Presyo bawat piraso
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Supplier|null $supplier
 * @property-read Collection<int, StockMovement> $stockMovements
 */
class Product extends Model
{
    /**
     * Mga fields na pinapayagang i-mass assign via Product::create() o $product->update().
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'sku',
        'category',
        'supplier_id',
        'location',
        'quantity',
        'reorder_point',
        'price',
    ];

    /**
     * Automatic type casting para sa numbers at decimals.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'supplier_id'   => 'integer',
            'quantity'      => 'integer',
            'reorder_point' => 'integer',
            'price'         => 'decimal:2',
        ];
    }

    /**
     * Relationship: Ang bawat produkto ay pwedeng may official Supplier (BelongsTo).
     * Ito ang gagamitin natin kapag nag-reorder para malaman kung kaninong vendor bibili!
     *
     * @return BelongsTo<Supplier, $this>
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relationship: Ang isang produkto ay may maraming Stock Movements (HasMany).
     * Naitatala rito ang bawat stock-in at stock-out transaction.
     *
     * @return HasMany<StockMovement, $this>
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Helper Method: Tinitingnan kung low-stock na ang produkto (mas mababa o pantay sa reorder_point).
     *
     * @return bool
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_point;
    }

    /**
     * Accessor para sa status badge sa UI:
     * - 'Out of Stock' kung zero o negative
     * - 'Low Stock' kung umabot na sa reorder_point
     * - 'In Stock' kung marami pa ang supply
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->quantity <= 0) {
            return 'Out of Stock';
        }

        if ($this->isLowStock()) {
            return 'Low Stock';
        }

        return 'In Stock';
    }
}
