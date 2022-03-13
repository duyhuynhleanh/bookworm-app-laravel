<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;


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


/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
*/

Route::post('users/login', [UserController::class, 'authUser']);
Route::post('users/register', [UserController::class, 'registerUser']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('users/profile', [UserController::class, 'updateUserProfile']);
});




/*
|--------------------------------------------------------------------------
| Product API Routes
|--------------------------------------------------------------------------
*/

Route::get('products/topdiscount', [ProductController::class, 'getTopDiscountProducts']);
Route::get('/search',[ProductController::class,'search']);
Route::get('products/toprecommend', [ProductController::class, 'getTopRecommendProducts']);
Route::get('products/toppopular', [ProductController::class, 'getTopPopularProducts']);
Route::get('categories', [ProductController::class, 'getAllCategories']);
Route::get('authors', [ProductController::class, 'getAllAuthors']);
Route::post('products/{id}/reviews', [ProductController::class, 'createProductReview']);
Route::get('products/{id}', [ProductController::class, 'getProductById']);
