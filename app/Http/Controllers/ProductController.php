<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Productos",
 *     description="Documentación de la API para gestionar productos.",
 *     @OA\Contact(email="soporte@tuempresa.com")
 * )
 *
 * @OA\Server(
 *     url="http://localhost/tutorial-backend-app/public",
 *     description="Servidor local"
 * )
 */
class ProductController extends Controller
{
    
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Listar productos",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    
    public function index()
    {
        try {
            $products = Product::all();

            return response()->json([
                'status' => 200,
                'products' => $products
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno del servidor',
                $e
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
                    'errors' => $validator->errors(),
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
                $e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // $product = Product::find($id); // retorna sino existe null
            $product = Product::findOrFail($id); // Si no existe, se lanza excepción y se puede atrapar en un try-catch

            return response()->json([
                'status' => 200,
                'product' => $product
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'No se encontró el producto con id: ' . $id,
                $e
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno del servidor',
                $e
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
                    'errors' => $validator->errors(),
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
                $e
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al editar el producto',
                $e
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
                $e
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al eliminar el producto',
                $e
            ], 500);
        }
    }
}
