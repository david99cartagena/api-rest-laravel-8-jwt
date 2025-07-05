<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API para gestiónar productos, usuarios y categorías",
 *     description="Documentación de la API para gestión de productos, usuarios y categorías.",
 *     @OA\Contact(
 *         email="soporte@tuempresa.com"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Products",
 *     description="Operaciones públicas sobre productos"
 * )
 *
 * @OA\Tag(
 *     name="Categories",
 *     description="Administración de categorías (requiere autenticación)"
 * )
 *
 * @OA\Tag(
 *     name="Users",
 *     description="Gestión de usuarios y roles (requiere autenticación)"
 * )
 *
 * @OA\Server(
 *     url="http://localhost/tutorial-backend-app/public",
 *     description="Servidor local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticación JWT usando el esquema Bearer. Ejemplo: 'Bearer {token}'"
 * )
 */
class OASInfo {}
