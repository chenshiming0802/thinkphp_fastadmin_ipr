<?php

namespace app\api\controller\csmipr;

use ReflectionClass;
use addons\csmipr\library\xcore\xcore\base\XcAMyAppyApi;
use addons\csmipr\library\xcore\xcore\utils\XcDaoUtils;

/**
 * APP首页的请求数据
 */
class XpmyDmoapply extends XcAMyAppyApi //XcMyApi
{
    protected $fields = ["id", "name", "csmipr_dmocategory_id", "csmipr_dmocategory_ids", "type", "types", "content", "bannerimage", "images", "status", "user_id", "createtime", "updatetime"];
    protected $dictfields = ["status", "type", "types"];

    public function xinit()
    {
        $this->model = new \app\admin\model\csmipr\Dmoapply();

        
    }



    public function queryList()
    {

        $buildparams = $this->buildparams();
        [$list, $total] = $this->_queryList($buildparams);
        XcDaoUtils::bindDbListColumn($list, "csmipr_dmocategory_id", new \app\admin\model\csmipr\Dmocategory(), 'dmocategory', ["name"]);
        XcDaoUtils::bindDbListMultiColumn($list, "csmipr_dmocategory_ids", new \app\admin\model\csmipr\Dmocategory(), 'dmocategorys', 'name');
        $this->success(null, ['list' => $list]);
    }

}
