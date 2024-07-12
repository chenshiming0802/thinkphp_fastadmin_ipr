/**
 * @usage
 * import xcCWeixinOaLoginUtils from '@/library/xcore/js/XcCWeixinOaLoginUtils.js';
 */
import config from "@/config/config.js";
import xcHttpUtils from '@/library/xcore/js/XcHttpUtils_mobile.js';
import xcViewUtils from '@/library/xcore/js/XcViewUtils_mobile.js';
import xcCloginUtils from '@/library/xcore/js/XcCloginUtils';


export default {
	test: function() {
		console.log('hi');
	},

	login: function(func) {
		let that = this;
		let url_token = xcHttpUtils.getUrlParam("clogintoken");
		// alert(url_token);
		if (that.isWeixinOa() && url_token==null) { //  && !xcCloginUtils.isLogin()
			let url = config.serverUrl + "api/" + config.addons + "/xc_cweixin_oa_login/login?targetUrl=" +
				encodeURIComponent(window.location);
			window.location = url;
		}
	},
	isWeixinOa() {
		let ua = navigator.userAgent.toLowerCase()
		return (/micromessenger/.test(ua))
	},
}