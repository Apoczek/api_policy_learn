<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/profile', [AuthController::class, 'profile']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/posts', [\App\Http\Controllers\PostController::class, 'index']);
    Route::get('/post/{post}', [\App\Http\Controllers\PostController::class, 'show']);
    Route::patch('/post/{post}', [\App\Http\Controllers\PostController::class, 'update']);
    Route::post('/post', [\App\Http\Controllers\PostController::class, 'store']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
