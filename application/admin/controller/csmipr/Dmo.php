<?php
// +----------------------------------------------------------------------
// | XPFRAMEWORK  [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024 https://gitee.com/sdnode All rights reserved.
// +----------------------------------------------------------------------
// | Author: chenshiming
// +----------------------------------------------------------------------

namespace app\admin\controller\csmipr;

use app\common\controller\Backend;

/**
 * DMO
 *
 * @icon fa fa-circle-o
 */
class Dmo extends Backend
{

    /**
     * Dmo模型对象
     * @var \app\admin\model\csmipr\Dmo
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\csmipr\Dmo;
        $this->assign("typeList", $this->model->getTypeList());
        $this->assign("typesList", $this->model->getTypesList());
        $this->assign("isreadList", $this->model->getIsreadList());
        $this->assign("statusList", $this->model->getStatusList());
    }

    public function import()
    {
        parent::import();
    }


    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


}