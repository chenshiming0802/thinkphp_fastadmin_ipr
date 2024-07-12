<template>
	<uv-upload :fileList="fileList" @afterRead="afterRead" @delete="deletePic" :accept="accept" :multiple="multiple"
		:capture="capture" :compressed="compressed" :camera="camera" :maxDuration="maxDuration" :uploadIcon="uploadIcon"
		:uploadIconColor="uploadIconColor" :useBeforeRead="useBeforeRead" :previewFullImage="previewFullImage"
		:previewFullVideo="previewFullVideo" :maxCount="maxCount" :disabled="disabled" :imageMode="imageMode"
		:name="name" :sizeType="sizeType" :deletable="deletable" :maxSize="maxSize" :uploadText="uploadText"
		:width="width" :height="height" :previewImage="previewImage"></uv-upload>
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
		props: {
			modelValue: {
				type: String,
				default: null
			},
			accept: {
				type: String,
				default: 'image'
			},
			// 	图片或视频拾取模式，当accept为image类型时设置capture可选额外camera可以直接调起摄像头
			capture: {
				type: [String, Array],
				default: () => ['album', 'camera']
			},
			// 当accept为video时生效，是否压缩视频，默认为true
			compressed: {
				type: Boolean,
				default: true
			},
			// 当accept为video时生效，可选值为back或front
			camera: {
				type: String,
				default: 'back'
			},
			// 当accept为video时生效，拍摄视频最长拍摄时间，单位秒
			maxDuration: {
				type: Number,
				default: 60
			},
			// 上传区域的图标，只能内置图标
			uploadIcon: {
				type: String,
				default: 'camera-fill'
			},
			// 上传区域的图标的颜色，默认
			uploadIconColor: {
				type: String,
				default: '#D3D4D6'
			},
			// 是否开启文件读取前事件
			useBeforeRead: {
				type: Boolean,
				default: false
			},
			// 是否开启图片预览功能
			previewFullImage: {
				type: Boolean,
				default: true
			},
			// 是否开启视频预览功能
			previewFullVideo: {
				type: Boolean,
				default: true
			},
			// 最大上传数量
			maxCount: {
				type: [String, Number],
				default: 52
			},
			// 是否禁用
			disabled: {
				type: Boolean,
				default: false
			},
			// 预览上传的图片时的裁剪模式，和image组件mode属性一致
			imageMode: {
				type: String,
				default: 'aspectFill'
			},
			// 标识符，可以在回调函数的第二项参数中获取
			name: {
				type: String,
				default: ''
			},
			// 所选的图片的尺寸, 可选值为original compressed
			sizeType: {
				type: Array,
				default: () => ['original', 'compressed']
			},
			// 是否开启图片多选，部分安卓机型不支持
			multiple: {
				type: Boolean,
				default: false
			},
			// 是否展示删除按钮
			deletable: {
				type: Boolean,
				default: true
			},
			// 文件大小限制，单位为byte
			maxSize: {
				type: [String, Number],
				default: Number.MAX_VALUE
			},
			// 上传区域的提示文字
			uploadText: {
				type: String,
				default: ''
			},
			// 内部预览图片区域和选择图片按钮的区域宽度
			width: {
				type: [String, Number],
				default: 80
			},
			// 内部预览图片区域和选择图片按钮的区域高度
			height: {
				type: [String, Number],
				default: 80
			},
			// 是否在上传完成后展示预览图
			previewImage: {
				type: Boolean,
				default: true
			},
		},
		watch: {
			'modelValue': {
				handler(newVal, oldVal) {
					let that = this;
					let farray = that.modelValue.split(",");;
					that.fileList = [];
					for (let i = 0, j = farray.length; i < j; i++) {
						let fstring = farray[i];
						let url = fstring;
						if (fstring.indexOf("http") != 0) {
							url = config.image_baseUrl + fstring;
						}
						that.fileList.push({
							url: url
						});
					}
				},
			},
		},
		data() {
			return {
				config: config,
				xsStringUtils: xsStringUtils,
				xsDateUtils: xsDateUtils,
				sp: {},
				sr: {},
				control: {

				},
				fileList: [],
			}
		},
		created() {
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
			},
			public_reset() {
				let that = this;
				that.public_show();
			},
			control_click() {
				let that = this;
				that.$emit('update:modelValue', "Hello World!");
			},
			// 删除图片
			deletePic(event) {
				let that = this;
				this[`fileList${event.name}`].splice(event.index, 1);
				that.$emit('update:modelValue', that._convertFileListToValue(that.fileList));
			},
			// 新增图片
			async afterRead(event) {
				let that = this;
				// 当设置 multiple 为 true 时, file 为数组格式，否则为对象格式
				let lists = [].concat(event.file)
				let fileListLen = this[`fileList${event.name}`].length
				lists.map((item) => {
					this[`fileList${event.name}`].push({
						...item,
						status: 'uploading',
						message: '上传中'
					})
				})
				for (let i = 0; i < lists.length; i++) {
					const resulturl = await this.uploadFilePromise(lists[i].url)
					let item = this[`fileList${event.name}`][fileListLen]
					this[`fileList${event.name}`].splice(fileListLen, 1, Object.assign(item, {
						status: 'success',
						message: '',
						url: resulturl
					}))
					fileListLen++
				}
				that.$emit('update:modelValue', that._convertFileListToValue(that.fileList));
			},
			uploadFilePromise(url) {
				return new Promise((resolve, reject) => {
					xcHttpUtils.upload(url, function(res) {
						resolve(res.data.url);
					});
				})
			},
			_convertFileListToValue() {
				let that = this;
				let values = [];
				for (let i = 0, j = that.fileList.length; i < j; i++) {
					let url = that.fileList[i]["url"];
					if (url.indexOf(config.image_baseUrl) >= 0) {
						url = url.substring(config.image_baseUrl.length);
					}
					values.push(url);
				}
				return values.join(',');
			}
		}
	}
</script>

<style lang="scss">

</style>