define(['jquery', 'bootstrap', 'backend', 'table', 'form','csmipr_xcore'], function ($, undefined, Backend, Table, Form,xcore) {

    var Controller = {
        _queryString: '',
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'csmipr/dmo/index' + Controller._queryString,
                    add_url: 'csmipr/dmo/add' + Controller._queryString,
                    edit_url: 'csmipr/dmo/edit',
                    del_url: 'csmipr/dmo/del',
                    multi_url: 'csmipr/dmo/multi',
                    import_url: 'csmipr/dmo/import',
                    table: 'csmipr_dmo',
                }
            });

            Fast.config.openArea = ['98%','98%'];
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
                        {field: 'csmipr_dmoapply_id', title: __('Csmipr_dmoapply_id')},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'csmipr_dmocategory_id', title: __('Csmipr_dmocategory_id')},
                        {field: 'csmipr_dmocategory_ids', title: __('Csmipr_dmocategory_ids'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'), searchList: {"T1":__('Type t1'),"T2":__('Type t2')}, formatter: Table.api.formatter.normal},
                        {field: 'types', title: __('Types'), searchList: {"T1":__('Types t1'),"T2":__('Types t2')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'isread', title: __('Isread'), searchList: {"Y":__('Isread y'),"N":__('Isread n')}, formatter: Table.api.formatter.normal},
                        {field: 'bannerimage', title: __('Bannerimage'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'images', title: __('Images'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.images},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'status', title: __('Status'), searchList: {"normal":__('Normal'),"hidden":__('Hidden')}, formatter: Table.api.formatter.status},
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
            },
            queryString: function () {
                return location.search.replace("dialog=1", "").split('&').filter(function (item) {
                    return !!item;
                }).join("&");
            }
        }
    };
    Controller._queryString = Controller.api.queryString();
    return Controller;
});
