<?php

namespace app\api\controller\csmipr;

use addons\csmipr\library\xcore\xcore\base\XcAMyApi;
use addons\csmipr\library\xcore\xcore\utils\XcDaoUtils;
use addons\csmipr\library\xcore\xcore\utils\XcConfigUtils;
use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;

/**
 * APP首页的请求数据
 */
class XcmyClogininfo extends XcAMyApi
{

    protected $fields = [
        "id",
        "user_id",
        "settingjson",
    ];
    protected $createFields = [
        "settingjson",
    ];

    public function xinit()
    {
        $this->model = new \app\admin\model\csmipr\Clogininfo();
    }

    public function create()
    {
    }
    public function update()
    {
    }

    public function gets()
    {
        $keys = explode(',', XcRequestUtils::get('keys'));
        
        // $vals = XcCloginUtils::getUserSettingValues($this->sessionUserId,$keys);
        $vals = [];
        foreach($keys as $key){
            $vals[] = XcConfigUtils::xpconfig($keys);
        }

        $this->success(null, ['vals' => $vals]);
    }
    
    public function save()
    {
        $setting_key = XcRequestUtils::post('setting_key');
        $setting_value = XcRequestUtils::post('setting_value');
        $setting_value_array = XcRequestUtils::post('setting_value_array');

        if (!empty($setting_value_array)) {
            $setting_value = (empty($setting_value_array) || $setting_value_array == '') ? [] : explode(",", $setting_value_array);
        }
        $setting_value = (empty($setting_value))?[]:$setting_value;

        $this->_save($setting_key, $setting_value);

        $this->success();
    }

    private function _save($setting_key, $setting_value)
    {

        $row = XcDaoUtils::getRowBySql($this->model, [
            ['user_id', '=', $this->sessionUserId]
        ], null, true, false);

        $settingjson = [];
        if (true) {
            if ($row != null && !empty($row->settingjson)) {
                $settingjson = json_decode($row->settingjson, true);
            }
            $settingjson[$setting_key] = $setting_value;
        }
        if ($row == null) {
            $this->model->create([
                'user_id' => $this->sessionUserId,
                'settingjson' => json_encode($settingjson)
            ]);
        } else {
            $row->save([
                'settingjson' => json_encode($settingjson)
            ]);
        }
    }

    public function delete(){
        if(true){
            $row = XcDaoUtils::getRowById(new \app\admin\model\User(),$this->sessionUserId);
            $row->save([
                'status'=>'hidden',
                'mobile'=>'',
                'username'=>$row->username."-DEL"
            ]);
        }
        if(true){
            $row = XcDaoUtils::getRowBySql($this->model,[
                ['user_id','=',$this->sessionUserId]
            ],null,true,false);
            if($row){
                $row->save([
                    'status'=>'hidden'
                ]);
            }
        }

        $this->success();
    }

 
}
