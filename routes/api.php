<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OAuthController;
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


Route::middleware(['category-auth'])->group(function() {
    Route::post('/categories',[CategoriesController::class,'store']);
    Route::get('/categories', [CategoriesController::class, 'showAll']);
    Route::put('/categories/{id}', [CategoriesController::class, 'update']);
    Route::delete('/categories/{id}', [CategoriesController::class, 'delete']);
});


Route::middleware(['product-auth'])->group(function() {
    Route::post('/products',[ProductController::class,'store']);
    Route::get('/products', [ProductController::class, 'showAll']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'delete']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


Route::group(['middleware' => ['web']], function () {
    Route::get('/oauth/register', [OAuthController::class, 'redirect']);
    Route::get('/auth/google/callback', [OAuthController::class, 'callback']);
});