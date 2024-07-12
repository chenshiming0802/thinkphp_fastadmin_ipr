define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'echarts', 'echarts-theme'], function ($, undefined, Backend, Table, Form, Template, Echarts) {

    var Controller = {
        tongji1: function () {
            //这句话在多选项卡统计表时必须存在，否则会导致影响的图表宽度不正确
            $(document).on("click", ".charts-custom a[data-toggle=\"tab\"]", function () {
                var that = this;
                setTimeout(function () {
                    var id = $(that).attr("href");
                    var chart = Echarts.getInstanceByDom($(id)[0]);
                    chart.resize();
                }, 0);
            });

            if (true) {
                var chart = Echarts.init(document.getElementById('user_week_line'), 'walden');
                var option = {
                    xAxis: {
                        type: 'category',
                        data: ['周日', '周一', '周二', '周三', '周四', '周五', '周六']
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [{
                        //data: [49, 92, 61, 134, 90, 130, 120],
                        data: Config.user_week_line,
                        type: 'line'
                    }]
                };
                chart.setOption(option);
            }

            if(true){
                var chart = Echarts.init(document.getElementById('user_port_pie'), 'walden');
                var option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b}: {c} ({d}%)'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 10,
                        //data: ['微信', 'APP', 'Web在线', 'Electron']
                    },
                    series: [{
                        name: '用户登录设备',
                        type: 'pie',
                        radius: ['50%', '70%'],
                        avoidLabelOverlap: false,
                        label: {
                            normal: {
                                show: false,
                                position: 'center'
                            },
                            emphasis: {
                                show: true,
                                textStyle: {
                                    fontSize: '30',
                                    fontWeight: 'bold'
                                }
                            }
                        },
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        data:Config.user_port_pie,
                    }]
                };
                chart.setOption(option);
            }

            if (true) {
                var chart = Echarts.init(document.getElementById('data_line'), 'walden');
                var option = {
                    xAxis: {
                        type: 'category',
                        data: Config.data_line_weidu
                    },
                    yAxis: {
                        type: 'value',
                    },
                    series: [{
                        data: Config.data_line,
                        type: 'line',
                    }]
                };
                chart.setOption(option);
            }

            if(true){
                var chart = Echarts.init(document.getElementById('data_pie'), 'walden');
                var option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b}: {c} ({d}%)'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 10,
                        //data: ['微信', 'APP', 'Web在线', 'Electron']
                    },
                    series: [{
                        name: '统计',
                        type: 'pie',
                        radius: ['50%', '70%'],
                        avoidLabelOverlap: false,
                        label: {
                            normal: {
                                show: false,
                                position: 'center'
                            },
                            emphasis: {
                                show: true,
                                textStyle: {
                                    fontSize: '30',
                                    fontWeight: 'bold'
                                }
                            }
                        },
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        data:Config.data_pie,
                    }]
                };
                chart.setOption(option);
            }
  

            if(true){
                Table.api.init({
                    extend: {
                        index_url: 'csmipr/xp_dmo_dashboard/page1_list1' + location.search,
                        table: '',
                    }
                });
                var table = $("#page1_list1");
                table.bootstrapTable({
                    url: $.fn.bootstrapTable.defaults.extend.index_url,
                    pk: 'id',
                    sortName: 'weigh',
                    columns: [
                        [
                            {checkbox: true},
                            {field: 'dmo.id', title:'DMO.ID'},
                            {field: 'dmo.name', title:'DMO标题', operate: 'LIKE %...%'},
                            {field: 'user.id', title:'USER_ID'},
                            {field: 'user.nickname', title:'用户昵称', operate: 'LIKE %...%'},
                            {field: 'user.mobile', title:'用户手机', operate: 'LIKE %...%'},
                            {field: 'dmo.createtime', title:'创建时间', formatter: Table.api.formatter.datetime,operate:false},
                            {field: 'log.port', title:'端口', searchList: {"11":"手机微信","12":"手机APP","21":"PC WEB","22":"PC EXE",}, formatter: Table.api.formatter.status},
                        ]
                    ]
                });
                Table.api.bindevent(table);
            }

            if(true){
                Table.api.init({
                    extend: {
                        index_url: 'csmipr/xp_dmo_dashboard/page1_list2' + location.search,
                        table: '',
                    }
                });
                var table = $("#page1_list2");
                table.bootstrapTable({
                    url: $.fn.bootstrapTable.defaults.extend.index_url,
                    pk: 'id',
                    sortName: 'weigh',
                    columns: [
                        [
                            {checkbox: true},
                            {field: 'user.id', title:'USER_ID'},
                            {field: 'user.nickname', title:'用户昵称', operate: 'LIKE %...%'},
                            {field: 'user.mobile', title:'用户手机', operate: 'LIKE %...%'},
                            {field: 'recent_createtime', title:'最近创建任务', formatter: Table.api.formatter.datetime,operate:false},
                            {field: 'count', title:'数据',operate:false}    
                        ]
                    ]
                });
                Table.api.bindevent(table);
            }
        }
    };
    return Controller;
});
