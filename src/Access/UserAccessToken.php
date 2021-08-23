<?php

namespace FeishuSDK\Access;

use FeishuSDK\Http\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class UserAccessToken
{
    const PREFIX = 'feishu.user_access_token.';

    public static function checkToken($openId, $token) {
        $data = Redis::get(self::PREFIX . $openId);

        if ($data) {
            $data = json_decode($data, true);
        }

        if (!$data || Arr::get($data, 'access_token') != $token) {
            throw new \Exception('登录失效');
        }
        // 判断时效
        if (Arr::get($data, 'expire_time') <= time()) {
            // token失效,去刷新
            if (Arr::get($data, 'refresh_expire_time') <= time()) {
                throw new \Exception('登录失效');
            }
            return static::refreshToken(Arr::get($data, 'refresh_token'));
        }

        return true;
    }

    public static function getToken(string $code) {
        $response = Client::getInstance()
            ->sendWithAppToken('https://open.feishu.cn/open-apis/authen/v1/access_token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
            ]);

        if (Arr::get($response, 'code') !== 0) {
            Log::error('获取用户user_access_token失败', $response);
            return null;
        }

        return Arr::get($response, 'data');
    }

    public static function refreshToken(string $refreshToken) {
        $response = Client::getInstance()
            ->sendWithAppToken('https://open.feishu.cn/open-apis/authen/v1/refresh_access_token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ]);

        if (Arr::get($response, 'code') !== 0) {
            Log::error('刷新用户user_access_token失败', $response);
            return null;
        }

        $data = Arr::get($response, 'data');

        static::setToken($data);

        return Arr::get($data, 'access_token');
    }

    public static function setToken(array $data) {
        Arr::set($data, 'expire_time', time() + Arr::get($data, 'expires_in'));
        Arr::set($data, 'refresh_expire_time', time() + Arr::get($data, 'refresh_expires_in'));

        Redis::set(self::PREFIX . Arr::get($data, 'open_id'), json_encode($data));
        return $data;
    }
}
