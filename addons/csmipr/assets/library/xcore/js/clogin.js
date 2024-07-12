define(['jquery', 'toastr', 'form', 'template'], 
	function ($, toastr, Form, Template) {
	var Controller = {
		mounted: function () {
			var that = this;
			that._render();
			that._bind();
		},
		_render: function () {
			var uurl = Fast.api.fixurl('/index/user/index.html')
			$('<style>.csmwj-social-login{display:flex}.csmwj-social-login a{flex:1;margin:0 2px;}.csmwj-social-login a:first-child{margin-left:0;}.csmwj-social-login a:last-child{margin-right:0;}</style>').appendTo("head");
		    $("#register-form,#login-form").append('<div class="form-group csmwj-social-login"></div>');
		        $('<a class="btn '+(window.location.href.indexOf("/wxquickerlogin.html")>=0?"btn-danger":"btn-default")+'" href="' + Fast.api.fixurl('/index/csmwj/cloginuser/wxquickerlogin.html') + '"> 微信登录</a>').appendTo(".csmwj-social-login");
		        $('<a class="btn '+(window.location.href.indexOf("/login.html")>=0?"btn-danger":"btn-default")+'" href="' + Fast.api.fixurl('/index/user/login.html') + '?url='+uurl+'"> 账号登录</a>').appendTo(".csmwj-social-login"); 
 		        $('<a class="btn '+(window.location.href.indexOf("/mobilequickerlogin.html")>=0?"btn-danger":"btn-default")+'" href="' + Fast.api.fixurl('/index/csmwj/cloginuser/mobilequickerlogin.html') + '"> 手机登录</a>').appendTo(".csmwj-social-login");
		 	if(this._isWeixin()){
				if(confirm("检测到您在微信浏览器中，请确认是否使用微信登录")){
					Fast.api.ajax({
					    url: Fast.api.fixurl("/api/csmwj/cloginweixinajax/getweixinh5mobileurl"),
					    type: "post",
					}, function (data) {	
						var selfurl =window.location.protocol + '//' + window.location.hostname+Fast.api.fixurl("/index/csmwj/cloginuser/loginbyweixincodeonweixinbrowers.html");
						var url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="+data.cloginwxappid+"&redirect_uri="+selfurl+"&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
						window.location = url;
						return false;
					});		
				}
			}
		},
		_bind: function () {

		},
		_isWeixin:function(){
			let ua = navigator.userAgent.toLowerCase();
			let isWeixin = ua.indexOf('micromessenger') != -1;
			return isWeixin;
		}
	};
	return Controller;
});