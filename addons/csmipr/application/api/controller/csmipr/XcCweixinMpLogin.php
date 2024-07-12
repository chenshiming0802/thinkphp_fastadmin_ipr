<?php

namespace app\api\controller\csmipr;

use fast\Random;
use EasyWeChat\Factory;
use addons\csmipr\library\xcore\xcore\base\XcAApi;
use addons\csmipr\library\xcore\xcore\utils\XcDaoUtils;
use addons\csmipr\library\xcore\xcore\utils\XcConfigUtils;
use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;
use addons\csmipr\library\xcore\xcore\utils\XcUserCloginThirdUtils;
use addons\csmipr\library\xcore\xcore\utils\XcUserSessionUtils;

/**
 * 微信登录支持
 * mp：微信小程序
 * oa：微信公众号
 */
class XcCweixinMpLogin extends XcAApi
{
    protected $model = null;
    protected $config = null;
    protected $user_fields = "id,username,nickname,email,mobile,avatar,jointime";

    public function xinit()
    {
        $this->model = new \app\admin\model\csmipr\Cloginthird();

        $this->config = [
            'app_id' => XcConfigUtils::config('cloginwxmpappid'),
            'secret' => XcConfigUtils::config('cloginwxmpappsecret'),
            'response_type' => 'array',
        ];
    }

    public function loginOpenid()
    {
        $code = XcRequestUtils::post("code");

        $app = Factory::miniProgram($this->config);
        $wx_sessions = $app->auth->session($code);

        $openid = $wx_sessions['openid'];
        $unionid = isset($wx_sessions['unionid']) ? $wx_sessions['unionid'] : "";

        $user_id = XcUserCloginThirdUtils::registerIFNotExist('weixin_mp', $this->config['app_id'], $openid, $unionid, null, '微信用户');

        $token = null;
        if (true) {
            $this->auth->direct($user_id);
            $token = $this->auth->getToken();
        }

        $user['mp_unionid'] = $unionid;
        $user['mp_openid'] = $openid;
        $this->success(null, ['user' => $user, "openid" => $openid, "token" => $token]);
    }

    // public function loginOpenid()
    // {
    //     $code = XcRequestUtils::post("code");

    //     $app = Factory::miniProgram($this->config);
    //     $wx_sessions = $app->auth->session($code);

    //     $openid = $wx_sessions['openid'];
    //     $unionid = isset($wx_sessions['unionid']) ? $wx_sessions['unionid'] : "";
    //     $session_key = $wx_sessions['session_key'];

    //     $cloginthird = null;
    //     if (true) {
    //         $cloginthird = XcDaoUtils::getRowBySql($this->model, [
    //             ["openid", '=', $openid]
    //         ], null, true, false);
    //         if ($cloginthird == null) {
    //             $cloginthird = $this->model->create([
    //                 "platform" => "weixin",
    //                 "platformid" => $this->config['app_id'],
    //                 "unionid" => $unionid,
    //                 "openid" => $openid,
    //                 "access_token" => $session_key
    //             ]);
    //         } else {
    //             $cloginthird->save([
    //                 "access_token" => $session_key,
    //                 "unionid" => $unionid,
    //             ]);
    //         }
    //     }

    //     $user = null;
    //     if (!empty($cloginthird->user_id) && $cloginthird->user_id != 0) {
    //         $user = XcDaoUtils::getRowById(new \app\admin\model\User(), $cloginthird->user_id, true, false, $this->user_fields);
    //     } else {
    //         $auth = \app\common\library\Auth::instance();
    //         $auth->register($openid, Random::alnum(5), null, null, ['nickname' => '微信用户' . $cloginthird->id]);
    //         $user = XcDaoUtils::getRowBySql(new \app\admin\model\User(), [
    //             ["username", '=', $openid],
    //         ], $this->user_fields);
    //         $cloginthird->save([
    //             'user_id' => $user->id
    //         ]);
    //     }

    //     $token = null;
    //     if (true) {
    //         $this->auth->direct($user->id);
    //         $token = $this->auth->getToken();
    //     }

    //     $user['mp_unionid'] = $cloginthird->unionid;
    //     $user['mp_openid'] = $cloginthird->openid;
    //     $this->success(null, ['user' => $user, "openid" => $openid, "token" => $token]);
    // }

    public function loginMobile()
    {
        $code = XcRequestUtils::post("code");
        $app = Factory::miniProgram($this->config);
        $result = $app->phone_number->getUserPhoneNumber($code);

        if ($result['errcode'] != 0) {
            $this->error($result['errmsg']);
        } else {
            $user = XcUserCloginThirdUtils::updateMobile(XcUserSessionUtils::getUserId(),$result['phone_info']['purePhoneNumber']);

            $token = null;
            if (true) {
                $this->auth->direct($user->id);
                $token = $this->auth->getToken();
            }

            $this->success(null, [
                'token' => $token,
                'user' => $user,
            ]);
        }
    }
}
