<?php

namespace addons\csmipr\library\xcore\xcore\utils;

use think\Hook;
use fast\Random;


class XcUserCloginThirdUtils
{

    public static function registerIFNotExist($platform, $platformid, $openid, $unionid, $mobile, $nickname)
    {
        $dao = new \app\admin\model\csmipr\Cloginthird();
        $userDao = new \app\admin\model\User();

        $cloginthird = XcDaoUtils::getRowBySql($dao, [
            ["platform", '=', $platform], ["platformid", '=', $platformid], ["openid", '=', $openid],
        ]);

        // 对cloginthird创建或修改
        if (!$cloginthird) {
            $cloginthird = $dao->create([
                'platform' => $platform,
                'platformid' => $platformid,
                'openid' => $openid,
                'unionid' => $unionid,
                'purephonenumber' => $mobile,
                'nickname' => $nickname,
                'user_id' => null,
                'createtime' => time(),
            ]);
        } else {
            $cloginthird->save([
                'unionid' => $unionid,
                'purephonenumber' => $mobile,
                'nickname' => $nickname,
                'logintime' => time(),
            ]);
        }

        // cloginthird是否存在对应user表（存在user_id存在但是对应账号已经注销的情况）
        $hasUserRow = false;
        $userRow = null;
        if ($cloginthird != null && $cloginthird->user_id != null) {
            $userRow = XcDaoUtils::getRowById($userDao, $cloginthird->user_id, true, false);
            if ($userRow) {
                $hasUserRow = true;
            }
        }

        $user_id = null;
        if ($hasUserRow === false) {
            // 如果不存在对应user
            $mobileuser = static::_getUserByMobile($mobile);
            if ($mobileuser != null) {
                $cloginthird->save([
                    'user_id' => $mobileuser->id
                ]);
                $user_id = $mobileuser->id;
            } else {
                $auth = \app\common\library\Auth::instance();
                $auth->register($openid, Random::alnum(5), null, null, ['nickname' => $nickname . $cloginthird->id]);
                $user = XcDaoUtils::getRowBySql(new \app\admin\model\User(), [
                    ["username", '=', $openid],
                ]);
                $cloginthird->save([
                    'user_id' => $user->id
                ]);
                $user_id = $user->id;
            }
        } else {
            // 如果fa_user中有其他用户也有这个手机号码，则执行账号合并
            if ($mobile != null && $mobile != '') {
                $mobileuser = static::_getUserByMobile($mobile);
                if ($mobileuser != null && $mobileuser->id != $cloginthird->user_id) {
                    $user_id = static::_mergeUser($userRow->id, $mobileuser->id);
                }
            } else {
                $user_id = $userRow->id;
            }
        }

        return $user_id;
    }

    /**
     * 更新手机，如果匹配到其他人，则执行账号合并
     */
    public static function updateMobile($user_id, $mobile)
    {
        $user = null;

        $mobileuser = static::_getUserByMobile($mobile);
        if ($mobileuser != null && $mobileuser->id != $user_id) {
            $user = static::_mergeUser($user_id, $mobileuser->id);
        } else {
            $user = XcDaoUtils::getRowById(new \app\admin\model\User(), $user_id);
            $user->save([
                'mobile' => $mobile
            ]);
        }
        return $user;
    }

    private static function _getUserByMobile($mobile)
    {
        $userDao = new \app\admin\model\User();
        $mobileuser = XcDaoUtils::getRowBySql($userDao, [
            ['mobile', '=', $mobile]
        ]);
        return $mobileuser;
    }

    private static function _mergeUser($user_id, $mobileuser_id)
    {
        $userDao = new \app\admin\model\User();
        Hook::listen('xp_merge_user', $user_id,$mobileuser_id);

        $mobileuser = XcDaoUtils::getRowById($userDao, $mobileuser_id);
        return $mobileuser;
    }
}
