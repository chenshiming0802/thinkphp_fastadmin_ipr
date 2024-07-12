<?php

namespace addons\csmipr\library\xcore\xcore\utils\sdk;

use addons\csmipr\library\xcore\xcore\utils\XcConfigUtils;
use cn\sdnode\xstack\utils\XsHttpUtils;

/**
 * Fastadmin的工具封装类
 */
class XcGetuiUtils
{
    // https://dev.dcloud.net.cn/pages/app/push/index
    // https://uniapp.dcloud.net.cn/unipush-v1.html
    // https://docs.getui.com/getui/server/rest_v2/token/
    public static function token()
    {
        // $CC = CsmContants::$CONFIG_GETUI;
        $CC = XcConfigUtils::xpconfig("XcGetuiUtils.config");
        $appID =  $CC['appID'];

        $url = "https://restapi.getui.com/v2/{$appID}/auth";
        $timestamp = time() . '000';
        $data = [
            "sign" => hash('sha256', $CC['appKey'] . $timestamp . $CC['masterSecret']),
            "timestamp" => $timestamp,
            "appkey" => $CC['appKey']
        ];
        $data = json_encode($data);
        $sr = XsHttpUtils::postjson($url, $data);
        $sr = json_decode($sr, true);
        if ($sr != null  && $sr['code'] == 0) {
            return $sr['data']['token'];
        } else {
            throw new \Exception($sr);
        }
    }

    public static function sendSignle($cids, $title, $body, $payloadJson)
    {
        $urlencode_payload = urlencode(json_encode($payloadJson));

        $CC = XcConfigUtils::xpconfig("XcGetuiUtils.config");
        $appID =  $CC['appID'];

        $url = "https://restapi.getui.com/v2/{$appID}/push/single/cid";

        $data = [
            'request_id' => time(),
            'settings' => [
                'ttl' => 7200000,
            ],
            'audience' => [
                'cid' => $cids
            ],
            'push_channel' => [
                'android' => [
                    'ups' => [
                        'notification' => [
                            "title" => $title,
                            "body" => $body,
                            "click_type" => "intent",
                            "intent" => "intent://io.dcloud.unipush/?#Intent;scheme=unipush;launchFlags=0x4000000;component=com.sytask.mobile/io.dcloud.PandoraEntry;S.UP-OL-SU=true;S.payload={$urlencode_payload};end"
                        ]
                    ]
                ],
                'ios' => [
                    'type' => 'notify',
                    'payload' => json_encode($payloadJson),
                    'aps' => [
                        'alert' => [
                            "title" => $title,
                            "body" => $body,
                        ],
                        "content-available" => 0
                    ],
                    "auto_badge" => "+1"
                ],
            ],
            'push_message' => [
                'notification' => [
                    "title" => $title,
                    "body" => $body,
                    "click_type" => "intent",
                    "intent" => "intent://io.dcloud.unipush/?#Intent;scheme=unipush;launchFlags=0x4000000;component=com.sytask.mobile/io.dcloud.PandoraEntry;S.UP-OL-SU=true;S.payload={$urlencode_payload};end"
                ]
            ]
        ];
        $data = json_encode($data);
        $token = static::token();

        $sr = XsHttpUtils::postjson($url, $data, ["token: {$token}"]);
        $sr = json_decode($sr, true);

        if ($sr != null && $sr['code'] == 0) {
            return true;
        } else {
            throw new \Exception(json_encode($sr));
        }
    }
}
