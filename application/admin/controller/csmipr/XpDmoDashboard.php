<?php

namespace app\admin\controller\csmipr;

use app\common\controller\Backend;
use addons\csmipr\library\xcore\xcore\utils\XcDateUtils;
use addons\csmipr\library\xcore\xcore\utils\XcDebugUtils;
use addons\csmipr\library\xcore\xcore\utils\XcRequestUtils;
use addons\csmipr\library\xapp\csmipr\XpAddon;
use addons\csmipr\library\xcore\xcore\utils\XcDashboardUtils;

/**
 * DEMO明细
 *
 * @icon fa fa-circle-o
 */
class XpDmoDashboard extends Backend
{
    public function tongji1()
    {
        if (true) {
            $dao = new \app\admin\model\csmipr\Dmo();
            $cnt = $dao->count();
            $this->view->assign("total_count", $cnt);
        }
        if (true) {
            $tt = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $dao = new \app\admin\model\csmipr\Dmo();
            $cnt = $dao->where('createtime', '>=', $tt)->count();
            $this->view->assign("today_count", $cnt);
        }
        if (true) {
            $tt = strtotime("-7 days");
            $dao = new \app\admin\model\csmipr\Dmo();
            $cnt = $dao->where('createtime', '>=', $tt)->count();
            $this->view->assign("d7_count", $cnt);
        }
        if (true) {
            $tt = strtotime("-30 days");
            $dao = new \app\admin\model\csmipr\Dmo();
            $cnt = $dao->where('createtime', '>=', $tt)->count();
            $this->view->assign("d30_count", $cnt);
        }

        if (true) {
            $dao = new \app\admin\model\csmipr\Cloginlog();
            $list = $dao->where('operate', '=', 12)->group('cweek')->field('cweek,count(distinct user_id) cnt')->select();
            $list_data = [0, 0, 0, 0, 0, 0, 0];
            foreach ($list as $item) {
                $list_data[$item->cweek] = $item->cnt;
            }
            $this->assignconfig('user_week_line', $list_data);
        }
        if (true) {
            $dao = new \app\admin\model\csmipr\Cloginlog();
            $list = $dao->where('operate', '=', 12)->group('port')->field('port,count(*) cnt')->select();
            $list_data = [];

            $config_port = XpAddon::configCloginPort();
            foreach ($list as $item) {
                $list_data[] = [
                    "name" => $config_port[$item->port],
                    "value" => $item->cnt
                ];
            }
            $this->assignconfig('user_port_pie', $list_data);
        }

        if (true) {
            $dao = new \app\admin\model\csmipr\Cloginlog();
            $tt = strtotime("-7 days");
            $list = $dao->where('operate', '=', 101)->where('createtime', '>=', $tt)
                ->group("cyear,cmonth,cdate")
                ->field("concat(cyear,'-',cmonth,'-',cdate) ddate,count(distinct user_id) cnt")
                ->select();

            $list_weidu = XcDateUtils::getDatesFromSepdate(-7, 1);
            $list_data = XcDashboardUtils::adjustLineData($list_weidu, $list, "ddate", "cnt");

            $this->assignconfig('data_line_weidu', $list_weidu);
            $this->assignconfig('data_line', $list_data);
        }

        if (true) {
            $dao = new \app\admin\model\csmipr\Dmo();
            $list = $dao->group('type')->field('type,count(*) cnt')->select();
            $list_data = [];

            $names = $dao->getTypeList();
            
            foreach ($list as $item) {
                $list_data[] = [
                    "name" => $names[$item->type],
                    "value" => $item->cnt
                ];
            }
            $this->assignconfig('data_pie', $list_data);
        }

        return $this->view->fetch();
    }


    public function page1_list1()
    {
        [$where, $sort, $order, $offset, $limit] = $this->buildparams();

        $dao = new \app\admin\model\csmipr\Cloginlog();
        $list = $dao->alias("log")
            ->join("user user", "log.user_id=user.id")
            ->join("csmipr_dmo dmo", "log.object_id=dmo.id")
            ->field("dmo.id 'dmo.id',dmo.name 'dmo.name',dmo.createtime 'dmo.createtime',user.id 'user.id',user.nickname 'user.nickname',user.mobile 'user.mobile',log.port 't.port'")
            ->where("log.operate", '=', '101')
            ->order("dmo.createtime desc")
            ->where($where)
            ->paginate($limit);
        $result = ['total' => $list->total(), 'rows' => $list->items()];
        return json($result);
    }

    public function page1_list2()
    {
        [$where, $sort, $order, $offset, $limit] = $this->buildparams();
        $dao = new \app\admin\model\User();
        $list = $dao->alias("user")
            ->join("fa_csmipr_dmo dmo", "dmo.user_id=user.id")
            ->field("user.id 'user.id',user.nickname 'user.nickname',user.mobile 'user.mobile',count(*) count,max(dmo.createtime) recent_createtime")
            ->group("user.id,user.nickname,user.mobile")
            ->order("count desc")
            ->where($where)
            ->paginate($limit);
        $result = ['total' => $list->total(), 'rows' => $list->items()];
        return json($result);
    }

}
