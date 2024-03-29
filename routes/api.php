<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController as V1AuthController;
use App\Http\Controllers\Api\V1\BlogController as V1BlogController;

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

Route::middleware('jwt.auth')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {
    Route::group(['prefix' => '/auth', 'as' => 'auth.'], function () {
        Route::post('register', [V1AuthController::class, 'register']);
        Route::post('login', [V1AuthController::class, 'login']);
    });

    Route::group(['prefix' => '/blog', 'as' => 'blog.'], function () {
        Route::group(['middleware' => 'jwt'], function () { //jwt.auth

            Route::get('get', [V1BlogController::class, 'index']);
            Route::post('create', [V1BlogController::class, 'store']);
            Route::put('update/{id}', [V1BlogController::class, 'update']);
            Route::delete('delete/{id}', [V1BlogController::class, 'destroy']);
        });

    });
});

