define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'csmipr/cloginthird/index' + location.search,
                    add_url: 'csmipr/cloginthird/add',
                    edit_url: 'csmipr/cloginthird/edit',
                    del_url: 'csmipr/cloginthird/del',
                    multi_url: 'csmipr/cloginthird/multi',
                    import_url: 'csmipr/cloginthird/import',
                    table: 'csmipr_cloginthird',
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
                        {field: 'platform', title: __('Platform'), operate: 'LIKE'},
                        {field: 'platformid', title: __('Platformid'), operate: 'LIKE'},
                        {field: 'apptype', title: __('Apptype'), operate: 'LIKE'},
                        {field: 'unionid', title: __('Unionid'), operate: 'LIKE'},
                        {field: 'openid', title: __('Openid'), operate: 'LIKE'},
                        {field: 'openname', title: __('Openname'), operate: 'LIKE'},
                        {field: 'access_token', title: __('Access_token'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'refresh_token', title: __('Refresh_token'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'expires_in', title: __('Expires_in')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'logintime', title: __('Logintime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'expiretime', title: __('Expiretime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'avatarurl', title: __('Avatarurl'), operate: 'LIKE', formatter: Table.api.formatter.url},
                        {field: 'city', title: __('City'), operate: 'LIKE'},
                        {field: 'country', title: __('Country'), operate: 'LIKE'},
                        {field: 'gender', title: __('Gender'), operate: 'LIKE'},
                        {field: 'language', title: __('Language'), operate: 'LIKE'},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'province', title: __('Province'), operate: 'LIKE'},
                        {field: 'phonenumber', title: __('Phonenumber'), operate: 'LIKE'},
                        {field: 'purephonenumber', title: __('Purephonenumber'), operate: 'LIKE'},
                        {field: 'countrycode', title: __('Countrycode'), operate: 'LIKE'},
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
            }
        }
    };
    return Controller;
});
