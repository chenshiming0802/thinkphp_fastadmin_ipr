<?php
// +----------------------------------------------------------------------
// | CSMIPR  [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024 https://www.fastadmin.net All rights reserved.
// +----------------------------------------------------------------------
// | Author:chenshiming0802
// +----------------------------------------------------------------------

namespace app\admin\controller\csmipr;

use addons\csmipr\library\xcore\xcore\utils\XcDaoUtils;
use app\common\controller\Backend;

/**
 * 商标
 *
 * @icon fa fa-circle-o
 */
class Shangbiao extends Backend
{

    /**
     * Shangbiao模型对象
     * @var \app\admin\model\csmipr\Shangbiao
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\csmipr\Shangbiao;
        $this->assign("isgongyuList", $this->model->getIsgongyuList());
        $this->assign("hastuanList", $this->model->getHastuanList());
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


    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                    
                    ->where($where)
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                $row->visible(['id','name','tuan_image','zhucehao','isgongyu','shenqingren','liuchegnzhuagntai_id']);
                
            }

            $listrows = $list->items();
            XcDaoUtils::bindDbListColumn($listrows,"fenlei_id",new \app\admin\model\csmipr\ShangbiaoFenlei(),'fenlei',['name']);

            $result = array("total" => $list->total(), "rows" => $listrows);

            return json($result);
        }
        return $this->view->fetch();
    }

}