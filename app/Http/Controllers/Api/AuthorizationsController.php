<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorizationRequest;
use Illuminate\Http\Request;

class AuthorizationsController extends Controller
{
    /**
     * @param AuthorizationRequest $request
     * @return array|string[]
     */
    public function login(AuthorizationRequest $request){
        $username = $request->username;

        //php7.0新东西，FILTER_VALIDATE_EMAIL为php自带的email过滤器
        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        //接收传来的值
        $credentials['password']=$request->password;

        //验证密码是否正确
        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            abort('用户账号或密码错误',403);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ])->setStatusCode(201);
    }
}
