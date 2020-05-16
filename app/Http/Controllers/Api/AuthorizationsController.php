<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

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
        //获取最后一次的token并加入黑名单
        $user=\Auth::guard('api')->user();
        if ($user->last_token) {
            try{
                \JWTAuth::setToken($user->last_token)->invalidate();
            }catch (TokenExpiredException $e){
                //因为让一个过期的token再失效，会抛出异常，所以我们捕捉异常，不需要做任何处理
            }
        }
        $user->last_token = $token;
        $user->save();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ])->setStatusCode(201);
    }

    public function userInfo(){
        return 1;
    }
}
