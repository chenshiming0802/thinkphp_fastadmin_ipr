<script>
	// #ifdef MP-WEIXIN
	import xcCWeixinMpLoginUtils from '@/library/xcore/js/XcCWeixinMpLoginUtils.js';
	// #endif
	// #ifdef WEB
	import xcCWeixinOaLoginUtils from '@/library/xcore/js/XcCWeixinOaLoginUtils.js';
	// #endif


	export default {
		onLaunch: function() {
			console.log('App Launch')

			// #ifdef MP-WEIXIN
			xcCWeixinMpLoginUtils.login(function(user) {});
			// #endif
			// #ifdef WEB
			xcCWeixinOaLoginUtils.login(function(user) {});
			// #endif
			
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style lang="scss">
	@import "@/uni_uv.scss";
	@import "@/uni_xc.scss";
	@import "@/uni_xa.scss";
</style>