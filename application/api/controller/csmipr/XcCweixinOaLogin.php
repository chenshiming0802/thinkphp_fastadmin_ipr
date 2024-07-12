<?php

namespace app\api\controller\csmipr;

use EasyWeChat\Factory;
use addons\csmipr\library\xcore\xcore\base\XcAApi;
use addons\csmipr\library\xcore\xcore\utils\XcConfigUtils;
use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;
use addons\csmipr\library\xcore\xcore\utils\XcUserCloginThirdUtils;

/**
 * 微信登录支持
 * mp：微信小程序
 * oa：微信公众号
 */
class XcCweixinOaLogin extends XcAApi
{
    protected $model = null;
    protected $config = null;
    protected $user_fields = "id,username,nickname,email,mobile,avatar,jointime";

    public function xinit()
    {
        $this->model = new \app\admin\model\csmipr\Cloginthird();

        $this->config = [
            'app_id' => XcConfigUtils::config('cloginwxoaappid'),
            'secret' => XcConfigUtils::config('cloginwxoaappsecret'),
            'response_type' => 'array',
        ];
    }

    public function login()
    {
        session_start();
        $targetUrl = XcRequestUtils::get("targetUrl");
        $_SESSION['XcCweixinOaLogin_targetUrl'] = $targetUrl;

        $this->config['oauth'] = [
            'scopes'   => ['snsapi_base'],
            'callback' => XcConfigUtils::config("xcbaseurl") . "/api/" . XcConfigUtils::xpconfig('addons_code') . '/xc_cweixin_oa_login/callback',
        ];

        $app = Factory::officialAccount($this->config);
        $oauth = $app->oauth;

        $redirectUrl = $oauth->redirect();

        header("Location: {$redirectUrl}");
    }

    public function callback()
    {
        session_start();        
        $code = XcRequestUtils::get("code");

        $app = Factory::officialAccount($this->config);
        $oauth = $app->oauth->scopes(['snsapi_base']);
        $wxuser = $oauth->userFromCode($code);
        var_dump($wxuser);
        if ($wxuser != null && $wxuser->getId() != null) {
            $openid = $wxuser->getId();
            $user_id = XcUserCloginThirdUtils::registerIFNotExist('weixin_oa', $this->config['app_id'], $openid, null, null, '微信用户');
        } else {
            $this->error('微信免登录失败');
        }

        $token = null;
        if (true) {
            $this->auth->direct($user_id);
            $token = $this->auth->getToken();
        }

        $targetUrl = $_SESSION['XcCweixinOaLogin_targetUrl'];
        header("Location: {$targetUrl}?clogintoken={$token}");
    }
}
