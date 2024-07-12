<template>
	<view class="xc-wrap xp-wrap">
		<view class="xp-title">请输入验证码</view>
		<view class="xp-titledesc">验证码已发送至 {{sp.mobile}}</view>
		<uv-code-input v-model="control.form.captcha" @finish="control_finish" :maxlength="4" adjustPosition focus></uv-code-input>
		<uv-gap height="10" bgColor="#fff"></uv-gap>
		<uv-text type="error" :text="control.errmsg" v-if="control.errmsg!=null"></uv-text>
		<uv-gap height="10" bgColor="#fff"></uv-gap>
		<view>
			<uv-toast ref="toast"></uv-toast>
			<uv-code start-text="获取验证码" end-text="重新获取验证码" keep-running :seconds="controlcode.seconds"
				ref="code" @change="controlcode_codeChange"></uv-code>
			<text @tap="controlcode_getCode">{{controlcode.tips}}</text>
		</view>

	</view>
</template>

<script>
	import config from "@/config/config.js";
	import xcHttpUtils from '@/library/xcore/js/XcHttpUtils_mobile.js';
	import xcViewUtils from '@/library/xcore/js/XcViewUtils_mobile.js';
	import xcCloginUtils from '@/library/xcore/js/XcCloginUtils';
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
					mobile: null,
					push_clientid: null,
				},
				sr: {
					errmsg:null
				},
				control: {
					form: {
						captcha: "",
					}
					
				},
				controlcode: {
					tips: '',
					seconds: 60
				}
			}
		},
		onLoad(options) {
			let that = this;
			that.sp.mobile = options.mobile;
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
				// #ifdef APP-PLUS
				console.log("app#get that.sp.push_clientid");
				plus.push.getClientInfoAsync((info) => {
					that.sp.push_clientid = info["clientid"];
				});
				// #endif
				
				
			},
			public_show() {
				let that = this;
				that.controlcode_getCode();
			},
			public_reset() {
				let that = this;
				that.public_show();
			},
			control_finish() {
				let that = this;
				xcHttpUtils.post("/" + config.addons +'/xc_clogin_api/mobilelogin', {
					"mobile": that.sp.mobile,
					"captcha": that.control.form.captcha
				}, function(res) {

					let token = res.userinfo.token;
					xcCloginUtils.setSessionToken(token);

					// 在APP中有推送消息的clientid，保存在clogininfo表中
					if (that.sp.push_clientid != null || that.sp.push_clientid != '') {
						xcHttpUtils.my_post_xtype('xc','clogininfo', 'save', {
							'setting_key': "app_push_clientid",
							'setting_value': that.sp.push_clientid,
						}, function(res) {
							that._gotoMain();
						});
					} else {
						that._gotoMain();
					}


				},function(errmsg){
					that.control.errmsg = errmsg;
					that.control.form.captcha = "";
					return false;
				});
			},
			controlcode_codeChange(text) {
				this.controlcode.tips = text;
			},
			controlcode_getCode() {
				let that = this;
				console.log(that.$refs.code);
				if (that.$refs.code.canGetCode) {
					// 模拟向后端请求验证码
					uni.showLoading({
						title: '正在获取验证码'
					});
					xcHttpUtils.post("sms/send", {
						mobile: that.sp.mobile,
						event: "mobilelogin"
					}, function(res) {
						xcViewUtils.message_success("验证码已发送");
						that.$refs.code.start();
					}, function(e) {
						xcViewUtils.message_error(e);
					});
				} else {
					xcViewUtils.message_success('倒计时结束后再发送');
				}
			},
			_gotoMain: function() {
				let that = this;
				uni.reLaunch({
					url: '/pages/index/index'
				});
			},
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
		margin-bottom: $uni-spacing-row-base;
		color: $uni-text-color;
	}

	.xp-titledesc {
		font-size: $uni-font-size-paragraph;
		color: $uni-color-paragraph;
		margin-bottom: $uni-spacing-row-lg;
	}
</style>