<?php

namespace addons\csmipr\library\xcore\xcore\utils;

use Exception;
use cn\sdnode\xstack\utils\XsHttpUtils;
use cn\sdnode\xstack\utils\XsJsonUtils;

/**
 * 接口调用的工具封装类
 */
class XcInterfaceUtils
{
    public static function httpGet($url)
    {
        $dao = new \app\admin\model\csmipr\Commoninterfacelog();
        $row = $dao->create([
            'methodtype' => 'get',
            'createtime' => time(),
            'url' => $url,
        ]);

        $isSuccess = null;
        $request_result = null;
        try {
            $request_result = XsHttpUtils::get($url);
            $isSuccess = "Y";
        } catch (Exception $e) {
            $isSuccess = "N";
        }

        $row->save([
            'request_result' => $request_result,
            'costsecond' => (time() - $row->createtime),
            'issuccess' => $isSuccess
        ]);

        return $request_result;
    }

    public static function httpPost($url, $param)
    {
        $dao = new \app\admin\model\csmipr\Commoninterfacelog();
        $row = $dao->create([
            'methodtype' => 'post',
            'request_post' => XsJsonUtils::jsonToString($param),
            'createtime' => time(),
            'url' => $url,
        ]);

        $isSuccess = null;
        $request_result = null;
        try {
            $request_result = XsHttpUtils::post($url, $param);
            $isSuccess = "Y";
        } catch (Exception $e) {
            $isSuccess = "N";
        }

        $row->save([
            'request_result' => $request_result,
            'costsecond' => (time() - $row->createtime),
            'issuccess' => $isSuccess
        ]);

        return $request_result;
    }

    public static function httpPostJson($url, $json)
    {
        $dao = new \app\admin\model\csmipr\Commoninterfacelog();
        $row = $dao->create([
            'methodtype' => 'postjson',
            'request_post' => XsJsonUtils::jsonToString($json),
            'createtime' => time(),
            'url' => $url,
        ]);

        $isSuccess = null;
        $request_result = null;
        try {
            $request_result = XsHttpUtils::postJson($url, $json);
            $isSuccess = "Y";
        } catch (Exception $e) {
            $isSuccess = "N";
        }

        $row->save([
            'request_result' => $request_result,
            'costsecond' => (time() - $row->createtime),
            'issuccess' => $isSuccess
        ]);

        return $request_result;
    }
}
