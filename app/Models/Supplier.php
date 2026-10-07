<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Supplier
 *
 * Represents an external vendor supplying products to the inventory.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $supplied_items
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Supplier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'supplied_items',
    ];
}
