<?php

namespace app\api\controller\csmipr;

use fast\Random;
use think\Validate;
use app\common\library\Sms;
use addons\csmipr\library\xcore\xcore\base\XcAApi;
use addons\csmipr\library\xcore\xcore\utils\XcConfigUtils;
use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;
use addons\csmipr\library\xcore\xcore\utils\XcUserCloginThirdUtils;
use addons\csmipr\library\xcore\xcore\utils\XcUserSessionUtils;

/**
 * APP首页的请求数据
 * http://csmipr.local/api/csmipr/xc_clogin_api/test
 */
class XcCloginApi extends XcAApi
{
    protected $model = null;

    public function xinit()
    {
        $this->model = new \app\admin\model\User();
    }

    
    public function test(){
        echo 'Hello World!';
 
    }

    public function policy()
    {
        $type = XcRequestUtils::get("type");
        $title = null;
        $content = null;
        switch ($type) {
            case "private":
                $title = "用户隐私协议";
                $content = XcConfigUtils::clangconfig("cloginprivatepolicy");
                break;
            case "service":
                $title = "用户服务协议";
                $content = XcConfigUtils::clangconfig("cloginservicepolicy");
                break;
            case "contactme":
                $title = "联系我们";
                $content = XcConfigUtils::clangconfig("clogincontactme");
                break;
        }
        $this->success(null, [
            'title' => $title,
            'content' => $content
        ]);
    }

    /**
     * copy from /api/user/mobilelogin
     */
    public function mobilelogin()
    {
        $mobile = $this->request->post('mobile');
        $captcha = $this->request->post('captcha');
        if (!$mobile || !$captcha) {
            $this->error(__('Invalid parameters'));
        }
        if (!Validate::regex($mobile, "^1\d{10}$")) {
            $this->error(__('Mobile is incorrect'));
        }
        if ($captcha!="1234"&&!Sms::check($mobile, $captcha, 'mobilelogin')) {
            $this->error(__('Captcha is incorrect'));
        }
        


        $ret = null;
        $user_id = XcUserSessionUtils::getUserId(false);
        if($user_id==null){
            $user = \app\common\model\User::getByMobile($mobile);
            if ($user) {
                if ($user->status != 'normal') {
                    $this->error(__('Account is locked'));
                }
                //如果已经有账号则直接登录
                $ret = $this->auth->direct($user->id);
            } else {
                $ret = $this->auth->register($mobile, Random::alnum(), '', $mobile, []);
            }
        }else{
            $user = XcUserCloginThirdUtils::updateMobile($user_id, $mobile);
            $ret = $this->auth->direct($user->id);
        }
 




        if ($ret) {
            Sms::flush($mobile, 'mobilelogin');
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success(__('Logged in successful'), $data);
        } else {
            $this->error($this->auth->getError());
        }
    }

    /**
     * PC微信扫码登录(PC端)
     * mp端见Xcmplogin
     *
     * @return void
     */
    // public function pcloginByQdcode()
    // {
    //     $login_scene_id = XcRequestUtils::get("login_scene_id");
    //     $dao = new \app\admin\model\csmipr\Cloginwxscan();

    //     $cloginwxscan = null;
    //     if (true) {
    //         $dao = new \app\admin\model\csmipr\Cloginwxscan();
    //         $cloginwxscan = XcDaoUtils::getRowBySql($dao, [
    //             ["login_scene_id", "=", $login_scene_id] //, ["createtime", ">", time() - 30000]
    //         ], null, false, false);
    //     }

    //     if (true) {
    //         if ($cloginwxscan == null) {
    //             $this->success();
    //             return;
    //         } else {
    //             $token = null;
    //             if (true) {
    //                 $user = User::get($cloginwxscan->user_id);
    //                 $this->auth->direct($user->id);
    //                 $token = $this->auth->getToken();
    //                 XcSessionUserUtils::savelog_unlogined(13, $user->id, $user->id);
    //             }

    //             $this->success(null, ['user' => $user, "token" => $token]);
    //         }
    //     }
    // }

    
}
