<?php

namespace addons\csmipr\library\xcore\xcore\utils;

class XcDashboardUtils
{
       /**
     * 根据纬度，将list的值转换为chart的data
     * adjustLineData(["2024-12-13","2024-12-14","2024-12-15"],$list,"createdate","cnt")
     * 返回： [10,20,30] 值是从list的createdate的记录sum(cnt)得出
     * @return void
     */
    public static function adjustLineData($weidus, $list, $key_fieldname, $count_fieldname)
    {
        $weidu_data = [];
        foreach ($weidus as $key => $weidu) {
            $weidu_data[$key] = 0;
        }

        foreach ($list as $item) {
            foreach ($weidus as $key => $weidu) {
                if ($weidu == $item->$key_fieldname) {
                    $count = $item->$count_fieldname;
                    $weidu_data[$key] = $weidu_data[$key] + $count;
                }
            }
        }
        return $weidu_data;
    }
}
