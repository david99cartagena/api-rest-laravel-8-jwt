<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;


class ProductController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Listar todos los productos",
     *     description="Devuelve una lista con todos los productos disponibles.",
     *     operationId="getProducts",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Productos obtenidos exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Productos obtenidos existosamente"),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="papas"),
     *                     @OA\Property(property="description", type="string", example="demo1"),
     *                     @OA\Property(property="price", type="string", example="13.00"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02T14:59:39.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02T14:59:42.000000Z")
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
     *             @OA\Property(property="error", type="string", example="Exception details")
     *         )
     *     )
     * )
     */

    public function index()
    {
        try {
            $products = Product::all();

            return response()->json([
                'status' => 200,
                'msg' => 'Productos obtenidos existosamente',
                'products' => $products
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Crear un nuevo producto",
     *     description="Registra un nuevo producto en la base de datos. La propiedad 'price' es opcional.",
     *     operationId="storeProduct",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 minLength=3,
     *                 example="papas fritas",
     *                 description="Nombre único del producto (mínimo 3 caracteres)"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 minLength=10,
     *                 example="Papas fritas crocantes y saladas",
     *                 description="Descripción del producto (mínimo 10 caracteres)"
     *             ),
     *             @OA\Property(
     *                 property="price",
     *                 type="string",
     *                 example="15.50",
     *                 description="Precio del producto (opcional, por defecto 0)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se guardó correctamente el producto"),
     *             @OA\Property(
     *                 property="product",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="papas fritas"),
     *                 @OA\Property(property="description", type="string", example="Papas fritas crocantes y saladas"),
     *                 @OA\Property(property="price", type="string", example="15.50"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-04T10:30:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-04T10:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="Error de validación. Por favor revisa los datos ingresados."),
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 example={"name": {"El campo name es obligatorio."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al guardar el producto",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno al guardar el producto"),
     *             @OA\Property(property="error", type="string", example="Mensaje de error interno")
     *         )
     *     )
     * )
     */
    
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:products,name|min:3',
                'description' => 'min:10',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'msg' => 'Error de validación. Por favor revisa los datos ingresados.',
                    'error' => $validator->errors(),
                ], 400);
            }

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price ?? '0'
            ]);

            return response()->json([
                'status' => 200,
                'msg' => 'Se guardó correctamente el producto',
                'product' => $product
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al guardar el producto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Obtener un producto por ID",
     *     description="Devuelve la información de un producto específico. Si el producto no existe, retorna un error 404.",
     *     operationId="getProductById",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se encontró el producto con id: 1"),
     *             @OA\Property(
     *                 property="product",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="papas"),
     *                 @OA\Property(property="description", type="string", example="demo1"),
     *                 @OA\Property(property="price", type="string", example="13.00"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02T14:59:39.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02T14:59:42.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="No se encontró el producto con id: 999"),
     *             @OA\Property(property="error", type="string", example="No query results for model [App\\Models\\Product] 999")
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
            // $product = Product::find($id); // retorna sino existe null
            $product = Product::findOrFail($id); // Si no existe, se lanza excepción y se puede atrapar en un try-catch

            return response()->json([
                'status' => 200,
                'msg' => 'Se encontró el producto con id: ' . $id,
                'product' => $product
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'No se encontró el producto con id: ' . $id,
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Actualizar un producto por ID",
     *     description="Actualiza los datos de un producto específico. El campo 'price' es opcional. Retorna 404 si el producto no existe.",
     *     operationId="updateProduct",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 minLength=3,
     *                 example="papas actualizadas",
     *                 description="Nombre del producto (mínimo 3 caracteres, opcional)"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 minLength=10,
     *                 example="Descripción actualizada del producto",
     *                 description="Descripción (mínimo 10 caracteres, opcional)"
     *             ),
     *             @OA\Property(
     *                 property="price",
     *                 type="string",
     *                 example="17.99",
     *                 description="Precio del producto (opcional)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se editó correctamente el producto"),
     *             @OA\Property(
     *                 property="product",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="papas actualizadas"),
     *                 @OA\Property(property="description", type="string", example="Descripción actualizada del producto"),
     *                 @OA\Property(property="price", type="string", example="17.99"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02T14:59:39.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-04T12:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="Error de validación. Por favor revisa los datos ingresados."),
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 example={"description": {"La descripción debe tener al menos 10 caracteres."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="Producto no encontrado"),
     *             @OA\Property(property="error", type="string", example="No query results for model [App\\Models\\Product] 999")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al editar el producto",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno al editar el producto"),
     *             @OA\Property(property="error", type="string", example="Mensaje de excepción")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'min:3',
                'description' => 'min:10',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'msg' => 'Error de validación. Por favor revisa los datos ingresados.',
                    'error' => $validator->errors(),
                ], 400);
            }

            $product->update($request->only(['name', 'description', 'price']));

            return response()->json([
                'status' => 200,
                'msg' => 'Se editó correctamente el producto',
                'product' => $product
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'Producto no encontrado',
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al editar el producto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Eliminar un producto por ID",
     *     description="Elimina un producto específico de la base de datos. Retorna error si no existe.",
     *     operationId="deleteProduct",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se eliminó correctamente el producto"),
     *             @OA\Property(
     *                 property="product",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="papas"),
     *                 @OA\Property(property="description", type="string", example="demo1"),
     *                 @OA\Property(property="price", type="string", example="13.00"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02T14:59:39.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-04T12:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="Producto no encontrado"),
     *             @OA\Property(property="error", type="string", example="No query results for model [App\\Models\\Product] 999")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al eliminar el producto",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno al eliminar el producto"),
     *             @OA\Property(property="error", type="string", example="Mensaje de excepción")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'status' => 200,
                'msg' => 'Se eliminó correctamente el producto',
                'product' => $product
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'Producto no encontrado',
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al eliminar el producto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
