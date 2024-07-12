<template>
	<view class="xc-wrap">
		<uv-sticky bgColor="#fff">
			<uv-tabs :list="sr.tablist" @change="control_changeTabitem"></uv-tabs>
		</uv-sticky>
		<uv-list>
			<xc-uv-list-item v-for="(item, index) in sr.list" :title="item.name"
				:note="xsDateUtils.timestampToString(item.updatetime)" :thumb="item.bannerimage" thumb-size="lg"
				clickable @click="control_clickItem(item)">
			</xc-uv-list-item>
		</uv-list>
		<xc-uv-load-more :pageinfo="pageinfo" />
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
				pageinfo: xcHttpUtils.pageinfo(), 
				sp: {},
				sr: {
					tablist: [],
					list: [],
				},
				control: {
					tab_object_id: null,
				},
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
				that.public_reset();
				uni.stopPullDownRefresh();
			}, 1000);
		},
		onReachBottom() {
			let that = this;
			that._pagelist();
		},
		methods: {
			xinit() {
				let that = this;
				let bb = xcHttpUtils.buildparams({}, {}, "weigh", "desc");
				xcHttpUtils.queryList("dmocategory", bb, function(res) {
					that.sr.tablist = xsArrayUtils.push(res.list, {
						"name": "全部"
					});
				});
			},
			public_show() {
				let that = this;
				that.pageinfo.reset();
				that._pagelist();
			},
			public_reset() {
				let that = this;
				that.public_show();
			},
			control_changeTabitem: function(item) {
				let that = this;
				that.control.tab_object_id = item.id;
				that.public_show();
			},
			_pagelist() {
				let that = this;
				let bb = xcHttpUtils.buildparams({
					csmipr_dmocategory_id: that.control.tab_object_id
				}, {
					csmipr_dmocategory_id: '='
				}, "weigh", "desc");
				xcHttpUtils.queryPageList("dmo", bb, that.pageinfo, function(res) {
					xcHttpUtils.processListImages(res.list, ["bannerimage"]);
					that.sr.list = that.pageinfo.appendList(res.list, ["bannerimage"]);

				});
			},
			control_clickItem(item) {
				let that = this;
				xcViewUtils.router_gotoPage("/pages/xcdemo/dmo/item", {
					id: item.id
				});
			},

		}
	}
</script>

<style lang="scss">

</style>