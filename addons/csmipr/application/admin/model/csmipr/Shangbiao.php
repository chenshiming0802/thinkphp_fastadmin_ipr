<?php
// +----------------------------------------------------------------------
// | CSMIPR  [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024 https://gitee.com/chenshiming0802/thinkphp_fastadmin_ipr All rights reserved.
// +----------------------------------------------------------------------
// | Author:chenshiming0802
// +----------------------------------------------------------------------

namespace app\admin\model\csmipr;

use think\Model;


class Shangbiao extends Model
{

    

    

    // 表名
    protected $name = 'csmipr_shangbiao';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'isgongyu_text',
        'hastuan_text',
        'status_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getIsgongyuList()
    {
        return ['Y' => __('Isgongyu y'), 'N' => __('Isgongyu n')];
    }

    public function getHastuanList()
    {
        return ['Y' => __('Hastuan y'), 'N' => __('Hastuan n')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    public function getIsgongyuTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['isgongyu']) ? $data['isgongyu'] : '');
        $list = $this->getIsgongyuList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getHastuanTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['hastuan']) ? $data['hastuan'] : '');
        $list = $this->getHastuanList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}