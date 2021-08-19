<?php
namespace FeishuSDK\Access;

abstract class AccessToken
{
    protected $appId;

    protected $appSecret;

    abstract static function getToken();
}
