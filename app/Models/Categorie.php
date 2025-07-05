<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Category",
 *     title="Categoría",
 *     description="Esquema de una categoría de productos",
 *     type="object",
 *     required={"name", "code"},
 *     
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         readOnly=true,
 *         example=1,
 *         description="Identificador único de la categoría"
 *     ),
 *     
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=100,
 *         example="Ropa Deportiva",
 *         description="Nombre de la categoría (máx. 100 caracteres)"
 *     ),
 *     
 *     @OA\Property(
 *         property="code",
 *         type="string",
 *         maxLength=100,
 *         example="DEP2024",
 *         description="Código de la categoría (máx. 100 caracteres)"
 *     ),
 *     
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         readOnly=true,
 *         example="2024-01-01T12:00:00Z",
 *         description="Fecha de creación"
 *     ),
 *     
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         readOnly=true,
 *         example="2024-01-02T12:00:00Z",
 *         description="Fecha de última actualización"
 *     )
 * )
 */

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'code'
    ];
}
