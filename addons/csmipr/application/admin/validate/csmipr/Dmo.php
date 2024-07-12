<?php
// +----------------------------------------------------------------------
// | XPFRAMEWORK  [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024 https://gitee.com/sdnode All rights reserved.
// +----------------------------------------------------------------------
// | Author: chenshiming
// +----------------------------------------------------------------------

namespace app\admin\validate\csmipr;

use think\Validate;

class Dmo extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
    ];
    
}