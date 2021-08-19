<?php

namespace FeishuSDK\Middleware;

use Closure;
use FeishuSDK\Access\UserAccessToken;
use FeishuSDK\Utils\AuthUtil;

class AuthFeishu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('User-Token');
        $openId = $request->header('Open-Id');
        if (!$token || !$openId) {
            return AuthUtil::gotoLoginPage();
        }

        $newToken = UserAccessToken::checkToken($openId, $token);

        $response = $next($request);

        if (is_string($newToken) && $response->getStatusCode() < 500) {
            $content = json_decode($response->getContent(), true);
            $content['token'] = $newToken;
            $response->setContent(json_encode($content));
        }

        return $response;
    }
}
