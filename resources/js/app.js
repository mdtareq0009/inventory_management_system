require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Require Vue
window.Vue = require('vue').default;

//Register Vue Components
import component from './component'
Vue.use(component);


//  toster
import Toaster from 'v-toaster'
import 'v-toaster/dist/v-toaster.css' 
Vue.use(Toaster, {timeout: 5000});


// v-select
import vSelect from "vue-select";

Vue.component("v-select", vSelect);
import "vue-select/dist/vue-select.css";

//datatable
import { VuejsDatatableFactory } from 'vuejs-datatable';
 
Vue.use( VuejsDatatableFactory );

// Initialize Vue
const app = new Vue({
    el: '#app',
});

