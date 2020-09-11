/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Toast, { TYPE } from "vue-toastification";

// Toast default options
const options = {
    toastDefaults: {
        // ToastOptions object for each type of toast
        [TYPE.ERROR]: {
            timeout: 10000,
            // closeButton: false,
        },
        [TYPE.SUCCESS]: {
            timeout: 3000,
            // hideProgressBar: false,
        }    
    }
};

window.Vue = require('vue');

Vue.use(Toast, options);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('unsorted-documents', require('./components/documents/Unsorted.vue').default);
Vue.component('latest-documents', require('./components/documents/Latest.vue').default);
Vue.component('all-documents', require('./components/documents/AllDocuments.vue').default);
Vue.component('document-search', require('./components/documents/Search.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 // Apply Vue globals
Vue.mixin({
    methods: {
        niceError(e = null) {
            // Display a nice error message to the user
            this.$toast.error('Something went wrong! Please let us know if this keeps happening.');

            // Support http error responses
            if(e.response && e.response.data && e.response.data.message) {
                throw new Error(e.response.data.message);
            }

            // Also supports simple string errors
            if(typeof e === "string") {
                throw new Error(e);
            }
        },
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    }
});

const app = new Vue({
    el: '#app',
});
