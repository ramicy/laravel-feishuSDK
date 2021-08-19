<?php
namespace FeishuSDK;

use FeishuSDK\Http\Client;
use FeishuSDK\Utils\AuthUtil;
use FeishuSDK\Utils\GroupUtil;

class FeishuAuth
{
    protected $groupUtil;

    public function __construct() {
        $this->setGroupUtils(new GroupUtil());
    }

    protected function setGroupUtils(GroupUtil $groupUtils) {
        $this->groupUtil = $groupUtils;
    }

    public function createGroup($name, $desc = '') {
        return $this->groupUtil->createGroup($name, $desc);
    }

    public function deleteGroup($chatId) {
        return $this->groupUtil->deleteGroup($chatId);
    }

    public function groupMembers($chatId) {
        return $this->groupUtil->groupMembers($chatId);
    }

    public function isInGroup(string $chatId) {
        return $this->groupUtil->isInGroup($chatId);
    }

    public function gotoLoginPage() {
        return AuthUtil::gotoLoginPage();
    }
}
