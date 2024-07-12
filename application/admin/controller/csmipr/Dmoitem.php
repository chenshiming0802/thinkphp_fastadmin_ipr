<?php

namespace app\admin\controller\csmipr;

use app\common\controller\Backend;
use addons\csmipr\library\xcore\xcore\utils\XcAdminTabUtils;
use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;

/**
 * DEMO明细
 *
 * @icon fa fa-circle-o
 */
class Dmoitem extends Backend
{

    /**
     * Dmoitem模型对象
     * @var \app\admin\model\csmipr\Dmoitem
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\csmipr\Dmoitem;
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());

        // csmipr:设置页面tabs
        $xc_tabid = XcRequestUtils::get("xc_tabid");
        XcAdminTabUtils::append_control_initialize('dmo', $xc_tabid, __CLASS__, $this->view);
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if (false === $this->request->isAjax()) {
            return $this->view->fetch();
        }
        //如果发送的来源是 Selectpage，则转发到 Selectpage
        if ($this->request->request('keyField')) {
            return $this->selectpage();
        }
        [$where, $sort, $order, $offset, $limit] = $this->buildparams();

        $list = $this->model
            ->where($where)
            ->where('csmipr_dmo_id', '=', $this->request->request("xc_tabid")) //xpframwork
            ->where('status','=','normal')
            ->order($sort, $order)
            ->paginate($limit);
        $result = ['total' => $list->total(), 'rows' => $list->items()];
        return json($result);
    }
}
