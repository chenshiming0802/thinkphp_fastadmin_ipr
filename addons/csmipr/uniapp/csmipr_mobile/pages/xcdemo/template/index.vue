<template>
	<view class="xc-wrap">		
		<view class="xc-block">
			<view class="xc-title">{{sr.row.name}}</view>
		</view>
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
				sp: {
					id: null
				},
				sr: {
					row: []
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
				xcHttpUtils.getById("dmo", that.sp.id, function(res) {
					xcHttpUtils.processRowImages(res.row, ["bannerimage", "images"]);
					that.sr.row = res.row;
				})
			},
		}
	}
</script>

<style lang="scss">

</style>