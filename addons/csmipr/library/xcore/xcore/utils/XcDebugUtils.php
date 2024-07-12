<?php

namespace addons\csmipr\library\xcore\xcore\utils;


/**
 * Debug的工具封装类
 * 
 * 启用需要在链接之后添加参数: ?xdebug=true
 * 解决了Fastadmin自带的Log::record($msg)在日志打印框中不易阅读的问题
 */
class XcDebugUtils
{


    public static function showSql($dao, $isDie = false)
    {
        $sql = $dao->getLastSql();
        echo $sql;

        if ($isDie === true) {
            die();
        }
    }

    /**
     * 打印数据库DB的List(为解决DbList打印时,有DB的信息不直观)
     *
     * 示例:XcDebugUtils::showDbList($list,false);
     * @param object $dblist 需打印的从数据库读取出来的Db List
     * @param boolean $isDie 打印完毕是否die()
     * @return void
     */
    public static function showDbList($dblist, $isDie = false)
    {
        $plist = [];
        foreach ($dblist as $item) {
            $pitem = [];
            foreach ($item->getData() as $k => $v) {
                $pitem[$k] = $v;
            }
            $plist[] = $pitem;
        }
        echo "<pre>";
        var_dump($plist);
        echo "</pre>";
        if ($isDie === true) {
            die();
        }
    }


    /**
     * 打印数据库DB的Row(为解决DbRow打印时,有DB的信息不直观)
     *
     * 示例:XcDebugUtils::showDbRow($row,false);
     * @param object $dbRow 需打印的从数据库读取出来的Db Row
     * @param boolean $isDie 打印完毕是否die()
     * @return void
     */
    public static function showDbRow($dbRow, $isDie = false)
    {

        $pitem = [];
        foreach ($dbRow->getData() as $k => $v) {
            $pitem[$k] = $v;
        }
        echo "<pre>";
        var_dump($pitem);
        echo "</pre>";
        if ($isDie === true) {
            die();
        }
    }
}
