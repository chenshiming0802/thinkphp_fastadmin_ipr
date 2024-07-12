<?php

namespace addons\csmipr\library\xcore\xcore\base;

use think\Loader;
use ReflectionClass;
use app\common\controller\Api;
use addons\csmipr\library\xcore\xcore\utils\XcDaoUtils;
use addons\csmipr\library\xcore\xcore\utils\XcLangUtils;
use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;

/**
 * Api的基础类(api control的基类)
 */
abstract class XcAApi extends Api
{
    protected $noNeedLogin = ["*"];
    protected $noNeedRight = ["*"];
    protected $model = null;
    protected $fields = ["*"];
    protected $dictfields = [];

    public function getDicts()
    {

        $clsname = null;
        if(true){
            $classname = get_class($this->model);
            $clsname = substr($classname,strrpos($classname, '\\') + 1);
        }

        XcLangUtils::loadAdminlang($clsname);
        $list = [];
        $reflectionClass = new ReflectionClass(get_class($this->model));
        foreach ($reflectionClass->getMethods() as $method) {
            $methoName = $method->name;
            $methodDName = lcfirst(substr($methoName, 3, -4));
            if (in_array($methodDName, $this->dictfields)) {
                if (strpos($methoName, 'get') === 0 && substr($methoName, -4) === 'List') {
                    $results = $this->model->$methoName();
                    $item = [];
                    foreach ($results as $key => $value) {
                        $item[] = ["id" => $key, "code" => $key, "name" => $value];
                    }
                    $list[$methodDName] = $item;
                }
            }
        }
        $this->success(null, ["list" => $list]);
    }

    public function getModel()
    {
        return $this->model;
    }
    abstract protected function xinit();
    protected function _initialize()
    {
        parent::_initialize();
        $this->xinit();
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
            $row = XcDaoUtils::getRowById($this->model, $id, true, true, $this->fields);
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
        // $a = null;$b = null;
        $dao = $this->model
            ->where($where)
            ->where("status", "=", "normal")
            // ->where(function ($query) use ($a,$b) {
            //     $query->whereOr('a', "=",$a )->whereOr('b', "=",$b);
            // })
            ->field($this->fields)
            ->order($sort, $order);

        $list = $dao->paginate($limit);

        return [$list->items(), $list->total()];
    }

    protected function buildparams($searchfields = null, $relationSearch = null)
    {
        $search = $this->request->get("search", '');
        $filter = $this->request->get("filter", '');
        $filter = str_replace('&quot;', '"', $filter);
        $op = $this->request->get("op", '', 'trim');
        $op = str_replace('&quot;', '"', $op);
        $sort = $this->request->get("sort", !empty($this->model) && $this->model->getPk() ? $this->model->getPk() : 'id');
        $order = $this->request->get("order", "DESC");
        $offset = $this->request->get("offset/d", 0);
        $limit = $this->request->get("limit/d", 999999);
        //新增自动计算页码
        $page = $limit ? intval($offset / $limit) + 1 : 1;
        if ($this->request->has("page")) {
            $page = $this->request->get("page/d", 1);
        }
        $this->request->get([config('paginate.var_page') => $page]);
        $filter = (array)json_decode($filter, true);
        $op = (array)json_decode($op, true);
        $filter = $filter ? $filter : [];

        $where = [];
        $alias = [];
        $bind = [];
        $name = '';
        $aliasName = '';
        $sortArr = explode(',', $sort);
        foreach ($sortArr as $index => &$item) {
            $item = stripos($item, ".") === false ? $aliasName . trim($item) : $item;
        }
        unset($item);
        $sort = implode(',', $sortArr);

        if ($search) {
            $searcharr = is_array($searchfields) ? $searchfields : explode(',', $searchfields);
            foreach ($searcharr as $k => &$v) {
                $v = stripos($v, ".") === false ? $aliasName . $v : $v;
            }
            unset($v);
            $where[] = [implode("|", $searcharr), "LIKE", "%{$search}%"];
        }
        $index = 0;
        foreach ($filter as $k => $v) {
            if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $k)) {
                continue;
            }
            $sym = isset($op[$k]) ? $op[$k] : '=';
            if (stripos($k, ".") === false) {
                $k = $aliasName . $k;
            }
            $v = !is_array($v) ? trim($v) : $v;
            $sym = strtoupper(isset($op[$k]) ? $op[$k] : $sym);
            //null和空字符串特殊处理
            if (!is_array($v)) {
                if (in_array(strtoupper($v), ['NULL', 'NOT NULL'])) {
                    $sym = strtoupper($v);
                }
                if (in_array($v, ['""', "''"])) {
                    $v = '';
                    $sym = '=';
                }
            }

            switch ($sym) {
                case '=':
                case '<>':
                    $where[] = [$k, $sym, (string)$v];

                    break;
                case 'LIKE':
                case 'NOT LIKE':
                case 'LIKE %...%':
                case 'NOT LIKE %...%':
                    $where[] = [$k, trim(str_replace('%...%', '', $sym)), "%{$v}%"];
                    break;
                case '>':
                case '>=':
                case '<':
                case '<=':
                    //$where[] = [$k, $sym, intval($v)];
                    $where[] = [$k, $sym, ($v)];
                    break;
                case 'FINDIN':
                case 'FINDINSET':
                case 'FIND_IN_SET':
                    $v = is_array($v) ? $v : explode(',', str_replace(' ', ',', $v));
                    $findArr = array_values($v);
                    foreach ($findArr as $idx => $item) {
                        $bindName = "item_" . $index . "_" . $idx;
                        $bind[$bindName] = $item;
                        $where[] = "FIND_IN_SET(:{$bindName}, `" . str_replace('.', '`.`', $k) . "`)";
                    }
                    break;
                case 'IN':
                case 'IN(...)':
                case 'NOT IN':
                case 'NOT IN(...)':
                    $where[] = [$k, str_replace('(...)', '', $sym), is_array($v) ? $v : explode(',', $v)];
                    break;
                case 'BETWEEN':
                case 'NOT BETWEEN':
                    $arr = array_slice(explode(',', $v), 0, 2);
                    if (stripos($v, ',') === false || !array_filter($arr, function ($v) {
                        return $v != '' && $v !== false && $v !== null;
                    })) {
                        continue 2;
                    }
                    //当出现一边为空时改变操作符
                    if ($arr[0] === '') {
                        $sym = $sym == 'BETWEEN' ? '<=' : '>';
                        $arr = $arr[1];
                    } elseif ($arr[1] === '') {
                        $sym = $sym == 'BETWEEN' ? '>=' : '<';
                        $arr = $arr[0];
                    }
                    $where[] = [$k, $sym, $arr];
                    break;
                case 'RANGE':
                case 'NOT RANGE':
                    $v = str_replace(' - ', ',', $v);
                    $arr = array_slice(explode(',', $v), 0, 2);
                    if (stripos($v, ',') === false || !array_filter($arr)) {
                        continue 2;
                    }
                    //当出现一边为空时改变操作符
                    if ($arr[0] === '') {
                        $sym = $sym == 'RANGE' ? '<=' : '>';
                        $arr = $arr[1];
                    } elseif ($arr[1] === '') {
                        $sym = $sym == 'RANGE' ? '>=' : '<';
                        $arr = $arr[0];
                    }
                    $tableArr = explode('.', $k);
                    if (count($tableArr) > 1 && $tableArr[0] != $name && !in_array($tableArr[0], $alias) && !empty($this->model)) {
                        //修复关联模型下时间无法搜索的BUG
                        $relation = Loader::parseName($tableArr[0], 1, false);
                        $alias[$this->model->$relation()->getTable()] = $tableArr[0];
                    }
                    $where[] = [$k, str_replace('RANGE', 'BETWEEN', $sym) . ' TIME', $arr];
                    break;
                case 'NULL':
                case 'IS NULL':
                case 'NOT NULL':
                case 'IS NOT NULL':
                    $where[] = [$k, strtolower(str_replace('IS ', '', $sym))];
                    break;
                default:
                    break;
            }
            $index++;
        }
        if (!empty($this->model)) {
            $this->model->alias($alias);
        }
        $model = $this->model;
        $where = function ($query) use ($where, $alias, $bind, &$model) {
            if (!empty($model)) {
                $model->alias($alias);
                $model->bind($bind);
            }
            foreach ($where as $k => $v) {
                if (is_array($v)) {
                    call_user_func_array([$query, 'where'], $v);
                } else {
                    $query->where($v);
                }
            }
        };
        return [$where, $sort, $order, $offset, $limit, $page, $alias, $bind];
    }
}
