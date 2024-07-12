<template>
	<view class="xc-wrap">
		<view class="xc-block">
			<view class="xc-title">{{param.title}}</view>
			<view class="xc-content" v-if="param.content!=null&&param.content!=''"><rich-text
					:nodes="param.content"></rich-text></view>
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
				sp: {
					type: null,
				},
				param: {
					title: null,
					content: null
				}
			}
		},
		onLoad(options) {
			let that = this;
			that.sp.type = options.type;
			that.xinit();
		},
		onShow() {
			let that = this;
			that.$nextTick(function() {
				that.public_show()
			});
		},
		methods: {
			xinit: function() {
				let that = this;
			},
			public_show: function() {
				let that = this;
				xcHttpUtils.get("/" + config.addons + "/xc_clogin_api/policy", {
					type: that.sp.type
				}, function(res) {
					that.param.title = res.title;
					that.param.content = res.content;
				});
			}
		}
	}
</script>

<style>

</style>