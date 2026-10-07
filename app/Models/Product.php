<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Product
 *
 * Represents an inventory item within the warehouse catalog.
 *
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property string $category
 * @property string|null $location
 * @property int $quantity
 * @property int $reorder_point
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, StockMovement> $stockMovements
 */
class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'sku',
        'category',
        'location',
        'quantity',
        'reorder_point',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'reorder_point' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Stock movements associated with this product.
     *
     * @return HasMany<StockMovement, $this>
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Determine whether the product is currently below its reorder point threshold.
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_point;
    }

    /**
     * Accessor for displaying the human-readable stock status badge label.
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
