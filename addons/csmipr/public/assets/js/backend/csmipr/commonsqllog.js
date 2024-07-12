define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'csmipr/commonsqllog/index' + location.search,
                    add_url: 'csmipr/commonsqllog/add',
                    edit_url: 'csmipr/commonsqllog/edit',
                    del_url: 'csmipr/commonsqllog/del',
                    multi_url: 'csmipr/commonsqllog/multi',
                    import_url: 'csmipr/commonsqllog/import',
                    table: 'csmipr_commonsqllog',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'request_uuid', title: __('Request_uuid'), operate: 'LIKE'},
                        {field: 'sql', title: __('Sql'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'costtime', title: __('Costtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'baseurl', title: __('Baseurl'), operate: 'LIKE', formatter: Table.api.formatter.url},
                        {field: 'request_post', title: __('Request_post'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
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
