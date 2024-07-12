<template>
	<view class="xc-wrap-grey">
		<uv-image :src="sr.row.bannerimage" width="100%" :observeLazyLoad="true"/>
		
		<view class="xc-block">
			<view class="xc-title">{{sr.row.name}}</view>
			<view class="xc-titledesc">{{"发布时间："+xsDateUtils.timestampToString(sr.row.updatetime)}}</view>
			<uv-line :hairline="false" margin="10px 0px"></uv-line>
			<view class="xc-content"><uv-parse :content="sr.row.content"/></view>
			<uv-image v-for="item in sr.row.images" :showLoading="true" :src="item" width="100%" height="180px"/>
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
			public_show() {
				let that = this;
				xcHttpUtils.getById("dmo", that.sp.id, function(res) {
					xcHttpUtils.processRowImages(res.row, ["bannerimage", "images"]);
					that.sr.row = res.row;
				})
			},
			public_reset() {
				let that = this;
				that.public_show();
			}
		}
	}
</script>

<style lang="scss">

</style>