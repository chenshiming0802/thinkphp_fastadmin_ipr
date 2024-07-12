<?php

namespace app\admin\model\csmipr;

use think\Model;


class Cloginlog extends Model
{

    

    

    // 表名
    protected $name = 'csmipr_cloginlog';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'septime_text'
    ];
    

    



    public function getSeptimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['septime']) ? $data['septime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setSeptimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
