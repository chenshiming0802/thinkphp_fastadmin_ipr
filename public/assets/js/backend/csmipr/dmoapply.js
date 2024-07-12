define(['jquery', 'bootstrap', 'backend', 'table', 'form','csmipr_xcore'], function ($, undefined, Backend, Table, Form,xcore) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'csmipr/dmoapply/index' + location.search,
                    add_url: 'csmipr/dmoapply/add',
                    edit_url: 'csmipr/dmoapply/edit',
                    del_url: 'csmipr/dmoapply/del',
                    multi_url: 'csmipr/dmoapply/multi',
                    import_url: 'csmipr/dmoapply/import',
                    table: 'csmipr_dmoapply',
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
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'csmipr_dmocategory_name', title: __('Csmipr_dmocategory_id')},
                        {field: 'csmipr_dmocategorys_name', title: __('Csmipr_dmocategory_ids'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'), searchList: {"T1":__('Type t1'),"T2":__('Type t2')}, formatter: Table.api.formatter.normal},
                        {field: 'types', title: __('Types'), searchList: {"T1":__('Types t1'),"T2":__('Types t2')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'bannerimage', title: __('Bannerimage'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'images', title: __('Images'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.images},
                        {field: 'auditcontent', title: __('auditcontent'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'status', title: __('Status'), searchList: {"hidden":__('Status hidden'),"normal":__('Status normal'),"draft":__('Status draft'),"toaudit":__('Status toaudit'),"reject":__('Status reject')}, formatter: Table.api.formatter.status},
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

            var param = [];
            $("select,input").each(function(){
                if($(this).attr("type")=='radio'){
                    if($(this).attr("checked")=='checked'){
                        console.log($(this).attr("name"),'=',$(this).val());
                        param[$(this).attr("id")] = $(this).val();
                    }
                }else{
                    console.log($(this).attr("id"),$(this).attr("name"),'=',$(this).val());
                    param[$(this).attr("id")] = $(this).val();
                }
            });
            console.log(param);

            // csmipr: 根据不同的状态,显示不同的字段 , -2=申请撤销,-1=审核退回,0=待审核,1=审核通过
            $("input[name='row[status]']").attr("disabled", 'true');
            switch ($("[name='row[status]']:checked").val()) {
                case 'toaudit':
                    $(".btnok").css("display", "inline");
                    $(".btnno").css("display", "inline");
                    break;
                case 'reject':
                    $(".btnok").css("display", "inline");
                    break;
            }
            console.log(xcore.cFaPageInputValues());
            $(".btnok").click(function () {
                Layer.confirm('请确定是否执行审核通过操作', function (index) {
                    Fast.api.ajax({
                        url: Fast.api.fixurl("csmipr/dmoapply/submitAudit"),
                        type: "post",
                        data: xcore.cFaPageInputValues(),
                    }, function (data, ret) {
                        layer.close(index);
                        window.location.reload();
                        return false;
                    });
                });
            });
            $(".btnno").click(function () {
                Layer.open({
                    title: '审核退回',
                    content: '\
            	        <div class="form-group">\
            	            <label class="control-label" for="account">请填写退回原因</label>\
            	    	<input id="xp-auditcontent" class="form-control" name="auditcontent" type="text" value="">\
            	        </div>\
            	    ',
                    yes: function (index, layero) {
                        Fast.api.ajax({
                            url: Fast.api.fixurl("csmipr/dmoapply/submitReject"),
                            type: "post",
                            data: {
                                id: $("#c-id").val(),
                                auditcontent: $("#xp-auditcontent").val()
                            },
                        }, function (data, ret) {
                            layer.close(index);
                            window.location.reload();
                            return false;
                        });

                    }
                });
            });


        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
