<?php

namespace addons\csmipr\library\xcore\xcore\utils;

use addons\qcloudsms\library\SmsSingleSender;


class XcUserMesageUtils
{
    public static function sendmessage($mobile, $templateid, $data = [])
    {
        $config = get_addon_config("qcloudsms");
        $sender = new SmsSingleSender($config['appid'], $config['appkey']);
        $result = $sender->sendWithParam("86", $mobile, $templateid, $data, $config['sign'], "", "");
        trace("sendMessage {$mobile} {$templateid}:".json_encode($data));
        return $result;
    }
}
