<?php

namespace addons\csmipr\library\xcore\xcore\base;

use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;
use addons\csmipr\library\xcore\xcore\utils\XcResponseUtils;

abstract class XcAMyAppyApi  extends XcAMyApi
{
    public function createOrUpdate()
    {
        $params = XcRequestUtils::requestBodyJson();

        if(!in_array($params['status'], ["draft", "toaudit"])){
            XcResponseUtils::error("提交状态异常");
        }

        if (isset($params['id']) && !empty($params['id'])) {
            $row = self::_getById($params['id']);
            if (!in_array($row->status, ["draft", "toaudit"])) {
                XcResponseUtils::error("当前记录不是操作或者待审状态，无法修改");
            }
            
            $row = self::_update($params);
        } else {
            $row = self::_create($params);
        }
  
        $this->success(null, ["row" => $row]);
    }
}
