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

    Route::post('login', 'AuthorizationsController@login');//登陆
    Route::group([
        'middleware' => 'auth:api',
        'prefix' => 'auth'
    ], function ($router) {
        Route::post('logout', 'AuthorizationsController@logout');//退出登陆
        Route::post('user_info', 'AuthorizationsController@userInfo');//用户信息
    });
    Route::middleware('auth:api')->group(function ($router) {
        //这里存放需要通过验证的路由
    });
});
