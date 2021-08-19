<?php

namespace FeishuSDK\Utils;

use FeishuSDK\Http\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class GroupUtil
{
    protected $client;

    public function __construct() {
        $this->client = Client::getInstance();
    }

    public function createGroup($name, $desc = '') {
        $url = 'https://open.feishu.cn/open-apis/im/v1/chats';
        $response = $this->client->sendWithAppToken($url, [
            'name' => $name,
            'description' => $desc,
            'chat_mode' => 'group',
            'chat_type' => 'private',
            'join_message_visibility' => 'all_members',
            'leave_message_visibility' => 'all_members',
            'membership_approval' => 'no_approval_required', // 加群审批
        ]);

        if (Arr::get($response, 'code') != 0) {
            Log::error('创建群失败', $response);
            return '';
        }

        return Arr::get($response, 'data.chat_id');
    }

    public function deleteGroup($chatId) {
        $url = 'https://open.feishu.cn/open-apis/im/v1/chats/'.$chatId;

        $response = $this->client->sendWithAppToken($url, [], 'delete');

        return Arr::get($response, 'msg');
    }

    public function groupMembers(string $chatId) {
        $url = "https://open.feishu.cn/open-apis/im/v1/chats/{$chatId}/members";
        $response = $this->client->sendWithAppToken($url, [], 'get');

        return Arr::get($response, 'data');
    }

    public function isInGroup(string $chatId) {
        $url = "https://open.feishu.cn/open-apis/im/v1/chats/{$chatId}/members/is_in_chat";

        $response = $this->client->send($url, [], 'get');

        if (Arr::get($response, 'code') != 0) {
            Log::error('判断用户或机器人是否在群里失败', $response);
            return false;
        }

        return Arr::get($response, 'data.is_in_chat');
    }
}
