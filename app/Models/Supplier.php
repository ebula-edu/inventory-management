<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Supplier
 *
 * FILE OVERVIEW:
 * Ito ang Eloquent Model para sa table na `suppliers`.
 * Dito naka-save ang lahat ng impormasyon tungkol sa mga suppliers o external vendors:
 * pangalan, email, phone number, at ang mga produktong sinu-supply nila.
 *
 * KAILAN ITO TINATAWAG:
 * - Kapag nagre-reorder ng low stock items para malaman kung kanino magpapadala ng PO (Purchase Order).
 * - Sa Suppliers Directory tab kung saan pwede mag-add, edit, o delete ng vendor.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $supplied_items
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Product> $products
 */
class Supplier extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'supplied_items',
    ];

    /**
     * Relationship: Ang isang Supplier ay pwedeng may maraming Products na naka-link (HasMany).
     *
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
