<!--
<xc-uv-single-checkbox v-model="taskitem.xtaskstatus" truevalue="finish"></xc-uv-single-checkbox>
-->
<template>
	<view style="display: inline-block;" class="u-input u-input-single-checkbox">
		<uv-checkbox-group>
			<uv-checkbox shape="squre" label=" " :checked="sp.checked" size="20" @change="control_change"></uv-checkbox>
		</uv-checkbox-group>
	</view>
</template>

<script>
	export default {
		props: {
			modelValue: {
				type: String,
				default: null
			},
			truevalue: {
				type: String,
				default: "Y"
			},
			falsevalue: {
				type: String,
				default: "N"
			}
		},
		data() {
			return {
				sp: {
					checked: false,
				},
				control: {
					visible: false,
					displayvalue: "请选择",
					columns: [],
				},
			};
		},
		created() {
			let that = this;
			that.xinit();
		},
		methods: {
			xinit: function() {
				let that = this;
				let control = that.control;

				that.sp.checked = (that.modelValue == that.truevalue) ? true : false;

			},
			control_change: function(checked) {
				let that = this;
				let val = checked ? that.truevalue : that.falsevalue;
				that.$emit('update:modelValue', val);
			}
		}
	};
</script>

<style lang="scss">
</style>