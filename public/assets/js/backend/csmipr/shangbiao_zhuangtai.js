define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        _queryString: '',
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'csmipr/shangbiao_zhuangtai/index' + Controller._queryString,
                    add_url: 'csmipr/shangbiao_zhuangtai/add' + Controller._queryString,
                    edit_url: 'csmipr/shangbiao_zhuangtai/edit',
                    del_url: 'csmipr/shangbiao_zhuangtai/del',
                    multi_url: 'csmipr/shangbiao_zhuangtai/multi',
                    import_url: 'csmipr/shangbiao_zhuangtai/import',
                    table: 'csmipr_shangbiao_zhuangtai',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'uuid', title: __('Uuid'), operate: 'LIKE'},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.rebuildAddUrl(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            queryString: function () {
                return location.search.replace("dialog=1", "").split('&').filter(function (item) {
                    return !!item;
                }).join("&");
            },
            rebuildAddUrl: function (table) {
                var $tabs = $('.nav-tabs[data-field]');
                if ($tabs.length > 0) {
                    var field = $tabs.data("field");
                    var options = table.bootstrapTable('getOptions');
                    table.on("pre-body.bs.table", function () {
                        var activeTab = $('.active a', $tabs);
                        var value = activeTab.data("value");
                        var reg = new RegExp(field + "\=(.*?)");
                        var queryString = location.search
                            .replace("dialog=1", "")
                            .replace(reg, "")
                            .split('&')
                            .filter(function (item) {
                                return !!item;
                            }).join("&");
                        if (queryString.indexOf("?") == 0) {
                            queryString = queryString + "&" + field + "=" + value
                        } else {
                            queryString = queryString + "?" + field + "=" + value
                        }
                        options.extend.add_url = 'csmipr/shangbiao_zhuangtai/add' + queryString
                    })
                }
            }
        }
    };
    Controller._queryString = Controller.api.queryString();
    return Controller;
});