<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Listar todos los usuarios",
     *     description="Devuelve una lista de todos los usuarios registrados en el sistema.",
     *     operationId="index",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Usuarios obtenidos exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Usuarios obtenidos existosamente"),
     *             @OA\Property(
     *                 property="users",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="David Prueba"),
     *                     @OA\Property(property="role", type="string", example="user"),
     *                     @OA\Property(property="email", type="string", format="email", example="davidprueba1@gmail.com"),
     *                     @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02T16:33:42.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02T16:33:42.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="error", type="string", example="Mensaje del error")
     *         )
     *     )
     * )
     */

    public function index()
    {
        try {
            $users = User::all();

            return response()->json([
                'status' => 200,
                'msg' => 'Usuarios obtenidos existosamente',
                'users' => $users
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno del servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

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

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Actualizar un usuario",
     *     description="Actualiza la información de un usuario. Solo se modifican los campos enviados. Se debe enviar el ID del usuario en la URL.",
     *     operationId="updateUser",
     *     tags={"Users"},
     *     
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos a actualizar del usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="David Cartagena"),
     *             @OA\Property(property="role", type="string", example="admin"),
     *             @OA\Property(property="email", type="string", format="email", example="davidprueba@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="prueba123."),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="prueba123.")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Usuario actualizado correctamente"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 description="Información del usuario actualizado",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="David Cartagena"),
     *                 @OA\Property(property="email", type="string", example="davidprueba@gmail.com"),
     *                 @OA\Property(property="role", type="string", example="admin"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-07-06T15:30:00Z"),
     *                 @OA\Property(property="created_at", type="string", example="2024-01-01T10:00:00Z")
     *             )
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=422,
     *         description="Errores de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=422),
     *             @OA\Property(property="msg", type="string", example="Errores de validación"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al actualizar el usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error al actualizar el usuario"),
     *             @OA\Property(property="error", type="string", example="Mensaje del error")
     *         )
     *     )
     * )
     */

    public function updateUser(Request $request, $id)
    {
        try {

            $user = User::findOrFail($id);
            $rules = [];

            if ($request->has('name')) {
                $rules['name'] = 'string|max:255';
            }
            if ($request->has('email')) {
                $rules['email'] = 'email|unique:users,email,' . $id;
            }
            if ($request->has('role')) {
                $rules['role'] = 'string';
            }
            if ($request->has('password')) {
                $rules['password'] = 'string|min:6|confirmed';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'msg' => 'Errores de validación',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $request->only(['name', 'email', 'role']);
            $user->update($data);

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
                $user->save();
            }

            return response()->json([
                'status' => 200,
                'msg' => 'Usuario actualizado correctamente',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error al actualizar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Eliminar un usuario",
     *     description="Elimina un usuario existente por su ID.",
     *     operationId="destroy",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a eliminar",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se eliminó correctamente el usuario"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="David Prueba"),
     *                 @OA\Property(property="email", type="string", format="email", example="davidprueba1@gmail.com"),
     *                 @OA\Property(property="role", type="string", example="user"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02T16:33:42.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02T16:33:42.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="usuario no encontrado"),
     *             @OA\Property(property="error", type="string", example="No query results for model [App\\Models\\User] 999")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al eliminar el usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno al eliminar el usuario"),
     *             @OA\Property(property="error", type="string", example="Mensaje del error")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'status' => 200,
                'msg' => 'Se eliminó correctamente el usuario',
                'user' => $user
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'usuario no encontrado',
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al eliminar el usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}