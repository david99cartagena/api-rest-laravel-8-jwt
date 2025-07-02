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
    public function index()
    {
        try {
            $categorie = Categorie::all();

            return response()->json([
                'status' => 200,
                'categories' => $categorie
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno del servidor',
                $e
            ], 500);
        }
    }

    public function create(Request $request)
    {
        //
    }

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
                $e
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);

            return response()->json([
                'status' => 200,
                'categorie' => $categorie
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'No se encontró la categoria con id: ' . $id,
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

    public function edit($id)
    {
        //
    }

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
                $e
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al editar la categoria',
                $e
            ], 500);
        }
    }

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
                $e
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al eliminar la categoria',
                $e
            ], 500);
        }
    }
}
