<template>
	<view class="xc-wrap">

		<view class="xc-block">

			<uv-form labelPosition="left" :model="control.form" :rules="control.rules" ref="form">
				<!-- #ifndef MP-WEIXIN -->
				<uv-form-item label="姓名" prop="nickname" required borderBottom>
					<uv-input v-model="control.form.nickname" border="surround">
					</uv-input>
				</uv-form-item>
				<uv-form-item label="头像" prop="avatar" required borderBottom>
					<xc-uv-upload v-model="control.form.avatar" maxCount="1"></xc-uv-upload>
				</uv-form-item>
				<!-- #endif -->

				<!-- #ifdef MP-WEIXIN -->
				<uv-form-item label="姓名" prop="nickname" required borderBottom>
					<uv-input v-model="control.form.nickname" border="surround" type="nickname"
						@input="mp_control_changeNick">
					</uv-input>
				</uv-form-item>
				<uv-form-item label="头像" prop="avatar" :customStyle="{margin:'0px 0px 30px 0px'}" >
					<view class="xp-mp-avatar">
						<button class="avatar-wrapper uv-reset-button" open-type="chooseAvatar"
							@chooseavatar="mp_control_onChooseAvatar">
							<uv-avatar shape="square" :src="control.form.avatar">
							</uv-avatar>
						</button>
					</view>
				</uv-form-item>
				<!-- #endif -->
			</uv-form>
			<uv-button type="primary" text="保存" customStyle="margin-top: 10px" @click="control_submit"></uv-button>
			<view class="xc-afterbuton" @click="control_clickDelete">注销我的账号</view>			
		</view>
	</view>
</template>

<script>
	import config from "@/config/config.js";
	import xcViewUtils from '@/library/xcore/js/XcViewUtils_mobile.js';
	import xcHttpUtils from '@/library/xcore/js/XcHttpUtils_mobile.js';
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
				sp: {},
				sr: {},
				control: {
					form: {
						nickname: null,
						avatar: null,
					},
					rules: {
						nickname: {
							required: true,
							message: '请填写',
						},
					}
				}
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
				xcCloginUtils.getUserinfo(function(userinfo) {
					// that.control.form.nickname = userinfo.nickname;
					xcHttpUtils.row2form(userinfo,that.control.form);
				});

			},
			control_submit() {
				let that = this;
				let control = that.control;

				that.$refs.form.validate().then(res => {
					xcHttpUtils.my_post_xtype("xc", 'cloginApi', "updateUserinfo", control.form, function(res) {
						xcViewUtils.message_success('保存成功', function() {
							xcViewUtils.router_navigateBack();
						});
					});
				});
			},
			control_clickDelete() {
				let that = this;
				xcViewUtils.router_gotoPage("/pages/xclogin/me_delete")
			},
			mp_control_changeNick(e) {
				let that = this;
				let control = that.control;
				control.form.nickname = e.detail.value;
			},
			mp_control_onChooseAvatar(e) {
				let that = this;
				let control = that.control;
				control.form.avatar = e.detail.avatarUrl;
			},
		},

	}
</script>

<style lang="scss">
	.xp-mp-avatar {
		position: absolute;

		.avatar-wrapper {
			padding: 0px;
		}
	}
</style>