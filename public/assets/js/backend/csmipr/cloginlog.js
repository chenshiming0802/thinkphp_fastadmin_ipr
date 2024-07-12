define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'csmipr/cloginlog/index' + location.search,
                    add_url: 'csmipr/cloginlog/add',
                    edit_url: 'csmipr/cloginlog/edit',
                    del_url: 'csmipr/cloginlog/del',
                    multi_url: 'csmipr/cloginlog/multi',
                    import_url: 'csmipr/cloginlog/import',
                    table: 'csmipr_cloginlog',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'operate', title: __('Operate')},
                        {field: 'port', title: __('Port')},
                        {field: 'object_id', title: __('Object_id')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'cyear', title: __('Cyear')},
                        {field: 'cmonth', title: __('Cmonth')},
                        {field: 'cdate', title: __('Cdate')},
                        {field: 'cweek', title: __('Cweek')},
                        {field: 'chour', title: __('Chour')},
                        {field: 'cmin', title: __('Cmin')},
                        {field: 'septime', title: __('Septime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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
            }
        }
    };
    return Controller;
});
