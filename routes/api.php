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

Route::prefix('v1')->name('api.v1.')->namespace('Api')->group(function() {

    Route::post('login', 'AuthorizationsController@login');
    Route::group([
        'middleware' => 'jwt.auth',
        'prefix' => 'auth'
    ], function ($router) {
        Route::post('logout', 'AuthorizationsController@logout');
        Route::post('user_info', 'AuthorizationsController@userInfo');
    });
    Route::middleware('jwt.auth')->group(function ($router) {
       //这里存放需要通过验证的路由
    });
});
