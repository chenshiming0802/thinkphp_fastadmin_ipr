<template>
	<section>
		<uv-load-more v-if="pageinfo.list.length>0" :status="pageinfo.status" />
		<uv-empty  v-if="pageinfo.list.length==0" mode="list" marginTop="100" iconSize="100"></uv-empty>
	</section>
	
</template>

<script>
	export default {
		name: 'xc-uv-load-more',
		props: {
			pageinfo: {
				type: Object,
				default: []
			},
		},
		watch: {
			'pageinfo': {
				handler(newVal, oldVal) {
					this.xinit();
				},
				deep: true,
				immediate: true
			},
		},
		data() {
			return {
			};
		},
		mounted() {
			let that = this;
			that.$nextTick(function(){
				that.xinit();
			})
		},
		methods: {
			xinit: function() {
				let that = this;
			},
		}
	};
</script>

<style>
</style>
