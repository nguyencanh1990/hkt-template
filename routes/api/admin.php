<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Admins
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'cors'], function () {
    /*
    * API with Auth
    */
    Route::group(['middleware' => ['auth:user']], function () {
        // Update must be use Post method because maybe upload file

    });

    /*
     * API No Auth
    */

    Route::group(['prefix' => 'users'], function () {
        Route::apiResource('/', 'UserController')->only(['index', 'store', 'show', 'update']);
    });
});
