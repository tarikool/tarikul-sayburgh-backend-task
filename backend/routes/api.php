<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
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


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::get('logout', [AuthController::class, 'logout']);
    Route::apiResource('blogs', BlogController::class)
        ->except(['index', 'show']);

    Route::get('blogs/{blog}/comments', [BlogController::class, 'blogComments']);
    Route::post('blogs/{blog}/comments', [BlogController::class, 'postComment']);
    Route::post('update-comments/{comment}', [BlogController::class, 'updateComment']);
    Route::get('delete-comments/{comment}', [BlogController::class, 'deleteComment']);

});


Route::get('blogs', [BlogController::class, 'index']);
Route::get('blogs/{blog}', [BlogController::class, 'show']);

