/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import store from "./vuex.js";

Vue.use(Vuetify);
import "vuetify/dist/vuetify.min.css"; // Ensure you are using css-loader

Vue.use(VueRouter);
window.Vue = require("vue");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component("navbar", require("./components/Navbar.vue").default);
Vue.component("dashboard", require("./components/Dashboard.vue").default);
Vue.component("alert", require("./components/Alert.vue").default);

const images = Vue.component("images", require("./components/Images.vue").default);
const volume = Vue.component("volume", require("./components/Volume.vue").default);
const flavor = Vue.component("flavor",
    require("./components/Flavor.vue").default
);
const instance = Vue.component(
    "instance",
    require("./components/Instance.vue").default
);
const webserver = Vue.component(
    "webserver",
    require("./components/CreateWebServer.vue").default
);
const login = Vue.component("login", require("./components/Login.vue").default);
const routes = [{
        path: "/images",
        component: images,
        name: "images"
    },
    {
        path: "/volume",
        component: volume,
        name: "volume"
    },
    {
        path: "/flavor",
        component: flavor,
        name: "flavor"
    },
    {
        path: "/",
        component: login,
        name: "login"
    },
    {
        path: "/instance",
        component: instance,
        name: "instance"
    },
    {
        path: "/webserver",
        component: webserver,
        name: "webserver"
    },
];
const router = new VueRouter({
    routes // short for `routes: routes`
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router,
    store,
    data() {
        return {};
    },
});

router.beforeEach((to, from, next) => {
    if ((to.name == 'images') || (to.name == 'volume') || (to.name == 'flavor') || (to.name == 'instance')) {
        if (store.state.token == '') {
            next('/');
            return;
        }
    }
    next();
});
