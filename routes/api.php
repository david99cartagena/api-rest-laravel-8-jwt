<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/create', [ProductController::class, 'create']);
// Route::post('/create', ['ProductController@create']);

Route::apiResource('products', ProductController::class);
// Route::apiResource('categories', CategorieController::class);
Route::get('users', [AuthController::class, 'index']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::put('/users/{id}', [AuthController::class, 'updateUser']);
Route::delete('/users/{id}', [AuthController::class, 'destroy']);

// Route::middleware([IsUserAuth::class])->group(function () {
//     Route::controller(AuthController::class)->group(function () {
//         Route::post('logout', 'logout');
//         Route::get('me', 'getUser');
//     });

//     Route::get('/listadeCategorias', [CategorieController::class, 'index']);

//     Route::middleware([IsAdmin::class])->group(function () {
//         Route::controller(CategorieController::class)->group(function () {
//             Route::apiResource('categories', CategorieController::class);
//         });
//     });
// });

Route::middleware(['isUserAuth'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'getUser');
    });

    Route::get('/listadeCategorias', [CategorieController::class, 'index']);

    Route::middleware(['isAdmin'])->group(function () {
        Route::apiResource('categories', CategorieController::class);
    });
});
