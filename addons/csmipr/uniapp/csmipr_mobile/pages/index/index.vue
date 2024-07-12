<template>
	<view>
		<uv-cell-group>
			<uv-cell title="XcDemo" isLink url="/pages/xcdemo/index"></uv-cell>
		</uv-cell-group>	
		<view @click="control_click"></view>
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
		methods: {
			control_click(){
				let that = this;
				xcViewUtils.router_gotoPage("/pages/index/index");
			}
		}
	}
</script>