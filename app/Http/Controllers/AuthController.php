<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar un nuevo usuario",
     *     description="Crea un nuevo usuario con los datos proporcionados. El campo 'role' puede ser 'user' o 'admin'.",
     *     operationId="register",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","role","email","password"},
     *             @OA\Property(property="name", type="string", example="David Prueba"),
     *             @OA\Property(property="role", type="string", example="user", description="puede ser 'user' o 'admin'"),
     *             @OA\Property(property="email", type="string", format="email", example="davidprueba1@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="prueba12.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se guardó correctamente el Usuario"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al guardar Usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno al guardar Usuario"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    
    public function register(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'role' => $request->role,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'status' => 200,
                'msg' => 'Se guardó correctamente el Usuario',
                'user' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al guardar Usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión de usuario",
     *     description="Inicia sesión con email y contraseña. Devuelve un token JWT si las credenciales son válidas.",
     *     operationId="loginUser",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="davidprueba@gmail.com",
     *                 description="Correo electrónico del usuario registrado"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="prueba123.",
     *                 description="Contraseña del usuario"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Token generado exitosamente"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJh...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="msg", type="string", example="Valida de nuevo correo o contraseña"),
     *             @OA\Property(property="error", type="string", example="Credenciales Invalidas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al generar el token JWT",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="No se pudo crear el token"),
     *             @OA\Property(property="error", type="string", example="Error interno del servidor")
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 401,
                    'msg' => 'Valida de nuevo correo o contrasena',
                    'error' => 'Credenciales Invalidas'
                ], 401);
            }
            return response()->json([
                'status' => 200,
                'msg' => 'Token generado existosamente',
                'token' => $token
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'No se pudo crear el token',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Obtener usuario autenticado",
     *     description="Retorna los datos del usuario autenticado utilizando el token JWT en el encabezado Authorization.",
     *     operationId="getUserProfile",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Usuario obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Usuario obtenido existosamente"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="David Cartagena"),
     *                 @OA\Property(property="role", type="string", example="admin"),
     *                 @OA\Property(property="email", type="string", example="davidprueba@gmail.com"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02T16:33:23.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02T16:33:23.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - token no proporcionado o inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="msg", type="string", example="No autorizado")
     *         )
     *     )
     * )
     */

    public function getUser()
    {
        $user = Auth::user();
        return response()->json([
            'status' => 200,
            'msg' => 'Usuario obtenido existosamente',
            'user' => $user
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Cerrar sesión del usuario",
     *     description="Invalida el token JWT del usuario autenticado y cierra su sesión.",
     *     operationId="logoutUser",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se cerró sesión exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - token no proporcionado o inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="msg", type="string", example="Token inválido o no proporcionado")
     *         )
     *     )
     * )
     */

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json([
            'status' => 200,
            'msg' => 'Se cerró sesión exitosamente'
        ], 200);
    }
}
