define(['jquery', 'table'], function ($, Table) {
	var c = {
		test: function () {
			console.log('Hello World!');
		},
	
		// 获取Fastadmin CUR的字段数组
		// 特殊处理：1）input radio获取name 2）mutils select返回数组转为字符串 3）去掉c-
		// cFaPageInputValues() {
		// 	var param = {};
		// 	$("select,input").each(function () {
		// 		var input_id = $(this).attr("id");
		// 		var input_name = $(this).attr("name");
		// 		var input_val_tmp = $(this).val();
		// 		var input_val = Array.isArray(input_val_tmp) ? input_val_tmp.join(",") : input_val_tmp;
		// 		if ($(this).attr("type") == 'radio') {
		// 			if ($(this).attr("checked") == 'checked') {
		// 				if (input_name != null && input_name.indexOf("row[") == 0) {
		// 					input_name = input_name.substring(4, input_name.length - 1);
		// 					param[input_name] = input_val;
		// 				}
		// 			}
		// 		} else {
		// 			if (input_id != null && input_id.indexOf("c-") == 0) {
		// 				param[input_id.substring(2)] = input_val;
		// 			}
		// 		}
		// 	});
		// 	return param;
		// },
		xc_tab_processfield(fieldname) {
			var xc_tabid = $("#xc_tabid", window.parent.document).val();
			$("#c-" + fieldname).val(xc_tabid); //xcframework
			$("#c-" + fieldname).attr("disabled", "true");
			$("#c-" + fieldname).after("<input type='hidden' name='row[" + fieldname + "]' value='" + xc_tabid + "'>");
		},
	};
	return c;
});