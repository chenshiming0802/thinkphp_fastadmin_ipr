<?php

namespace addons\csmipr\library\xcore\xcore\utils;

use cn\sdnode\xstack\utils\XsstringUtils;


/**
 * Fastadmin的后台管理页面详情增加Tab的支持类
 */
class XcAdminTabUtils
{
    public static function append_control_initialize($objectname, $object_id, $classname, $view)
    {
        $view->assign("id", $object_id);
        $tabgroup = XcConfigUtils::xpconfig("XcAdminTabUtils.tabgroup");
        $view->assign("xp_currentnav_html", static::generateHtml($object_id, $tabgroup[$objectname], XcAdminTabUtils::getOnlyClassname($classname) . "#" . XcRequestUtils::action()));
    }

    public static function getOnlyClassname($fullclassname)
    {
        $cls = explode('\\', $fullclassname);
        return $cls[count($cls) - 1];
    }

    public static function generateHtml($object_id, $nav, $currentnav)
    {
        $root = XcRequestUtils::root();

        $navstring = "";
        foreach ($nav as $item) {
            $aa = ($item["code"] == $currentnav) ? "class='active'" : "";
            $url = XsstringUtils::replace($item["url"],'{id}',$object_id);
            $name = $item["name"];
            // {$item.code==$currentnav?'class='active'':''}
            $navstring .= "<li $aa><a href='{$root}/{$url}'>{$name}</a></li>";
        }

        return "
            <style>
            form{
                background-color: white;
            }
            .inner a:link {color:#ffffff;}		 
            .inner a:visited {color:#ffffff;} 
            .inner a:hover {color:#9c9c9c;}	 
            .inner a:active {color:#ffffff;} 
            </style>
            <div class='panel panel-default panel-intro'>  
                <div class='panel-heading'>
                    <ul class='nav nav-tabs' data-field='status'>
                        {$navstring}
                    </ul>
                </div>
            </div>
            <input type='hidden' id='xc_tabid' value='{$object_id}'>
            <BR>
        ";
    }
}
