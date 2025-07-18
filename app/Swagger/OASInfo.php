<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Gestión con JWT y Roles",
 *     description="Esta API permite gestionar productos, usuarios y categorías con autenticación basada en JWT y control de acceso por roles.",
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
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor local o producción"
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
