<?php

namespace addons\csmipr\library\xcore\xcore\utils;

use think\Lang;
use think\Request;


class XcLangUtils
{
    public static function loadAdminlang($objectName){
        $path = APP_PATH . "admin/lang/zh-cn/csmipr/{$objectName}.php";
        Lang::load($path);
    }
    
    public static function lang($name, $vars = [], $lang = '')
    {
        $cmsg = lang($name, $vars, $lang);
        return $cmsg;
    }

    /**
     * 根据lang,返回chtext或entext
     */
    public static function getCLangText($chtext, $entext)
    {
        $request = Request::instance();
        $lang = $request->header('CLang');
        if ($lang == 'en') {
            return $entext;
        } else {
            return $chtext;
        }
    }

    /**
     * 【专题API：供开发用Api】
     * 说明：双语处理Row:数据处理,根据语言显示对应的语言包
     * 依赖表：无
     * 使用场景：无
     * 使用方法：无
     * 调用示例：app\api\controller\csmipr\csmipr#gettimeselectinfo
     *      $row = ['name'=>'xxx','en_name'=>'xxxx'];//$row可以直接数据库读取的row
     *      $this->cProcessRowLang($row,['name']);
     * 逻辑说明：无  
     *    
     * 创建时间：2022-07-03@陈市明
     * 修改记录：
     *      无
     *      2022-07-03@陈市明：xxx  
     *   
     * @param $row 数据库记录,需要转换的data数据包(对应的字段,en_前缀为英文,比如name对应字段en_name)
     * @param $fields 需要转换的字段集合
     * @return 
     */
    public static function processRowLang($row, $fields)
    {
        $request = Request::instance();
        $lang = $request->header('CLang');
        if ($lang != 'en') {
            return;
        }
        foreach ($fields as $item) {
            $enitem = 'en_' . $item;
            if (is_array($row)) {
                if ($row[$enitem] != null && $row[$enitem] != '') {
                    $row[$item] = $row[$enitem];
                }
            } else {
                if ($row->$enitem != null && $row->$enitem != '') {
                    $row->$item = $row->$enitem;
                }
            }
        }
        return $row;
    }

    /**
     * 参照cProcessRowLang的List版本
     */
    public static function processListLang($list, $fields)
    {
        $ll = [];
        foreach ($list as &$row) {
            $ll[] = self::processRowLang($row, $fields);
        }
        return $ll;
    }
}
