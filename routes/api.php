<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TaskController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('tasks/overtime', [TaskController::class, 'overtime']);
Route::apiResource('users', UserController::class);
Route::apiResource('tasks', TaskController::class);

Route::get('tasks/{userId}/notices', [TaskController::class, 'notices']);
Route::post('tasks/{taskId}/done', [TaskController::class, 'done']);
