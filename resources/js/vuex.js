import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        token: '',
        alert:{
            snackbar: 'false',
            color: '',
            text: ''
    
        }
    },
    
    mutations: {
        setToken(state, token) {
            state.token = token;
        },
        initAlert(state){
            state.alert.snackbar = 'false';
        },
        showError(state, message) {
            state.alert.snackbar = 'true';
            state.alert.color = 'error';
            state.alert.text = message;
        }

    }
});

export default store;
