<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Producto",
 *     type="object",
 *     required={"name", "description", "price"},
 *     @OA\Property(property="id", type="integer", example=1, readOnly=true),
 *     @OA\Property(property="name", type="string", example="Zapatos deportivos"),
 *     @OA\Property(property="description", type="string", example="Zapatos cómodos para correr"),
 *     @OA\Property(property="price", type="number", format="float", example=59.99),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-05T12:00:00Z", readOnly=true)
 * )
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'name',
        'description',
        'price'
    ];
}
