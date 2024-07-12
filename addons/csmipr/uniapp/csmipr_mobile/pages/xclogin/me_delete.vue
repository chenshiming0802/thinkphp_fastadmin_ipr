<template>
	<view class="xc-wrap">
		<view class="xc-block">
			<view class="xc-title">注销账号有以下风险</view>
			<view class="xc-content">永久注销，无法登录</view>
			<view class="xc-content">一旦注销，您在本系统的登录账号将无法继续使用，您账号下的所有的任务信息将无法恢复</view>
			<uv-button type="primary" text="确定" @click="control_submit">注销账号</uv-button>
		</view>
	</view>
</template>

<script>
	import config from "@/config/config.js";
	import xcHttpUtils from '@/library/xcore/js/XcHttpUtils_mobile.js';
	import xcViewUtils from '@/library/xcore/js/XcViewUtils_mobile.js';
	import xcCloginUtils from '@/library/xcore/js/XcCloginUtils.js';
	
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
				},
				sr: {
					row: []
				},
				control: {}
			}
		},
		onLoad(options) {
			let that = this;
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
			},
			control_submit: function() {
				let that = this;
				xcViewUtils.confirm(null, "是否确定注销账号", function() {
					xcHttpUtils.my_get_xtype("xc", 'cloginApi', "deleteUserinfo", [], function() {
						xcViewUtils.message_success('注销成功', function() {
							xcCloginUtils.clearSession();
							uni.reLaunch({
								url: "/pages/index/index"
							});
						});
					})
				})

			}
		}
	}
</script>

<style lang="scss">

</style>