<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="Usuario",
 *     description="Esquema que representa a un usuario del sistema",
 *     type="object",
 *     required={"name", "role", "email", "password"},
 *     
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         readOnly=true,
 *         example=1,
 *         description="ID único del usuario"
 *     ),
 *     
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Juan Pérez",
 *         description="Nombre del usuario"
 *     ),
 *     
 *     @OA\Property(
 *         property="role",
 *         type="string",
 *         maxLength=30,
 *         example="admin",
 *         description="Rol del usuario (máx. 30 caracteres)"
 *     ),
 *     
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="juan@example.com",
 *         description="Correo electrónico único del usuario"
 *     ),
 *     
 *     @OA\Property(
 *         property="email_verified_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         example="2024-01-01T10:00:00Z",
 *         description="Fecha de verificación del correo electrónico"
 *     ),
 *     
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         writeOnly=true,
 *         example="******",
 *         description="Contraseña del usuario (solo escritura)"
 *     ),
 *     
 *     @OA\Property(
 *         property="remember_token",
 *         type="string",
 *         nullable=true,
 *         readOnly=true,
 *         example="e2a12b32aa98c3",
 *         description="Token para recordar sesión (solo lectura)"
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

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
