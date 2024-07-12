<?php

namespace addons\csmipr\library\xcore\xcore\utils;

use app\common\model\User;
use app\common\library\Token;
use app\admin\model\csmipr\Cloginlog;
use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;
use app\common\library\Auth;

/**
 * SessionUser的工具封装类
 */
class XcUserSessionUtils
{
    public static function getUserId($error = true)
    {

        //XcAssertUtils::error("need_login");
        $auth = Auth::instance();
        $user = $auth->getUser();
        if ($user != null && !empty($user->id)) {
            return $user->id;
        }

        // return 1;
        $token = XcRequestUtils::header("clogintoken");

        if (empty($token)) {
            $token = XcRequestUtils::post("clogintoken");
        }

        $data = Token::get($token);
        if ($data != null && isset($data['user_id'])) {
            return $data['user_id'];
        }

        if ($error === true)
            XcResponseUtils::error("need_login");
    }

    public static function getUserinfo()
    {
        $userid = static::getUserId();
        $user = User::get($userid);
        return $user;
    }

    public static function getUserNickName()
    {
        $user = static::getUserinfo();
        return $user->nickname;
    }

    public static function savelog($operate, $object_id, $content = null)
    {
        $user_id = static::getUserId();
        return static::savelog_unlogined($operate, $user_id, $object_id, $content);
    }

    public static function savelog_unlogined($operate, $user_id, $object_id, $content = null)
    {
        $port = 21;
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MicroMessenger/', $_SERVER['HTTP_USER_AGENT'])) {
            $port = 11;
        } else if (preg_match('/Electron/', $_SERVER['HTTP_USER_AGENT'])) {
            $port = 22;
        }

        $row = null;
        if (true) {
            $dao = new Cloginlog();
            $row = $dao->create([
                "user_id" => $user_id,
                "operate" => $operate,
                "port" => $port,
                "object_id" => $object_id,
                "createtime" => time(),
                "cyear" => date('Y'),
                "cmonth" => date('m'),
                "cdate" => date('d'),
                "cweek" => date("w"),
                "chour" => date("H"),
                "cmin" => date("i"),
                "content" => $content
            ]);
        }
        return $row;
    }

    public static function savelog_after_response($logRow, $recontent)
    {
        $logRow->save([
            "recontent" => $recontent,
            "septime" => (time() - $logRow->createtime)
        ]);
    }
}
