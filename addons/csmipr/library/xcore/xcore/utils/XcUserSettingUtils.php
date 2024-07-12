<?php

namespace addons\csmipr\library\xcore\xcore\utils;


class XcUserSettingUtils
{


    public static function getUserSettingValue($user_id, $key)
    {
        $tt = static::getUserSettingValues($user_id, [$key]);
        return $tt[$key];
    }
    public static function getUserSettingValues($user_id, $keys)
    {
        $row = XcDaoUtils::getRowBySql(new \app\admin\model\csmipr\Clogininfo(), [
            ['user_id', '=', $user_id]
        ], null, true, false);

        // $defaultKeyValues = CsmContants::$ADDONS_CONFIG_CLOGININFO_DEFAULT;
        $defaultKeyValues = XcConfigUtils::xpconfig("XcUserSettingUtils.defaultsetting");

        $vals = [];
        if ($row != null && !empty($row->settingjson)) {
            $settingjson = json_decode($row->settingjson, true);
            foreach ($keys as $key) {
                $vals[$key] = isset($settingjson[$key]) ? $settingjson[$key] : $defaultKeyValues[$key];
            }
        } else {
            // default if usersetting is null
            foreach ($keys as $key) {
                $vals[$key] = isset($defaultKeyValues[$key]) ? $defaultKeyValues[$key] : null;
            }
        }
        return $vals;
    }
}
