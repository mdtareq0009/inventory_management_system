require('./bootstrap');

window.Vue = require('vue').default;

// Register Vue Components
Vue.component('register', require('./components/Register.vue').default);
Vue.component('user-access', require('./components/UserAccess.vue').default);
Vue.component('user-activity', require('./components/UserActivity.vue').default);


Vue.component('company-profile', require('./components/CompanyProfile.vue').default);
Vue.component('category-entry', require('./components/CategoryEntry.vue').default);
Vue.component('unit-entry', require('./components/UnitEntry.vue').default);
Vue.component('product-entry', require('./components/ProductEntry.vue').default);
Vue.component('customer-entry', require('./components/CustomerEntry.vue').default);
Vue.component('supplier-entry', require('./components/SupplierEntry.vue').default);
Vue.component('purchase-order-inventory-entry', require('./components/inventory/PurchaseOrderInventoryEntry.vue').default);


