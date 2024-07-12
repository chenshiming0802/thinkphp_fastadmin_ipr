<?php

namespace app\admin\model\csmipr;

use think\Model;


class Commoninterfacelog extends Model
{

    

    

    // 表名
    protected $name = 'csmipr_commoninterfacelog';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'issuccess_text'
    ];
    

    
    public function getIssuccessList()
    {
        return ['Y' => __('Issuccess y'), 'N' => __('Issuccess n')];
    }


    public function getIssuccessTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['issuccess']) ? $data['issuccess'] : '');
        $list = $this->getIssuccessList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
