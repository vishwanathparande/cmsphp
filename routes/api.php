<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->group(function () {
    Route::post('user/logout', 'UserController@logout');
    Route::post('user/getInfo', 'UserController@getUserInfo');
    Route::post('user/getProducts', 'UserController@getProducts');
    Route::post('user/saveCart', 'UserController@saveCart');
    Route::post('user/getCart', 'UserController@getCart');
    Route::post('user/updateCart', 'UserController@updateCart');
    Route::post('user/placeOrder', 'UserController@placeOrder');
});

Route::post('user/register', 'UserController@register');
Route::post('user/login', 'UserController@login');
