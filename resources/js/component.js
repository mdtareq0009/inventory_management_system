require('./bootstrap');

window.Vue = require('vue').default;

// Register Vue Components
Vue.component('register', require('./components/Register.vue').default);
Vue.component('user-access', require('./components/UserAccess.vue').default);
Vue.component('user-activity', require('./components/UserActivity.vue').default);


Vue.component('company-profile', require('./components/CompanyProfile.vue').default);
Vue.component('category-entry', require('./components/CategoryEntry.vue').default);


