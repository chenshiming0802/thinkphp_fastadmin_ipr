<?php

namespace app\api\controller\csmipr;

use addons\csmipr\library\xcore\xcore\base\XcAApi;
use addons\csmipr\library\xcore\xcore\utils\XcDaoUtils;




/**
 * APP首页的请求数据
 */
class XpDmo extends XcAApi
{
    public function xinit()
    {
        $this->model = new \app\admin\model\csmipr\Dmo();
    }

    public function queryList()
    {

        $buildparams = $this->buildparams();
        [$list, $total] = $this->_queryList($buildparams);
        XcDaoUtils::bindDbListColumn($list,"csmipr_dmocategory_id",new \app\admin\model\csmipr\Dmocategory(),'dmocategory',["name"]);
        XcDaoUtils::bindDbListMultiColumn($list,"csmipr_dmocategory_ids",new \app\admin\model\csmipr\Dmocategory(),'dmocategorys','name');
        $this->success(null, ['list' => $list]);
    }
}
