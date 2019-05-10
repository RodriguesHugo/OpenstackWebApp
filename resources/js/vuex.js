import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        ip : '',
        username : '',
        password : '',
        devices:'',
    },
    mutations: {
    	setUser(state, user){
    		state.ip = user.ip;
    		state.username = user.username;
    		state.password = user.password;
    	},
    	setDevices(state, devices){
    		state.devices = devices;
    	}
    }
});

export default store;