<template>
	<view class="xc-wrap">
		<uv-vtabs :chain="false" :list="sr.tablist" :height="height" hdHeight="100rpx"
			@change="control_changeTabitem" @scrolltolower="control_scrolltobottom">
			<uv-vtabs-item>
				<uv-list>
					<xc-uv-list-item v-for="(item, index) in sr.list" :title="item.name"
						:note="xsDateUtils.timestampToString(item.updatetime)" :thumb="item.bannerimage" thumb-size="lg"
						clickable @click="control_clickItem(item)">
					</xc-uv-list-item>
				</uv-list>
			</uv-vtabs-item>
			<xc-uv-load-more :pageinfo="pageinfo" />
		</uv-vtabs>
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
		computed: {
			height() {
				return uni.getSystemInfoSync().windowHeight - uni.upx2px(100);
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
			control_changeTabitem(index) {
				let that = this;
				let item = that.sr.tablist[index];
				that.control.tab_object_id = item.id;
				that.public_show();
			},
			control_scrolltobottom(){
				let that = this;
				that._pagelist();
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