import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        token: '',
        alert: {
            snackbar: null,
            color: '',
            text: ''

        }
    },

    mutations: {
        setToken(state, token) {
            state.token = token;
        },
        clearToken(state) {
            state.token = '';
        },
        showError(state, message) {
            state.alert.snackbar = 'false';
            state.alert.snackbar = 'true';
            state.alert.color = 'error';
            state.alert.text = message;
            setTimeout(() => state.alert.snackbar = false, 6000);
        },
        showSuccess(state, message) {
            state.alert.snackbar = 'false';
            state.alert.snackbar = 'true';
            state.alert.color = 'success';
            state.alert.text = message;
            setTimeout(() => state.alert.snackbar = false, 6000);
        },

    }
});

export default store;
