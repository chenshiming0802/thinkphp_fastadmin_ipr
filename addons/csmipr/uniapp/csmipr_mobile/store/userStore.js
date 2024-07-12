/**
 * 本类通过XcCloginUtils.js代理使用，不要直接调用
 */

import {
	createStore
} from 'vuex';

export default createStore({
	state() {
		return {
			isLoggedIn: false,
			token: null,
		};
	},
	mutations: {
		SET_LOGGED_IN(state, payload) {
			console.log('store login',payload);
			state.isLoggedIn = true;
			state.token = payload.token;
			uni.setStorageSync('clogintoken', payload.token);
		},
		SET_LOGGED_OUT(state) {
			state.isLoggedIn = false;
			state.token = null;
			uni.removeStorageSync('clogintoken');
		}
	},
	actions: {
		login({
			commit
		}, token) {
			// 假设登录逻辑在这里，登录成功后调用
			commit('SET_LOGGED_IN', {
				token
			});
		},
		logout({
			commit
		}) {
			// 登出逻辑
			commit('SET_LOGGED_OUT');
		}
	},
	// 当store被创建时，检查localStorage中的token
	  replaceState: (state) => {
	    const storedToken = uni.getStorageSync('xclogin_token');
	    if (storedToken) {
	      state.token = storedToken;
	      state.isLoggedIn = true;
	    }
	  }
});
