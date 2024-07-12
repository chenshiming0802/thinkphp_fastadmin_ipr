<?php

namespace addons\csmipr\library\xcore\xcore\base;

use app\common\controller\Backend;

/**
 * Backend的基础类(admin control的基类)
 */
abstract class XcABackend extends Backend
{

    /**
     * 同assign(解决assign为protected的问题)
     *
     * @param string $name
     * @param string $value
     * @return mixed
     */
    public function xcAssign($name, $value = '')
    {
        return $this->assign($name, $value);
    }

    /**
     * 同assign(解决assign为protected的问题)
     *
     * @param string $name
     * @param string $value
     * @return mixed
     */
    public function xcAssignconfig($name, $value = '')
    {
        return $this->assignconfig($name, $value);
    }

  
}
