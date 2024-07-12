<template>
	<uv-tabbar :value="control.index" :fixed="true" safeAreaInsetBottom border @change="control_change">
		<uv-tabbar-item v-for="item in control.datas" :text="item.name" :icon="item.icon"></uv-tabbar-item>
	</uv-tabbar>
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
				control: {
					index:0,
					datas:[
						{name:"首页",icon:"home",path:"/pages/xcdemo/index"},
						{name:"我的",icon:"account",path:"/pages/xclogin/me"}
					]
				}
			}
		},
		created(){
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
				
				const currentPage = getCurrentPages().pop();
				const pagePath = "/"+currentPage.route;
				 
				for (var i = 0; i < that.control.datas.length; i++) {
					let item = that.control.datas[i];
					if(item.path==pagePath){
						that.control.index = i;
					}
				}
				
			},
			public_reset() {
				let that = this;
				that.public_show();
			},
			control_change(index){
				let that = this;
				that.control.index = index;
	
				uni.$uv.route({
					type: 'redirect',
					url: that.control.datas[index].path
				})
			}
		}
	}
</script>

<style lang="scss">

</style>