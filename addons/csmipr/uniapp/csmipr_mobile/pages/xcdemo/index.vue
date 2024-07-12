<template>
	<view class="xc-wrap">
		<uv-cell-group>
			<uv-cell title="用户登录" isLink url="/pages/xclogin/login"></uv-cell>
			<uv-cell title="我的信息" isLink url="/pages/xclogin/me"></uv-cell>
			<uv-cell title="DMO 列表数据 (水平选项卡)" isLink url="/pages/xcdemo/dmo/list_tabs"></uv-cell>
			<uv-cell title="DMO 列表数据 (垂直选项卡)" isLink url="/pages/xcdemo/dmo/list_vtabs"></uv-cell>
			<uv-cell title="DMO APPLY 管理" isLink url="/pages/xcdemo/dmoapply/list"></uv-cell>
		</uv-cell-group>
		<xa-uv-bottom-tabbar></xa-uv-bottom-tabbar>
	</view>

</template>

<script>
	export default {
		data() {
			return {
				value: 0
			}
		}
	}
</script>

<style>

</style>