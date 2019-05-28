import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        token: '',
        userLoged: '',
        proj:{id: '', name: ''},
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
        setProj(state,proj){
            state.proj.id = proj.id;
            state.proj.name = proj.name;
        },
        setUserLoged(state, userLoged) {
            state.userLoged = userLoged
        },
        clearToken(state) {
            state.token = '';
        },
        showError(state, message) {
            state.alert.snackbar = 'true';
            state.alert.color = 'error';
            state.alert.text = message;
            setTimeout(() => state.alert.snackbar = false, 6000);
        },
        showSuccess(state, message) {
            state.alert.snackbar = 'true';
            state.alert.color = 'success';
            state.alert.text = message;
            setTimeout(() => state.alert.snackbar = false, 6000);
        },

    }
});

export default store;
