<?php

namespace addons\csmipr\library\xcore\xcore\base;

use addons\csmipr\library\xcore\xcore\base\XcAApi;
use addons\csmipr\library\xcore\xcore\utils\XcAssertUtils;
use addons\csmipr\library\xcore\xcore\utils\XcDaoUtils;
use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;
use addons\csmipr\library\xcore\xcore\utils\XcResponseUtils;
use addons\csmipr\library\xcore\xcore\utils\XcUserSessionUtils;

/**
 * Api的基础类(api control的基类)
 * 示例：
class XpmyDemo extends XcMyApi
{
    protected $fields = ["id", "name"];
    protected $createFields = ["name"];

    public function xinit()
    {
        $this->model = new \app\admin\model\csmipr\Myfriend();
    }
}
 */
abstract class XcAMyApi extends XcAApi
{
    protected $noNeedLogin = ["*"];
    protected $noNeedRight = ["*"];
    protected $noNeedMyRight = [];

    //protected $model = null;
    protected $fields = [];
    protected $createFields = null;
    protected $updateFields = null;

    protected $userIdFieldName = "user_id";
    protected $sessionUserId = null;



    public function _initialize()
    {
        parent::_initialize();

        //assert action access-right
        if (true) {
            $actionname = strtolower($this->request->action());
            foreach ($this->noNeedMyRight as &$right) {
                $right = strtolower($right);
            }
            if (in_array($actionname, $this->noNeedMyRight)) {
                return;
            }
        }

        $this->sessionUserId = XcUserSessionUtils::getUserId();
        // 如果不设置，则为fields（去掉ID）
        if ($this->createFields == null) {
            $this->createFields = $this->fields;
            foreach ($this->createFields as $key => $field) {
                if ($field == "id") {
                    unset($this->createFields[$key]);
                }
            }
        }
        // 如果不设置，则为createfields（增加ID）
        if ($this->updateFields == null) {
            $this->updateFields = $this->createFields;
            if (in_array("id", $this->createFields)) {
                $this->updateFields[] = "id";
            }
        }
    }


    /**
     * 校验其他My对象的row
     */
    public function othermyobject_assertMyRow($objectclass, $objectid)
    {
        if ($objectid == "-1") {
            return;
        }
        $objectclass->xinit();
        $row = XcDaoUtils::getRowById($objectclass->getModel(), $objectid);
        $objectclass->assertMyRow($row);
    }


    protected function assertMyRow($row)
    {
        $userIdFieldName = $this->userIdFieldName;
        if ($userIdFieldName != null && $this->sessionUserId != $row->$userIdFieldName) {
            XcResponseUtils::error("非法获取数据:{$row->id}");
        }
    }

    public function getById()
    {
        $id = XcRequestUtils::get("id", true);
        $row = $this->_getById($id);
        $this->success(null, ['row' => $row]);
    }
    public function _getById($id)
    {
        $row = null;
        if (true) {
            $row = XcDaoUtils::getRowById($this->model, $id, false, true, $this->fields);
            $this->assertMyRow($row);
        }
        return $row;
    }

    /**
     * JS调用方式:
				xcHttpUtils.get('xpaido/xpmytaskgroupcategory/queryList', xcHttpUtils.buildparams(
					{xpaido_taskgroup_id:that.sp.xpaido_taskgroup_id},{xpaido_taskgroup_id:"="},"weigh","desc"
				), function(res) {
					control.sr_list = res.list;
				});
     * @return void
     */
    public function queryList()
    {
        $buildparams = $this->buildparams();
        [$list, $total] = $this->_queryList($buildparams);
        $this->success(null, ['list' => $list]);
    }
    protected function _queryList($buildparams, $need_userIdFieldName = true)
    {
        [$where, $sort, $order, $offset, $limit] = $buildparams;
        $dao = $this->model
            ->where($where)
            //->where("status", "=", "normal")
            ->field($this->fields)
            ->order($sort, $order);

        if ($need_userIdFieldName == true && $this->userIdFieldName != null) {
            $dao->where($this->userIdFieldName, "=", $this->sessionUserId);
        }
        $list = $dao->paginate($limit);

        return [$list->items(), $list->total()];
    }

    public function delete()
    {
        $id = XcRequestUtils::post("id", true);
        $this->_delete($id);
        $this->success();
    }
    // public static function triggerDelete($row, $params, $sessionUserId){}
    protected function _delete($id)
    {
        $row = null;
        if (true) {
            $row = XcDaoUtils::getRowById($this->model, $id);
            $this->assertMyRow($row);
        }

        $row->save([
            'status' => "hidden",
            "updatetime" => time()
        ]);

        return $row;
    }


    public function create()
    {
        $params = XcRequestUtils::requestBodyJson();
        $row = $this->_create($params);
        $this->success(null, ['row' => $row]);
    }
    // public static function triggerCreate($row, $params, $sessionUserId){}
    protected function _create($params)
    {

        if (!empty($params['id'])) {
            XcResponseUtils::error("包含非法字段:id");
        }

        $modelrow = [];
        foreach ($this->createFields as $field) {
            if (isset($params[$field])) {
                $modelrow[$field] = $params[$field];
            }
        }
        if ($this->userIdFieldName != null) {
            $modelrow[$this->userIdFieldName] = $this->sessionUserId;
        }

        $rr = $this->model->create($modelrow);
        // $row = XcDaoUtils::getRowById($this->model, $rr->id);
        $row = $this->_getById($rr->id);
        return $row;
    }

    public function update()
    {
        $params = XcRequestUtils::requestBodyJson();
        $this->_update($params);
        $this->success();
    }
    // public static function triggerUpdate($row, $params, $sessionUserId){}
    protected function _update($params)
    {
        XcAssertUtils::assertNotNull($params['id'], 'id');

        $row = XcDaoUtils::getRowById($this->model, $params['id']);
        $this->assertMyRow($row);

        $modelrow = [];
        foreach ($this->updateFields as $field) {
            if (isset($params[$field])) {
                $modelrow[$field] = $params[$field];
            }
        }

        $row->save($modelrow);
        return $row;
    }

    public function weighlist()
    {
        $params = XcRequestUtils::requestBodyJson();
        if (count($params) == 0) {
            $this->success();
        }
        $first_weigh = $params[0]['id'];
        foreach ($params as $index => $param) {
            $this->_weighlist($params['id'], ($first_weigh + $index));
        }
        $this->success();
    }

    protected function _weighlist($id, $weigh)
    {
        $row = XcDaoUtils::getRowById($this->model, $id);
        $this->assertMyRow($row);
        $row->save(["weigh" => $weigh]);
    }


    public function weigh()
    {
        $id1 = XcRequestUtils::post("id1", true);
        $id2 = XcRequestUtils::post("id2", true);
        $this->_weigh($id1, $id2);
        $this->success();
    }
    protected function _weigh($id1, $id2)
    {
        $row1 = XcDaoUtils::getRowById($this->model, $id1);
        $weigh1 = $row1->weigh;
        $this->assertMyRow($row1);

        $row2 = XcDaoUtils::getRowById($this->model, $id2);
        $weigh2 = $row2->weigh;
        $this->assertMyRow($row2);

        $row1->save(["weigh" => $weigh2]);
        $row2->save(["weigh" => $weigh1]);
        return [$row1, $row2];
    }


    public function getValueFromBuildparams($fieldname)
    {
        $filter = XcRequestUtils::get("filter");
        $filter = json_decode(str_replace('&quot;', '"', $filter), true);
        // if (!isset($filter[$fieldname])) {
        //     XcAssertUtils::error("缺少参数:{$fieldname}");
        // }
        return $filter[$fieldname];
    }


    public function createOrUpdate()
    {
        $params = XcRequestUtils::requestBodyJson();

        if (isset($params['id']) && !empty($params['id'])) {
            $row = self::_getById($params['id']);
            $row = self::_update($params);
        } else {
            $row = self::_create($params);
        }
  
        $this->success(null, ["row" => $row]);
    }
}
