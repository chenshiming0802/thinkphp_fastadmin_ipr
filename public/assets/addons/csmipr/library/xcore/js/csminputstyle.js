/*
usage:
<html>
 <head>
 	<meta charset="utf-8">
	<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="jquery.simple-color.js"></script>
	<script type="text/javascript" src="csminputstyle.js"></script>
	<link rel="stylesheet" href="csminputstyle.css" />
 </head>
 <body>
	<div id="t1">AAAA</div><div id="csminputstyle1"></div>
 </body>
</html>
<script>
	$(document).ready(function() {
		var t1 = $('#csminputstyle1').csminputstyle('font-size:19px;color:#ff00ee',function(styleString){
			$("#t1").attr("style",styleString);
		},3);
	});
</script>
*/
(function ($) {
	/*
	styleString:默认style
	callback：回调方法 ，function(styleString)
	type:类型 1-font，2-div，3-font&div，4-color，0-自定义
	*/
	$.fn.csminputstyle = function(styleString,callback,type,customizetype) {
		var that = this;
		that.typevalue = null;

		that.inputb = false;
		that.inputi = false;
		that.inputu = false;
		
		that.in_array = function(arr,val){
			for(let index in arr){
				if(val==arr[index]){
					return true;  
				}
			}
			return false;
		}

		switch(type){
			case 0:
				that.typevalue = customizetype;
				break;
			case 1://font style
				that.typevalue = ['font-size','color','b','i','u'];
				break;
			case 2://div style
				that.typevalue = ['background-color','text-align','padding','margin'];
				break;
			case 3://div style
				that.typevalue = ['font-size','color','b','i','u','background-color','text-align','padding','margin'];
				break;
			case 4://color
				that.typevalue = ['onlycolor'];
				if(styleString!=null&&styleString!=""){
					styleString = "onlycolor:"+styleString;
				}
				break;
		}	

		// 生成dom表单
		that.container = null;
		if(true){
			// 生成字体选择项 12-28
			if(that.in_array(that.typevalue,'font-size')){
				var optionstr = ""; 
				for(var i=12;i<28;i++){
					optionstr += "<option value='"+i+"px'>  "+i+" px  </option>";
				}
				// 生成dom：字体选择、颜色选择、粗体、斜体、下划线
				$(that).append("字体大小<select class='csminputstyle-input-size'>"+optionstr+"</select>");
			}
			if(that.in_array(that.typevalue,'color')){
				$(that).append("<input class='csminputstyle-input-color' value='#000000'/>");
			}
			if(that.in_array(that.typevalue,'onlycolor')){
				$(that).append("<input class='csminputstyle-input-onlycolor' value='#000000'/>");
			}
			if(that.in_array(that.typevalue,'b')){
				$(that).append("<div class='csminputstyle-input-b'>B</div>");
			}
			if(that.in_array(that.typevalue,'i')){
				$(that).append("<div class='csminputstyle-input-i'>I</div>");
			}
			if(that.in_array(that.typevalue,'u')){
				$(that).append("<div class='csminputstyle-input-u'>U</div>");
			}		
			//如果又有font和div，则文字和div设置多个回车
			if(type==3){
				$(that).append("<BR>");
			}
			if(that.in_array(that.typevalue,'background-color')){
				$(that).append("背景颜色<input class='csminputstyle-input-background-color' value='#ffffff'/>");
			}
			if(that.in_array(that.typevalue,'text-align')){
				var optionstr = ""; 
				optionstr += "文字对齐<option value='left'>居左</option>";
				optionstr += "<option value='center'>居中</option>";
				optionstr += "<option value='right'>居右</option>";
				$(that).append("<select class='csminputstyle-input-text-align'>"+optionstr+"</select>");
			}
			if(that.in_array(that.typevalue,'padding')){
				var optionstr = ""; 
				for(var i=0;i<=50;i++){
					optionstr += "<option value='"+i+"px'>  "+i+" px  </option>";
				}
				// 生成dom：字体选择、颜色选择、粗体、斜体、下划线
				$(that).append("间距(上右下左)<select class='csminputstyle-padding-top'>"+optionstr+"</select>");
				$(that).append("<select class='csminputstyle-padding-right'>"+optionstr+"</select>");
				$(that).append("<select class='csminputstyle-padding-bottom'>"+optionstr+"</select>");
				$(that).append("<select class='csminputstyle-padding-left'>"+optionstr+"</select>");
			}
			that.container = $(that).append("");
		}

		// 用于粗体、斜体、下划线选择后，加亮标记和触发回调（私有方法，仅在BIU被调整后触发）
		that._renderBIUDom = function(val,fonttype){
			if(val===true){
				that.container.find('.csminputstyle-input-'+fonttype).addClass("csminputstyle-input-active");
			}else{
				that.container.find('.csminputstyle-input-'+fonttype).removeClass("csminputstyle-input-active");
			}

		}
		that._getStyleStub = function(styleKey,jqueryDomname){
			let val = that.container.find(jqueryDomname).val();
			if(val!=null&&val!=''){
				//如果styleKey为空，则直接返回jqueryDomname，否则返回 key:value;
				if(styleKey!=null && styleKey!=''){
					return styleKey+":"+val+";";
				}else{
					return val;
				}
				//return styleKey+((styleKey==null||styleKey=='')?"":":")+val+";"+((styleKey==null||styleKey=='')?"":";");
			}else{
				return "";
			}
		}
		// 共有方法：返回style字符串,如：font-size:12px;color:#000000;....
		that.getStyleString = function(){
			var style = "";

			style += that._getStyleStub("font-size",".csminputstyle-input-size");
			style += that._getStyleStub("color",".csminputstyle-input-color");
			style += that._getStyleStub("",".csminputstyle-input-onlycolor");
			// style += "font-size:"+that.container.find('.csminputstyle-input-size').val()+";";
			// style += "color:"+that.container.find('.csminputstyle-input-color').val()+";";

			style += (that.inputb===true)?"font-weight:bold;":"";
			style += (that.inputi===true)?"font-style:italic;":"";
			style += (that.inputu===true)?"text-decoration:underline;":"";

			style += that._getStyleStub("background-color",".csminputstyle-input-background-color");
			style += that._getStyleStub("text-align",".csminputstyle-input-text-align");
			style += that._getStyleStub("padding-top",".csminputstyle-padding-top");
			style += that._getStyleStub("padding-right",".csminputstyle-padding-right");
			style += that._getStyleStub("padding-bottom",".csminputstyle-padding-bottom");
			style += that._getStyleStub("padding-left",".csminputstyle-padding-left");

			// style += "background-color:"+that.container.find('.csminputstyle-input-background-color').val()+";";
			// style += "text-align:"+that.container.find('.csminputstyle-input-text-align').val()+";";
			// style += "padding-top:"+that.container.find('.csminputstyle-padding-top').val()+";";
			// style += "padding-right:"+that.container.find('.csminputstyle-padding-right').val()+";";
			// style += "padding-bottom:"+that.container.find('.csminputstyle-padding-bottom').val()+";";
			// style += "padding-left:"+that.container.find('.csminputstyle-padding-left').val()+";";

			return style;
		}
		// 共有方法：返回style数组字符串,如：{font-size:12px,color:#000000,....}
		that.getStyleArr = function(){
			var getStyleArr = {};
			getStyleArr['font-size'] = that.container.find('.csminputstyle-input-size').val();
			getStyleArr['color'] = that.container.find('.csminputstyle-input-color').val();
			getStyleArr['onlycolor'] = that.container.find('.csminputstyle-input-onlycolor').val();
			if((that.inputb===true)){
				getStyleArr['font-weight'] = 'bold';
			}else{
				getStyleArr['font-weight'] = 'normal';
			}
			if((that.inputi===true)){
				getStyleArr['font-style'] = 'italic';
			}else{
				getStyleArr['font-style'] = 'normal';
			}
			if((that.inputu===true)){
				getStyleArr['text-decoration'] = 'underline';
			}else{
				getStyleArr['text-decoration'] = 'none';
			}
			//console.log(getStyleArr);
			return getStyleArr;
		}
		// 事件绑定
		that.container.on('change', '.csminputstyle-input-size', function(){
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('change', '.csminputstyle-input-color', function(){
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('change', '.csminputstyle-input-onlycolor', function(){
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('click', '.csminputstyle-input-b', function(){
			that.inputb = (that.inputb===true)?false:true;
			that._renderBIUDom(that.inputb,'b');
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('click', '.csminputstyle-input-i', function(){
			that.inputi = (that.inputi===true)?false:true;
			that._renderBIUDom(that.inputi,'i');
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('click', '.csminputstyle-input-u', function(){
			that.inputu = (that.inputu===true)?false:true;
			that._renderBIUDom(that.inputu,'u');
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('change', '.csminputstyle-input-background-color', function(){
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('change', '.csminputstyle-input-text-align', function(){
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('change', '.csminputstyle-padding-top', function(){
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('change', '.csminputstyle-padding-right', function(){
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('change', '.csminputstyle-padding-bottom', function(){
			if(callback){
				callback(that.getStyleString());
			}
		});
		that.container.on('change', '.csminputstyle-padding-left', function(){
			if(callback){
				callback(that.getStyleString());
			}
		});


		
		// 初始化dom的状态和数据
		if(styleString!=null && styleString!=''){
			//var styleString = "font-size:12px;color:red;";
			var styleStringArr = styleString.split(";");
			var styleArr = [];
			for(var index in styleStringArr){
				//console.log(styleStringArr[index]);
				var stubs = styleStringArr[index].split(":");
				if(stubs.length==2){
					styleArr[stubs[0].toLowerCase()] = stubs[1].toLowerCase();
				}
			}
			that.inputb = (styleArr["font-weight"]=="bold")?true:false;
			that.inputi = (styleArr["font-style"]=="italic")?true:false;
			that.inputu = (styleArr["text-decoration"]=="underline")?true:false;
			that._renderBIUDom(that.inputb,'b');
			that._renderBIUDom(that.inputi,'i');
			that._renderBIUDom(that.inputu,'u');
			that.container.find('.csminputstyle-input-size').val(styleArr["font-size"]);
			that.container.find('.csminputstyle-input-color').val(styleArr["color"]);
			that.container.find('.csminputstyle-input-onlycolor').val(styleArr["onlycolor"]);

			that.container.find('.csminputstyle-input-background-color').val(styleArr["background-color"]);
			that.container.find('.csminputstyle-input-text-align').val(styleArr["text-align"]);
			that.container.find('.csminputstyle-padding-top').val(styleArr["padding-top"]);
			that.container.find('.csminputstyle-padding-right').val(styleArr["padding-right"]);
			that.container.find('.csminputstyle-padding-bottom').val(styleArr["padding-bottom"]);
			that.container.find('.csminputstyle-padding-left').val(styleArr["padding-left"]);
		}

		// 初始化color picker组建
		that.container.find('.csminputstyle-input-color').simpleColor({ displayColorCode: true });
		that.container.find('.csminputstyle-input-onlycolor').simpleColor({ displayColorCode: true });
		that.container.find('.csminputstyle-input-background-color').simpleColor({ displayColorCode: true });
		// 回到方法，用户初始化render
		if(type==4){
			let param = "";
			if(styleArr!=null){
				param = styleArr["onlycolor"];
			}
			callback(param);
		}else{
			callback(that.getStyleString());
		}
		

		return that;
	};
}) (jQuery);