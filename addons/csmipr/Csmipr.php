<?php

namespace addons\csmipr;

use think\Addons;
use app\common\library\Menu;
use addons\csmipr\library\xcore\xcore\utils\XcConfigUtils;

/**
 * 插件
 */
class Csmipr extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
        ];
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete(XcConfigUtils::xpconfig('addons_code'));
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable(XcConfigUtils::xpconfig('addons_code'));
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable(XcConfigUtils::xpconfig('addons_code'));
        return true;
    }

    // Hook文档: https://doc.fastadmin.net/developer/87.html
    public function appInit()
    {
        $path = APP_PATH . '..' . \DIRECTORY_SEPARATOR . 'addons' . \DIRECTORY_SEPARATOR . 'csmipr' . \DIRECTORY_SEPARATOR . 'library' . \DIRECTORY_SEPARATOR . 'composer' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';

        require_once $path;
    }

    public function xpMergeUser($src_user_id,$target_user_id){
        return;
    }
}
