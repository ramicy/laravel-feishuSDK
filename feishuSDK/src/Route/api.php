<?php

Route::group(['middleware' => ['api'], 'prefix' => 'api'],function () {
    Route::get('feishu/code2session', 'FeishuSDK\Controller\AuthController@login');
});
