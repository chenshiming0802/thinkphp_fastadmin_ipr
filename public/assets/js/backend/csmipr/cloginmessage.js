define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'csmipr/cloginmessage/index' + location.search,
                    add_url: 'csmipr/cloginmessage/add',
                    edit_url: 'csmipr/cloginmessage/edit',
                    del_url: 'csmipr/cloginmessage/del',
                    multi_url: 'csmipr/cloginmessage/multi',
                    import_url: 'csmipr/cloginmessage/import',
                    table: 'csmipr_cloginmessage',
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
                        {field: 'id', title: __('Id')},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'cyear', title: __('Cyear')},
                        {field: 'cmonth', title: __('Cmonth')},
                        {field: 'cdate', title: __('Cdate')},
                        {field: 'cweek', title: __('Cweek')},
                        {field: 'chour', title: __('Chour')},
                        {field: 'cmin', title: __('Cmin')},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'objectcode', title: __('Objectcode'), operate: 'LIKE'},
                        {field: 'objectid', title: __('Objectid'), operate: 'LIKE'},
                        {field: 'isread', title: __('Isread'), searchList: {"Y":__('Isread y'),"N":__('Isread n')}, formatter: Table.api.formatter.normal},
                        {field: 'readtime', title: __('Readtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), searchList: {"normal":__('Normal'),"hidden":__('Hidden')}, formatter: Table.api.formatter.status},
                        {field: 'weigh', title: __('Weigh'), operate: false},
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
