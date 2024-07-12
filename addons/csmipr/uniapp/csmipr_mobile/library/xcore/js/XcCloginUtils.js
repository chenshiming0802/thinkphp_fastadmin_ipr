
/**
 * @usage
 * import xcCloginUtils from '@/library/xcore/js/XcCloginUtils';
 */
import config from "@/config/config.js";
import xcHttpUtils from '@/library/xcore/js/XcHttpUtils_mobile.js';
import xcViewUtils from '@/library/xcore/js/XcViewUtils_mobile.js';
import userStore from '@/store/userStore.js';


export default {
	test: function() { 
		console.log('hi');
	},
	isLogin(){
		let that = this;
		let token = that.getSessionToken();
		if(token!=null && token!=""){
			return true;
		}else{
			return false;
		}
	},
	getUserinfo(func) {
		let that = this;
		xcHttpUtils.my_get_xtype("xc", "clogin_api", "getSessionUserinfo", [], function(res) {
			func(res.userinfo);
		}, function(err) {
			return false;
		})
	}, 
	toLoginPage() {
		let that = this;
		that.clearSession();
		xcViewUtils.router_gotoPage("/pages/xclogin/login");	
	},
	toLoginPage() {
		let that = this;
		xcViewUtils.router_gotoPage("/pages/xclogin/login");
	},
	setSessionToken(code){
		let that = this;
		userStore.dispatch('login', code);
	},
	getSessionToken(code){
		let that = this;
		return userStore.state.token;
	},
	clearSession(){
		let that = this;
		userStore.dispatch('logout');
	},
 
}