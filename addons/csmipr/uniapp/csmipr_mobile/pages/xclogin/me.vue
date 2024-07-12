<template>
	<view class="xc-wrap-grey">
		<view class="xp-view-profile">
			<section>
				<uv-avatar v-if="sr.userinfo==null" text="用户" shape="circle" size="100" hairline></uv-avatar>
				<uv-avatar v-if="sr.userinfo!=null" :src="sr.userinfo.avatar" shape="circle" size="100"
					hairline></uv-avatar>
				<uv-gap height="20"></uv-gap>

				<xc-uv-clogin-mobilebutton></xc-uv-clogin-mobilebutton>

			</section>
		</view>
		<uv-gap height="20" v-if="sr.userinfo!=null"></uv-gap>
		<uv-cell-group v-if="sr.userinfo!=null">
			<uv-cell title="账号信息" isLink url="/pages/xclogin/me_profile"></uv-cell>
		</uv-cell-group>
		<uv-gap height="20"></uv-gap>
		<uv-cell-group>
			<uv-cell title="联系我们" isLink url="/pages/xclogin/policy?type=contactme"></uv-cell>
			<uv-cell title="用户协议" isLink url="/pages/xclogin/policy?type=service"></uv-cell>
			<uv-cell title="隐私政策" isLink url="/pages/xclogin/policy?type=private"></uv-cell>
		</uv-cell-group>
		<!-- #ifndef MP-WEIXIN -->
		<view class="xp-view-logout" v-if="sr.userinfo!=null">
			<uv-button type="primary" text="我要退出登录" @click="control_logout"></uv-button>
		</view>
		<!-- #endif -->
		<xa-uv-bottom-tabbar />
	</view>
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
		data() {
			return {
				config: config,
				xsStringUtils: xsStringUtils,
				xsDateUtils: xsDateUtils,
				sp: {
					id: null
				},
				sr: {
					userinfo: null
				},
				control: {}
			}
		},
		onLoad(options) {
			let that = this;
			that.sp.id = options.id;
			that.xinit();
		},
		onShow() {
			let that = this;
			that.$nextTick(function(res) {
				that.public_show();
			});
		},
		onPullDownRefresh() {
			let that = this;
			setTimeout(function() {
				that.public_show();
				uni.stopPullDownRefresh();
			}, 1000);
		},
		methods: {
			xinit() {
				let that = this;
			},
			public_reset() {
				let that = this;
				that.public_show();
			},
			public_show() {
				let that = this;
				xcCloginUtils.getUserinfo(function(userinfo) {
					that.sr.userinfo = userinfo;
				});
			},
			control_logout() {
				let that = this;
				that.sr.userinfo = null;
				// #ifdef H5 || APP-PLUS 
				xcCloginUtils.toLoginPage();
				// #endif
			},
		}
	}
</script>


<style lang="scss">
	.xp-view-profile {
		background-color: #374486;
		height: 200px;
		display: flex;
		flex-direction: column;
		align-items: center;
		padding-top: 40px;
	}

	.xp-view-logout {
		padding: $uni-spacing-row-lg $uni-spacing-row-base;
	}
</style>