<template>
	<view class="xc-wrap">
		<view class="xc-block">
			<uv-form labelPosition="left" :model="control.form" :rules="control.rules" ref="form" labelWidth="80px">
				<uv-form-item label="名称" prop="name" required borderBottom>
					<uv-input v-model="control.form.name" border="none" placeholder="请输入">
					</uv-input>
				</uv-form-item>
				<uv-form-item label="分类" prop="csmipr_dmocategory_id" required borderBottom>
					<xc-uv-select v-model="control.form.csmipr_dmocategory_id" :list="sr.dmocategorys"
						labelField="name" valueField="id"></xc-uv-select>
				</uv-form-item>
				<uv-form-item label="分类(多选)" prop="csmipr_dmocategory_ids" required borderBottom>
					<xc-uv-select v-model="control.form.csmipr_dmocategory_ids" :list="sr.dmocategorys"
						labelField="name" valueField="id" multiple></xc-uv-select>
				</uv-form-item>
				<uv-form-item label="类型" prop="type" required borderBottom>
					<xc-uv-select v-model="control.form.type" :list="sr.dicts['type']"></xc-uv-select>
				</uv-form-item>
				<uv-form-item label="类型(多选)" prop="types" required borderBottom>
					<xc-uv-select v-model="control.form.types" :list="sr.dicts['type']" multiple></xc-uv-select>
				</uv-form-item>
				<uv-form-item label="文件" prop="bannerimage" required borderBottom>
					<xc-uv-upload v-model="control.form.bannerimage" maxCount="1"></xc-uv-upload>
				</uv-form-item>
				<uv-form-item label="文件(多个)" prop="images" required borderBottom>
					<xc-uv-upload v-model="control.form.images" maxCount="10" multiple></xc-uv-upload>
				</uv-form-item>
				<uv-form-item label="正文" prop="content" required borderBottom>
					<uv-textarea v-model="control.form.content" placeholder="请输入内容"></uv-textarea>
				</uv-form-item>
			</uv-form>
		</view>
		<view class="xc-block-bottom-fill"></view>
		<view class="xc-block-bottom">
			<view class="xc-two-button">
				<uv-button type="primary" plain text="保存" size="small" @click="control_save"></uv-button>
				<uv-button type="primary" text="提交" size="small" @click="control_submit"></uv-button>
			</view>
		</view>
	</view>
</template>

<script>
	import config from "@/config/config.js";
	import xcViewUtils from '@/library/xcore/js/XcViewUtils_mobile.js';
	import xcHttpUtils from '@/library/xcore/js/XcHttpUtils_mobile.js';
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
				sr: {
					dicts: [],
					dmocategorys: [],
				},
				control: {
					form: {
						id: null,
						name: null,
						csmipr_dmocategory_id: null,
						csmipr_dmocategory_ids: null,
						type: null,
						types: null,
						content: null,
						bannerimage: null,
						images: null,
					},
					rules: {
						name: {
							required: true,
							message: '请填写',
						},
						csmipr_dmocategory_id: {
							required: true,
							message: '请选择',
						},
						csmipr_dmocategory_ids: {
							required: true,
							message: '请选择',
						},
						type: {
							required: true,
							message: '请选择',
						},
						types: {
							required: true,
							message: '请选择',
						},
					}
				}
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
				xcHttpUtils.get_dicts("dmoapply", function(res) {
					that.sr.dicts = res.list;
				});
				let bb = xcHttpUtils.buildparams({}, {}, "weigh", "desc");
				xcHttpUtils.queryList("dmocategory", bb, function(res) {
					that.sr.dmocategorys = res.list;
				});
			},
			public_reset() {
				let that = this;
				that.public_show();
			},
			public_show() {
				let that = this;
				if (that.sp.id != null) {
					xcHttpUtils.my_getById("dmoapply", that.sp.id, function(res) {
						xcHttpUtils.row2form(res.row, that.control.form)
					});
				}
			},
			control_save() {
				let that = this;
				that.control.form.status = "draft";
				xcHttpUtils.my_post("dmoapply", "createOrUpdate", that.control.form, function(res) {
					xcHttpUtils.row2form(res.row, that.control.form);
					return true;
				});
			},
			control_submit() {
				let that = this;

				that.control.form.status = "toaudit";
				that.$refs.form.validate().then(res => {
					xcHttpUtils.my_post("dmoapply", "createOrUpdate", that.control.form, function(res) {
						xcHttpUtils.row2form(res.row, that.control.form);
						return true;
					});
				});
			},
			control_clickDelete() {
				let that = this;
				xcViewUtils.router_gotoPage("/pages/xclogin/me_delete")
			},
		},

	}
</script>

<style lang="scss">
	.uv-demo-block__content {
		flex-direction: row;
		flex-wrap: wrap;
		align-items: center;
	}
</style>