<template>
	<view class="xc-wrap"></view>
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
			modelValue: {
				type: Object,
				default: []
			},
			data: {
				type: Object,
				default: []
			},
		},
		watch: {
			'data': {
				handler(newVal, oldVal) {
					let that = this;
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
			},
			public_show() {
				let that = this;
			},
			public_reset() {
				let that = this;
				that.public_show();
			},
			control_click(){
				let that = this;
				that.$emit('update:modelValue', "Hello World!");
			}
		}
	}
</script>

<style lang="scss">

</style>