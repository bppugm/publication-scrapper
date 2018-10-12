
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('Paginator', require('./components/Paginator'));
Vue.component('Skeleton', require('./components/Skeleton'));
Vue.component('Spinner', require('./components/Spinner'));
Vue.component('SearchBase', require('./components/search/SearchBase'));
Vue.component('AuthorFilterBase', require('./components/author/AuthorFilterBase'));
Vue.component('AuthorSearchBase', require('./components/author/AuthorSearchBase'));
Vue.component('AuthorDetailsBase', require('./components/author/AuthorDetailsBase'));
Vue.component('AuthorDetailsProfile', require('./components/author/AuthorDetailsProfile'));
Vue.component('DocumentListItem', require('./components/document/DocumentListItem'));
Vue.component('DocumentSearchForm', require('./components/document/DocumentSearchForm'));

import store from './store/index';

Vue.mixin({
    methods: {
        url(string) {
            return env.baseUrl + string
        }
    }
});

const app = new Vue({
    el: '#app',
    store,
});
