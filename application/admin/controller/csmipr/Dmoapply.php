<?php

namespace app\admin\controller\csmipr;

use think\Db;
use app\common\controller\Backend;
use addons\csmipr\library\xcore\xcore\utils\XcDaoUtils;
use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;
use addons\csmipr\library\xapp\csmipr\utils\XpDmoUtils;

/**
 * DMO申请
 *
 * @icon fa fa-circle-o
 */
class Dmoapply extends Backend
{

    /**
     * Dmoapply模型对象
     * @var \app\admin\model\csmipr\Dmoapply
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\csmipr\Dmoapply;
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("typesList", $this->model->getTypesList());
        $this->view->assign("statusList", $this->model->getStatusList());
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
            ->order($sort, $order)
            ->paginate($limit);

        // csmipr：将List中的xx_id字段转换为对应表显示字段
        XcDaoUtils::bindDbListColumn($list, "csmipr_dmocategory_id", new \app\admin\model\csmipr\Dmocategory(), 'csmipr_dmocategory', ['name']);
        XcDaoUtils::bindDbListMultiColumn($list, "csmipr_dmocategory_ids", new \app\admin\model\csmipr\Dmocategory(), "csmipr_dmocategorys", "name");

        $result = ['total' => $list->total(), 'rows' => $list->items()];
        return json($result);
    }

    public function submitReject()
    {
        $id = XcRequestUtils::post("id", true);
        $auditcontent = XcRequestUtils::post("auditcontent", true);

        $row = XcDaoUtils::getRowById($this->model, $id, false);
        if ($row) {
            $row->save([
                'auditcontent' => $auditcontent,
                'status' => 'reject'
            ]);
            //XcCloginUtils::sendUserMessage($row->user_id, '您的入会申请已驳回', $auditcontent, 'dmoapply', $row->id);
        }

        $this->success('审核已驳回');
    }

    public function submitAudit()
    {
        $id = XcRequestUtils::post("id", true);
        $row = XcDaoUtils::getRowById($this->model, $id, false);

        $model = [
            'status' => 'normal',
            "name" => XcRequestUtils::post("name"),
            "csmipr_dmocategory_id" => XcRequestUtils::post("csmipr_dmocategory_id"),
            "csmipr_dmocategory_ids" => XcRequestUtils::post("csmipr_dmocategory_ids"),
            "type" => XcRequestUtils::post("type"),
            "types" => XcRequestUtils::post("types"),
            "content" => XcRequestUtils::post("content"),
            "bannerimage" => XcRequestUtils::post("bannerimage"),
        ];
        $row->save($model);

        $baserow = null;
        if (true) {
            $dao = new \app\admin\model\csmipr\Dmo();
            $model["csmipr_dmoapply_id"] = $row->id;
            $baserow = $dao->create($model);
            XcDaoUtils::saveMapping(new \app\admin\model\csmipr\Dmo2category(), "csmipr_dmo_id", $baserow->id, "csmipr_dmocategory_id", $baserow->csmipr_dmocategory_ids);
            
            XpDmoUtils::triggerAdd($baserow);
        }

        $this->success(null, null, ['baserow' => $baserow]);
    }
}
