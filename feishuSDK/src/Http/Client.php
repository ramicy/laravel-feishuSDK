<?php
namespace FeishuSDK\Http;

use Exception;
use FeishuSDK\Access\TenantAccessToken;
use Illuminate\Support\Facades\Http;

class Client
{
    private static $instance;

    private function __construct() {

    }

    public function send($url, $data, $method = 'post') {
        $token = request()->header('User-Token');

        if (!$token) {
            throw new Exception('获取用户token失败');
        }

        return Http::withToken($token)->$method($url, $data)->json();
    }

    public function sendWithAppToken($url, $data, $method = 'post') {
        $token = TenantAccessToken::getToken();

        if (!$token) {
            throw new Exception('获取应用token失败');
        }

        return Http::withToken($token)->$method($url, $data)->json();
    }

    public static function getInstance() {
        if (!static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

}
