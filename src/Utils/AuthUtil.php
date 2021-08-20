<?php

namespace FeishuSDK\Utils;

class AuthUtil
{
    public static function gotoLoginPage() {
        $query = http_build_query([
            'app_id' => config('feishu.appId'),
            'redirect_uri' => rtrim(config('feishu.redict_url'), '/') . '/api/feishu/code2session'
        ]);

        return redirect('https://open.feishu.cn/open-apis/authen/v1/index?'.urlencode($query));
    }
}
