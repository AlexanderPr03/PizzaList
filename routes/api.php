<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PizzaController;
use App\Http\Controllers\IngredientController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('pizzas', PizzaController::class);
Route::post('pizzas/{id}/addIngredients', [PizzaController::class, 'addIngredients']);
Route::get('pizzas/{id}/ingredients', [PizzaController::class, 'getPizzaIngredients']);
Route::post('pizzas/{id}/ingredients', [PizzaController::class, 'addIngredients']);
Route::put('pizzas/{id}/reorderIngredient', [PizzaController::class, 'reorderIngredient']);
Route::put('pizzas/{id}/removeIngredient', [PizzaController::class, 'removeIngredient']);

Route::apiResource('ingredients', IngredientController::class);
Route::get('ingredients/{id}', [IngredientController::class, 'show']);
Route::put('ingredients/{id}/change', [IngredientController::class, 'updateIn']);
// Route::put('ingredients/{id}/remove', [IngredientController::class, 'removeIngredient']);
