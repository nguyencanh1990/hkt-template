<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
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

Route::prefix('{model}')->group(function() {
  Route::get('/', [BaseController::class, 'index'])->name('item.list');
  // Route::post('/', 'Api\BaseController@store')->name('item.store');
  // Route::get('/{id}', 'Api\BaseController@show')->name('item.show');
  // Route::post('/{id}', 'Api\BaseController@update')->name('item.update');
  // Route::delete('/{id}', 'Api\BaseController@delete')->name('item.delete');
});