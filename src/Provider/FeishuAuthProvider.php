<?php

namespace FeishuSDK\Provider;

use FeishuSDK\FeishuAuth;
use Illuminate\Support\ServiceProvider;

class FeishuAuthProvider extends ServiceProvider
{
    /**
     * 在注册后启动服务
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__ . '/../Config/feishu.php') => config_path('feishu.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../Route/api.php');
    }

    /**
     * 在容器中注册绑定
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('FeishuAuth', function () {
            return new FeishuAuth();
        });
    }
}
