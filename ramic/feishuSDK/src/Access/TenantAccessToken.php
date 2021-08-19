<?php

namespace FeishuSDK\Access;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TenantAccessToken extends AccessToken
{
    public static function getToken()
    {
        return Cache::get('feishu.tenant_access_token', function () {

            $response = Http::post('https://open.feishu.cn/open-apis/auth/v3/tenant_access_token/internal', [
                'app_id' => config('feishu.appId'),
                'app_secret' => config('feishu.appSecret'),
            ])->json();

            if (Arr::get($response, 'code') !== 0) {
                Log::error('获取飞书tenant_access_token失败', $response);
                return null;
            }

            $token = Arr::get($response, 'tenant_access_token');

            Cache::put('feishu.tenant_access_token', $token, Arr::get($response, 'expire', 7200) - 300);

            return $token;
        });
    }
}
