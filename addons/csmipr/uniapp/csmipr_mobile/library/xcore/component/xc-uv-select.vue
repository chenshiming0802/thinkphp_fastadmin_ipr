<!--
相比picker
* 支持多选单选
* 支持页面层面检索
* 支持valueField和labelField
-->
<template>
	<view class="xp-wrap">
		<!-- <uv-input v-model="control.value" border="none" placeholder="请选择"  @click="control_clickInput" /> -->
		<view class="xp-label" v-if="control.value!=null&&control.value!=''" @click="control_clickInput">{{control.value}}</view>
		<view v-else class="xp-label xp-label-placeholder" @click="control_clickInput">请选择</view>
		<uv-icon name="arrow-right" @click="control_clickInput"></uv-icon>
		<uv-popup ref="popup">
			<view style="padding: 20rpx;">
				<uv-search v-if="showSearch" @custom="controlpopup_search" @search="controlpopup_search"
					placeholder="请输入检索关键字" v-model="controlpopup.keyword"></uv-search>
				<uv-gap v-if="showSearch" height="15"></uv-gap>
				<scroll-view :scroll-top="controlpopup.scrollTop" scroll-y="true" class="xp-scroll" :enable-flex="true">
					<uv-radio-group v-if="!multiple" :borderBottom="true" iconPlacement="right" placement="column"
						v-model="controlpopup.radioValue">
						<section v-for="(item, index) in list">
							<uv-radio :customStyle="{marginBottom: '12px'}"
								v-if="item[labelField].indexOf(controlpopup.keyword)>=0" :label="item[labelField]"
								:name="item[valueField]">
							</uv-radio>
						</section>
					</uv-radio-group>
		
					<uv-checkbox-group v-if="multiple" :borderBottom="true" placement="column" iconPlacement="right"
						v-model="controlpopup.checkboxValue">
						<section v-for="(item, index) in list">
							<uv-checkbox :customStyle="{marginBottom: '12px',paddingBottom:'12px'}"
								v-if="item[labelField].indexOf(controlpopup.keyword)>=0" :label="item[labelField]"
								:name="item[valueField]+''">
							</uv-checkbox>
						</section>
					</uv-checkbox-group>
		
				</scroll-view>
				<uv-gap height="45"></uv-gap>
				<view class="bottons">
					<uv-row>
						<uv-col customStyle="padding:0 5px 10px 5px" span="6">
							<uv-button @click="control_cancel">取消</uv-button>
						</uv-col>
						<uv-col customStyle="padding:0 5px 10px 5px" span="6">
							<uv-button @click="control_confirm" type="primary">确认</uv-button>
						</uv-col>
					</uv-row>
				</view>
			</view>
		</uv-popup>
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
		props: {
			multiple: {
				type: Boolean,
				default: false
			},
			list: {
				type: Array,
				default: []
			},
			valueField: {
				type: String,
				default: "code"
			},
			labelField: {
				type: String,
				default: "name"
			},
			modelValue: {
				type: [Number, String],
				default: null
			},
			disabled: {
				type: Boolean,
				default: false
			},
			showSearch: {
				type: Boolean,
				default: true
			}
		},
		watch: {
			'modelValue': {
				handler(newVal, oldVal) {
					let that = this;
					if (that.multiple) {
						that.controlpopup.checkboxValue = (newVal + "").split(",");
					} else {
						that.controlpopup.radioValue = newVal;
					}
					that.control.value = that._getCurrentLabelFromList();
				},
			},
		},
		data() {
			return {
				config: config,
				xsStringUtils: xsStringUtils,
				xsDateUtils: xsDateUtils,
				sp: {},
				sr: {},
				control: {
					value: null,
				},
				controlpopup: {
					keyword: '',
					scrollTop: 0,
					checkboxValue: [],
					radioValue: ''
				}
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
			},
			public_show() {
				let that = this;
			},
			public_reset() {
				let that = this;
				that.public_show();
			},
			control_clickInput() {
				let that = this;
				console.log('============');
				that.$refs.popup.open('bottom');
			},
			control_confirm() {
				let that = this;
				that.control.value = that._getCurrentLabelFromList();
				if (that.multiple) {
					that.$emit('update:modelValue', that.controlpopup.checkboxValue ? that.controlpopup.checkboxValue.join(
						",") : "");
				} else {
					that.$emit('update:modelValue', that.controlpopup.radioValue);
				}
				that.$refs.popup.close();
			},
			controlpopup_search() {
				let that = this;
			},
			//点击搜索触发
			search() {
				this.$emit('search', this.controlpopup.keyword)
			},
			//点击取消按钮触发
			control_cancel() {
				let that = this;
				that.$refs.popup.close();
			},
			_getCurrentLabelFromList() {
				let that = this;
				if (!that.multiple) {
					let value = that.controlpopup.radioValue;
					for (let i = 0, j = that.list.length; i < j; i++) {
						let item = that.list[i];
						if (item[that.valueField] == value) {

							return item[that.labelField];
						}
					}
				} else {
					let sr = [];
					let values = (that.controlpopup.checkboxValue + "").split(",");
					for (let i = 0, j = that.list.length; i < j; i++) {
						let item = that.list[i];
						for (let ii = 0, jj = values.length; ii < jj; ii++) {
							if (item[that.valueField] == values[ii]) {
								sr.push(item[that.labelField]);
							}
						}
					}
					return sr.join(",");
				}
			},
		}
	}
</script>

<style lang="scss">
	.xp-scroll {
		height: 650rpx;
	}
	.xp-wrap{
		display: flex;
		width: 100%;
	}
	.xp-label{
		width: 90%;
		font-size: $uni-font-size-paragraph;
	}
	.xp-label-placeholder{
		color:$uni-text-color-grey;		
	}
</style>