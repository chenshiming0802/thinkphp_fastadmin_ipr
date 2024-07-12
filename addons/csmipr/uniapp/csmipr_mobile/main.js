import uvUI from '@climblee/uv-ui'
uni.$uv.config.unit = 'px'

import App from './App'
import {xsDateUtils,xsDatetimeUtils} from 'xstack-for-javascript';
import { createSSRApp } from 'vue'
import xcCloginUtils from '@/library/xcore/js/XcCloginUtils';
import xcHttpUtils from "@/library/xcore/js/XcHttpUtils_mobile.js";

if(true){
	let storage_token = uni.getStorageSync('clogintoken');
	if (storage_token) {
	  xcCloginUtils.setSessionToken(storage_token);
	}
	
	// #ifdef WEB
	let url_token = xcHttpUtils.getUrlParam("clogintoken");
	if (url_token) {
	  xcCloginUtils.setSessionToken(url_token);
	}
	// #endif
	
}

export function createApp() {
  const app = createSSRApp(App)
  // app.use(userStore);
  app.use(uvUI)
  uni.$uv.config.unit = 'px';
  
  return {
    app
  }
}
 