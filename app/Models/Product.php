<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Producto",
 *     description="Esquema que representa un producto",
 *     type="object",
 *     required={"name", "price"},
 *     
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         readOnly=true,
 *         example=1
 *     ),
 *     
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Zapatos deportivos",
 *         description="Nombre único del producto"
 *     ),
 *     
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         example="Zapatos cómodos para correr",
 *         description="Descripción del producto (opcional)"
 *     ),
 *     
 *     @OA\Property(
 *         property="price",
 *         type="string",
 *         example="59.99",
 *         description="Precio del producto como string. Si se omite, se toma como '0'."
 *     ),
 *     
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         readOnly=true,
 *         example="2024-01-01T12:00:00Z"
 *     ),
 *     
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         readOnly=true,
 *         example="2024-01-05T12:00:00Z"
 *     )
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
