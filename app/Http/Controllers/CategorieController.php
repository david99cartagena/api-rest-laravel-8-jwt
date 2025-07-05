<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CategorieController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/listadeCategorias",
     *     summary="Listar todas las categorías (requiere autenticación)",
     *     description="Devuelve una lista de todas las categorías. Esta ruta requiere un token JWT.",
     *     operationId="getAllCategories",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Categorías obtenidas exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Categorias obtenidos existosamente"),
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Comida"),
     *                     @OA\Property(property="description", type="string", example="Categoría de alimentos"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-01T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02T10:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - token no proporcionado o inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="msg", type="string", example="Token no válido o expirado"),
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="error", type="string", example="Mensaje de excepción")
     *         )
     *     )
     * )
     */

    public function index()
    {
        try {
            $categories = Categorie::all();

            return response()->json([
                'status' => 200,
                'msg' => 'Categorias obtenidos existosamente',
                'categories' => $categories
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno del servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function create(Request $request)
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Crear una nueva categoría (requiere ser admin)",
     *     description="Permite a un usuario con rol de administrador crear una nueva categoría. Requiere token JWT.",
     *     operationId="storeCategory",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "code"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Bebidas",
     *                 description="Nombre de la categoría"
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="BEV001",
     *                 description="Código identificador único de la categoría"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se guardó correctamente la categoria"),
     *             @OA\Property(
     *                 property="categorie",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Bebidas"),
     *                 @OA\Property(property="code", type="string", example="BEV001"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-04T13:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-04T13:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - token no proporcionado o inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="msg", type="string", example="Token no válido o expirado"),
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al guardar la categoría",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno al guardar la categoria"),
     *             @OA\Property(property="error", type="string", example="Mensaje de error")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        try {
            $categorie = Categorie::create([
                'name' => $request->name,
                'code' => $request->code,
            ]);

            return response()->json([
                'status' => 200,
                'msg' => 'Se guardó correctamente la categoria',
                'categorie' => $categorie
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al guardar la categoria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Obtener una categoría por ID (requiere ser admin)",
     *     description="Retorna los datos de una categoría específica. Requiere token JWT válido y permisos de administrador.",
     *     operationId="getCategoryById",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a buscar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se encontró la categoria con id: 1"),
     *             @OA\Property(
     *                 property="categorie",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Bebidas"),
     *                 @OA\Property(property="code", type="string", example="BEV001"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-04T13:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-04T13:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="No se encontró la categoria con id: 99"),
     *             @OA\Property(property="error", type="string", example="No query results for model [App\\Models\\Categorie] 99")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - token no proporcionado o inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="msg", type="string", example="No autorizado"),
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="error", type="string", example="Mensaje de excepción")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);

            return response()->json([
                'status' => 200,
                'msg' => 'Se encontró la categoria con id: ' . $id,
                'categorie' => $categorie
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'No se encontró la categoria con id: ' . $id,
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno del servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Actualizar una categoría (requiere ser admin)",
     *     description="Edita los datos de una categoría existente. Requiere token JWT válido y permisos de administrador.",
     *     operationId="updateCategory",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría que se desea actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Snacks",
     *                 description="Nuevo nombre de la categoría"
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="SNK001",
     *                 description="Nuevo código de la categoría"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se editó correctamente la categoria"),
     *             @OA\Property(
     *                 property="categorie",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Snacks"),
     *                 @OA\Property(property="code", type="string", example="SNK001"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-01T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-04T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="Categoria no encontrado"),
     *             @OA\Property(property="error", type="string", example="No query results for model [App\\Models\\Categorie] 99")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al editar la categoría",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno al editar la categoria"),
     *             @OA\Property(property="error", type="string", example="Mensaje de excepción")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            $categorie = Categorie::findOrFail($id);

            $categorie->update($request->only(['name', 'code']));

            return response()->json([
                'status' => 200,
                'msg' => 'Se editó correctamente la categoria',
                'categorie' => $categorie
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'Categoria no encontrado',
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al editar la categoria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Eliminar una categoría (requiere ser admin)",
     *     description="Elimina una categoría existente. Requiere token JWT válido y permisos de administrador.",
     *     operationId="deleteCategory",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se eliminó correctamente la categoria"),
     *             @OA\Property(
     *                 property="categorie",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Bebidas"),
     *                 @OA\Property(property="code", type="string", example="BEV001"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-01T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-04T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="categoria no encontrado"),
     *             @OA\Property(property="error", type="string", example="No query results for model [App\\Models\\Categorie] 99")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno al eliminar la categoria"),
     *             @OA\Property(property="error", type="string", example="Mensaje de excepción")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            $categorie->delete();

            return response()->json([
                'status' => 200,
                'msg' => 'Se eliminó correctamente la categoria',
                'categorie' => $categorie
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'categoria no encontrado',
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al eliminar la categoria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
