<template>
	<view class="xc-wrap xp-wrap">
		<view class="xp-title">欢迎登录{{config.projectname}}</view>
		<uv-input type="number" border="bottom" v-model="control.mobile" placeholder="请输入手机号" />
		<view class="xp-tips">未注册的手机号验证后自动创建美团账号</view>
		<uv-button size="large" :custom-style="inputStyle" text="获取短信验证码" @click="control_click"></uv-button>
		<view class="xp-agreement">
			<xc-uv-single-checkbox v-model="control.agreementChecked"></xc-uv-single-checkbox>
			我已经阅读并同意<text class="link">《用户协议》</text>和<text class="link">《隐私政策》</text>
		</view>

		<uv-modal ref="aggreementModal" @confirm="control_clickAgreement" showCancelButton confirmText="我阅读并同意">
			<view class="slot-content">
				请阅读<text class="link">《用户协议》</text>和<text class="link">《隐私政策》</text>
			</view>
		</uv-modal>
	</view>
</template>

<script>
	import config from "@/config/config.js";
	import xcHttpUtils from '@/library/xcore/js/XcHttpUtils_mobile.js';
	import xcViewUtils from '@/library/xcore/js/XcViewUtils_mobile.js';
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
				sp: {},
				sr: {},
				control: {
					mobile: null,
					agreementChecked: "N",
				},
			}
		},
		computed: {
			inputStyle() {
				let style = {};
				if (this.control.mobile == null || this.control.mobile == '') {
					style.color = "#999";
					style.backgroundColor = "#fdf3d0";
				} else {
					style.color = "#fff";
					style.backgroundColor = "#f0ad4e";
				}
				return style;
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
		methods: {
			xinit() {
				let that = this;
			},
			public_show() {
				let that = this;
			},
			public_reset() {
				let that = this;
				that.public_show();
			},
			control_click() {
				let that = this;
				if (that.control.mobile === '') {
					xcViewUtils.message_error("手机号不能为空!");
					return;
				} else if (!/^(13[0-9]|14[01456879]|15[0-35-9]|16[2567]|17[0-8]|18[0-9]|19[0-35-9])\d{8}$/.test(
						that.control.mobile)) {
					xcViewUtils.message_error("手机号格式错误!");
					return;
				}
				if (that.control.agreementChecked == 'N') {
					that.$refs['aggreementModal'].open();
					return;
				}
				xcViewUtils.router_gotoPage("/pages/xclogin/login_mobilecaptcha", {
					mobile: that.control.mobile
				})

			},
			control_clickAgreement() {
				let that = this;
				that.control.agreementChecked = "Y";
				that.control_click();
			}
		}
	}
</script>

<style lang="scss">
	.xp-wrap {
		padding-left: 30px;
		padding-right: 30px;
	}

	.xp-title {
		padding-top: 50px;
		text-align: left;
		font-size: 30px;
		font-weight: 500;
		margin-bottom: 50px;
		color: $uni-text-color;
	}

	.xp-tips {
		margin-bottom: 60px;
		margin-top: $uni-spacing-row-sm;
		font-size: $uni-font-size-sm;
		padding-left: $uni-font-size-sm;
		color: $uni-text-color-grey;
	}

	.xp-agreement {
		font-size: $uni-font-size-base;
		position: absolute;
		bottom: $uni-spacing-row-lg;
		color: $uni-text-color-grey;
	}
</style>