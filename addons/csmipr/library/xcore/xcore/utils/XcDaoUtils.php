<?php

namespace addons\csmipr\library\xcore\xcore\utils;

/**
 * Dao的工具封装类
 */
class XcDaoUtils
{
    /**
     * 用户1:N时,保存冗余数据到mapping表(2)
     * create:
     * XcDaoUtils::saveMapping(new \app\admin\model\airoom\Aiitem2aicategory(),"airoom_aiitem_id",$result,$result,"airoom_aicategory_id",$params['airoom_aicategory_ids']);
     * update:
     * XcDaoUtils::saveMapping(new \app\admin\model\airoom\Aiitem2aicategory(), "airoom_aiitem_id",$row->id, "airoom_aicategory_id", $params['airoom_aicategory_ids']);
     */
    public static function saveMapping($mapDao, $id_field, $id, $ref_id_field, $ref_ids)
    {
        if (true) {
            $list = $mapDao->where($id_field, 'in', $id)->select();
            foreach ($list as $item) {
                $item->delete();
            }
        }

        if (true) {
            $ref_id_array = explode(",", $ref_ids);
            foreach ($ref_id_array as $ref_id) {
                $model = [
                    $id_field => $id,
                    $ref_id_field => $ref_id,
                ];
                $mapDao->create($model);
            }
        }
    }
    /**
     * 根据ID获取记录（附带assert）
     *
     * 示例:XcDaoUtils::getRowById(new \app\admin\model\xpdemo\project(),10,true,true);
     * @param object $dao
     * @param int $id
     * @param boolean $hasNormalField 是否增加status='normal'的条件
     * @param boolean $isAssert 是否断言为空
     * @return object
     */
    public static function getRowById($dao, $id, $hasNormalField = false, $isAssert = true, $fields = "*")
    {
        $dao = $dao->alias('t');
        $dao = $dao->where('t.id', '=', $id);
        if ($hasNormalField === true) {
            $dao = $dao->where('t.status', '=', 'normal');
        }
        $dao->field($fields);
        $row = $dao->find();

        // XcDebugUtils::showSql($dao);
        if ($isAssert === true) {
            XcAssertUtils::assertNotNull($row, $id,"记录#{$id}不存在或已经被删除");
        }

        return $row;
    }

    /**
     * 查询row记录-根据多个等号查询字段
     * 
     * 示例:XcDaoUtils::getRowBySql(new \app\admin\model\xpdemo\project(),[['name','=','xx']],["id,name"],true,true);
     * @param object $dao
     * @param array $whereFieldValues where对象，eg:[['id','=','1']]
     * @param array $fieldString field字段，eg:id,name
     * @param boolean $hasNormalField 是否增加status='normal'的条件
     * @param boolean $isAssert 是否断言
     * @return object
     */
    public static function getRowBySql($dao, $whereFieldValues, $fieldString = null, $hasNormalField = true, $isAssert = false, $orders = "id desc")
    {
        $row = null;
        $dao = $dao->alias('t');
        if ($fieldString != null) {
            $dao->field($fieldString);
            //var_dump($fieldString);
        }
        foreach ($whereFieldValues as $item) {
            $dao->where($item[0], $item[1], $item[2]);
        }
        if ($hasNormalField != null) {
            $dao->where('t.status', '=', 'normal');
        }
        $dao = $dao->order($orders);
        $row = $dao->find();
        // XcDebugUtils::showSql($dao);

        if ($isAssert === true) {
            XcAssertUtils::assertNotNull($row, $dao->getLastSql());
        }
        return $row;
    }

    public static function randomRowBySql($dao, $whereFieldValues, $fieldString = null, $hasNormalField = true, $isAssert = true)
    {
        $list = static::getListBySql($dao, $whereFieldValues, $fieldString, $hasNormalField);
        $row = static::randomRow($list);

        // if (CsmContants::$xdebug === true) {
        //     foreach ($list as $item) {
        //         if ($item->xdebug == 'Y') {
        //             $row = $item;
        //         }
        //     }
        // }

        if ($isAssert === true) {
            XcAssertUtils::assertNotNull($row, "randomRowBySql");
        }
        return $row;
    }

    /**
     * 查询List记录-根据多个等号查询字段和排序
     *
     * 示例:XcDaoUtils::getListBySql(new \app\admin\model\xpdemo\project(),[['name','=','xx']],["id,name"],["id","desc"],true);
     * @param object $dao dao
     * @param array $whereFieldValues where对象，eg:[['id','=','1']]
     * @param string $fieldString  eg:id,name
     * @param array $orders 排序,eg:['id','desc']
     * @param boolean $hasNormalField 是否增加status='normal'的条件
     * @return array
     */
    public static function getListBySql($dao, $whereFieldValues, $fieldString = null, $orders = null, $hasNormalField = true)
    {
        $list = null;
        $dao = $dao->alias('t');
        if ($fieldString != null) {
            $dao = $dao->field($fieldString);
        }
        foreach ($whereFieldValues as $item) {
            if ($item == null) {
                continue;
            }
            if (count($item) == 2) {
                $dao = $dao->where($item[0], $item[1]);
            } else {
                $dao = $dao->where($item[0], $item[1], $item[2]);
            }
        }
        if ($hasNormalField === true) {
            $dao = $dao->where('t.status', '=', 'normal');
        }
        if ($orders != null) {
            $dao = $dao->order($orders);
        }
        $list = $dao->select();
        // XcDebugUtils::showSql($dao);
        return $list;
    }

    /**
     * 一条记录多选字段的绑定
     */
    public static function bindDbRowMultiColumn(&$row, $key, $dao, $daoalias, $daofieldname)
    {
        $ids = [];
        //        foreach ($list as $item) {
        if (isset($row->$key)) {
            if ($row->$key != null && $row->$key != '') {
                $tt = explode(',', $row->$key);
                $ids = array_merge($tt, $ids);
            }
        }
        //        }



        $keylist = $dao->where("id", "in", $ids)
            ->field("id," . $daofieldname)
            ->select();

        $keynames = [];
        foreach ($keylist as $keyrow) {
            $keynames['D' . $keyrow->id] = $keyrow->$daofieldname;
        }

        //        foreach ($list as $i => $item) {
        $tt = [];

        $mutilkeystring = isset($row->$key) ? $row->$key : null;
        if ($mutilkeystring != null && $mutilkeystring != '') {
            $mutilkeys = explode(',', $mutilkeystring);
            foreach ($mutilkeys as $item_key) {

                if (isset($keynames['D' . $item_key])) {
                    $tt[] = $keynames['D' . $item_key];
                }
            }
        }
        $row[$daoalias . "_" . $daofieldname] = $tt;

        //        }

    }


    /**
     * 多选字段的绑定
     */
    public static function bindDbListMultiColumn(&$list, $key, $dao, $daoalias, $daofieldname)
    {
        $ids = [];
        foreach ($list as $item) {
            if (isset($item->$key)) {
                if ($item->$key != null && $item->$key != '') {
                    $tt = explode(',', $item->$key);
                    $ids = array_merge($tt, $ids);
                }
            }
        }

        $keylist = $dao->where("id", "in", $ids)
            ->field("id," . $daofieldname)
            ->select();

        $keynames = [];
        foreach ($keylist as $keyrow) {
            $keynames['D' . $keyrow->id] = $keyrow->$daofieldname;
        }

        foreach ($list as $i => $item) {
            $tt = [];

            $mutilkeystring = isset($item->$key) ? $item->$key : null;
            if ($mutilkeystring != null && $mutilkeystring != '') {
                $mutilkeys = explode(',', $mutilkeystring);
                foreach ($mutilkeys as $item_key) {

                    if (isset($keynames['D' . $item_key])) {
                        $tt[] = $keynames['D' . $item_key];
                    }
                }
            }
            $list[$i][$daoalias . "_" . $daofieldname] = $tt;
        }
    }

    /**
     * 将list的id对应的记录绑定在list上,比如tastgroup2user的list需要将fa_user记录绑定上list
     * useage: 
     * XcDaoUtils::bindDbListColumn($list,"user_id",new \app\admin\model\User(),"user",["username","avatar"]);
     */
    public static function bindDbListColumn(&$list, $key, $dao, $daoalias, $daofieldnames)
    {
        $ids = [];
        foreach ($list as $item) {
            if (isset($item->$key)) {
                $ids[] = $item->$key;
            }
        }

        $keylist = $dao->where("id", "in", $ids)
            ->field("id," . join(",", $daofieldnames))
            ->select();

        $keynames = [];
        foreach ($keylist as $keyrow) {
            $daofieldvalues = [];
            foreach ($daofieldnames as $daofieldname) {
                $daofieldvalues[$daofieldname]  = $keyrow->$daofieldname;
            }
            $keynames['D' . $keyrow->id] = $daofieldvalues;
        }

        foreach ($list as $i => $item) {
            $item_key = isset($item->$key) ? $item->$key : null;
            if (isset($keynames['D' . $item_key])) {
                foreach ($keynames['D' . $item_key] as $daofieldname => $daofieldvalue) {
                    $newkeyname = ($daoalias == null) ? $daofieldname : ("{$daoalias}_{$daofieldname}");
                    $list[$i][$newkeyname] = $daofieldvalue;
                }
            } else {
                $list[$i][$key] = null;
            }
        }
    }

    public static function bindListColumn2list(&$list, $key, $list2, $key2, $daoalias)
    {
        foreach ($list as &$item) {
            foreach ($list2 as $item2) {
                if ($item->$key == $item2->$key2) {
                    foreach ($item2->getData() as $kk => $vv) {
                        $keyname = "{$daoalias}_{$kk}";
                        $item->$keyname = $vv;
                    }
                }
            }
        }
    }

    public static function bindDbRowColumn(&$row, $key, $dao, $daoalias, $daofieldnames)
    {
        $row2 = XcDaoUtils::getRowById($dao, $row->$key, true, false, $daofieldnames);
        if ($row2 != null) {
            foreach ($row2->getData() as $k => $v) {
                $newkeyname = ($daoalias == null) ? $k : ("{$daoalias}_{$k}");
                $row->$newkeyname = $v;
            }
        }
    }


    public static function mergeList($fromList, $toList, $mergeFieldName)
    {
        $list = [];
        $mergeFieldValues = [];
        foreach ($fromList as $item) {
            $mergeFieldValues[] = $item->$mergeFieldName;
            $list[] = $item;
        }
        foreach ($toList as $item) {
            if (!in_array($item->$mergeFieldName, $mergeFieldValues)) {
                $list[] = $item;
            }
        }
        return $list;
    }

    public static function getValuesFromList($list, $keyname)
    {
        $values = [];
        foreach ($list as $item) {
            $values[] = $item->$keyname;
        }
        return $values;
    }
 

    public static function cloneRow2Model($row)
    {
        $model = [];
        foreach ($row->getData() as $key => $value) {
            $model[$key] = $value;
        }
        return $model;
    }


    private static function randomRow($rows)
    {
        if (count($rows) == 0) {
            return null;
        }
        if (count($rows) == 1) {
            return array_shift($rows);
            //return $rows[0];
        }
        $list = static::randomRows($rows, 1);
        return $list[0];
    }
    private static function randomRows($rows, $rowCount)
    {
        if (count($rows) <= $rowCount) {
            return $rows;
        }


        $list = [];
        $indexs = array_rand($rows, $rowCount);
        if ($rowCount == 1) {
            return [$rows[$indexs]];
        }
        foreach ($indexs as $index) {
            $list[] = $rows[$index];
        }
        return $list;
    }
}
