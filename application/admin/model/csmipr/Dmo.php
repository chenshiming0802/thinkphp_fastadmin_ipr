<?php
// +----------------------------------------------------------------------
// | XPFRAMEWORK  [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024 https://gitee.com/sdnode All rights reserved.
// +----------------------------------------------------------------------
// | Author: chenshiming
// +----------------------------------------------------------------------

namespace app\admin\model\csmipr;

use think\Model;


class Dmo extends Model
{

    

    

    // 表名
    protected $name = 'csmipr_dmo';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'type_text',
        'types_text',
        'isread_text',
        'status_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getTypeList()
    {
        return ['T1' => __('Type t1'), 'T2' => __('Type t2')];
    }

    public function getTypesList()
    {
        return ['T1' => __('Types t1'), 'T2' => __('Types t2')];
    }

    public function getIsreadList()
    {
        return ['Y' => __('Isread y'), 'N' => __('Isread n')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getTypesTextAttr($value, $data)
    {
        $value = $value ?: ($data['types'] ?? '');
        $valueArr = explode(',', $value);
        $list = $this->getTypesList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }


    public function getIsreadTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['isread']) ? $data['isread'] : '');
        $list = $this->getIsreadList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function setTypesAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }


}