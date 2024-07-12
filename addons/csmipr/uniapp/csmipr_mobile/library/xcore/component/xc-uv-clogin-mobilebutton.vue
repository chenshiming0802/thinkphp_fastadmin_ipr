<template>
	<!-- #ifndef MP-WEIXIN -->
	<uv-button v-if="sr.userinfo==null|| sr.userinfo.mobile==null||sr.userinfo.mobile==''" text="立即登录" size="normal" @click="control_clickLogin"></uv-button>
	<text v-if="sr.userinfo!=null && sr.userinfo.mobile!=null && sr.userinfo.mobile!=''" class="xp-nickname">{{sr.userinfo.nickname}}</text>
	<!-- #endif -->
	<!-- #ifdef MP-WEIXIN -->
	<button v-if="sr.userinfo==null|| sr.userinfo.mobile==null||sr.userinfo.mobile==''"
		class="uv-reset-button" open-type="getPhoneNumber" @getphonenumber="mp_control_getPhoneNumber">立即登录</button>
	<text v-else class="xp-nickname">{{sr.userinfo.nickname}}</text>
	<!-- #endif -->
</template>
<script>
	import config from "@/config/config.js";
	import xcHttpUtils from '@/library/xcore/js/XcHttpUtils_mobile.js';
	import xcViewUtils from '@/library/xcore/js/XcViewUtils_mobile.js';
	import xcCloginUtils from '@/library/xcore/js/XcCloginUtils.js';
	import xcCWeixinMpLoginUtils from '@/library/xcore/js/XcCWeixinMpLoginUtils.js';
	import {
		xsStringUtils,
		xsDateUtils,
		xsArrayUtils
	} from 'xstack-for-javascript';

	export default {
		props: {
		},
		watch: {
		},
		data() {
			return {
				config: config,
				xsStringUtils: xsStringUtils,
				xsDateUtils: xsDateUtils,
				sp: {},
				sr: {
					userinfo: null
				},
				control: {}
			}
		},
		created() {
			let that = this;
			that.xinit();
			that.public_show();
		},
		methods: {
			xinit() {
				let that = this;
				xcCloginUtils.getUserinfo(function(userinfo) {
					that.sr.userinfo = userinfo;
				});
			},
			public_show() {
				let that = this;
			},
			public_reset() {
				let that = this;
				that.public_show();
			},
			control_clickLogin() {
				let that = this;
				xcCloginUtils.toLoginPage();
			},
			mp_control_getPhoneNumber(e) {
				let that = this;
				xcCWeixinMpLoginUtils.getPhoneNumber(e, function(user) {
					that.sr.userinfo = user;
				});
			},
		}
	}
</script>

<style lang="scss">
	.xp-nickname {
		color: #ffffff;
		text-align: center;
		display: block;
	}
</style>