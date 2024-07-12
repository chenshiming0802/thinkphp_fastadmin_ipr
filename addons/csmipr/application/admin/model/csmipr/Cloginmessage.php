<?php

namespace app\admin\model\csmipr;

use think\Model;


class Cloginmessage extends Model
{

    

    

    // 表名
    protected $name = 'csmipr_cloginmessage';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'isread_text',
        'readtime_text',
        'status_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getIsreadList()
    {
        return ['Y' => __('Isread y'), 'N' => __('Isread n')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    public function getIsreadTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['isread']) ? $data['isread'] : '');
        $list = $this->getIsreadList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getReadtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['readtime']) ? $data['readtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setReadtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
