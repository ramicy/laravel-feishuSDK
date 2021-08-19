<?php

namespace FeishuSDK\Facades;

use Illuminate\Support\Facades\Facade;

class FeishuAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'feishu-auth';
    }
}
