<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\Api\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\Api\AuthController@logout');
});

Route::group([
    'prefix' => 'user'
], function ($router) {
    Route::post('', 'App\Http\Controllers\Api\UserController@register');
    Route::put('', 'App\Http\Controllers\Api\UserController@update')->middleware('jwt.auth');
    Route::put('confirm-registration', 'App\Http\Controllers\Api\UserController@confirm_registration')->middleware('jwt.auth');
});
