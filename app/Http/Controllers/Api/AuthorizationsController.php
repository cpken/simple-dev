<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorizationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => '账号或者密码不正确'
            ], 401);

        $user = $request->user();

        //生成token
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
}
