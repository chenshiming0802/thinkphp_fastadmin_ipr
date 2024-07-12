<?php

namespace addons\csmipr\library\xcore\xcore\utils;

class XcResponseUtils
{
    public static function error($msg = '', $url = null, $data = '', $wait = 999, array $header = [])
    {
        $result = [
            'code' => 0,
            'msg' => $msg,
            'data' => $data,
            'url' => $url,
            'wait' => $wait
        ];
        echo json_encode($result);
        die();
    }
 
}
