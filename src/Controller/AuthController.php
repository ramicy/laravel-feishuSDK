<?php

namespace FeishuSDK\Controller;

use Illuminate\Support\Arr;
use Exception;
use FeishuSDK\Access\UserAccessToken;
use Illuminate\Http\Request;

class AuthController
{
    public function login(Request $request) {
        $code = $request->query('code');
        if (!$code) {
            throw new Exception('参数错误,请稍后重试');
        }

        $token = UserAccessToken::getToken($code);

        if (!$token) {
            throw new Exception('登录失败,请稍后重试');
        }

        $data = UserAccessToken::setToken($token);

        return Arr::only($data, ['access_token', 'open_id']);
    }
}
