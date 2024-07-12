<?php

namespace addons\csmipr\library\xcore\xcore\utils;

/**
 * 
 * 具体配置参照: https://doc.fastadmin.net/developer/80.html <BR>
 * 
 * 配置支持datasoure下拉 ,具体示例如下:<BR>
 * [
        'type' => 'string',
        'extend' => 'data-source="auth/admin/selectpage" data-field="nickname" class="form-control selectpage"',
   ],
 */
class XcConfigUtils
{

    public static function config($key)
    {
        $config = get_addon_config(static::xpconfig("addons_code"));
        return isset($config[$key])?$config[$key]:null;
    }

    public static function clangconfig($key)
    {
        $val = XcConfigUtils::config($key);
        $enval = XcConfigUtils::config('en_' . $key);
        return XcLangUtils::getCLangText($val, $enval);
    }

    public static function xpconfig($key)
    {
        $sr = include(__DIR__ . "/../../../../xpconfig.php");
        return isset($sr[$key])?$sr[$key]:null;
    }
}
