/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/en';
require('./bootstrap');
window.Vue = require('vue').default;
Vue.use(ElementUI , { locale });

import { StreamBarcodeReader } from "vue-barcode-reader";

Vue.component('StreamBarcodeReader', StreamBarcodeReader);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('login-component', require('./components/LoginComponent.vue').default);
Vue.component('colaborador-component', require('./components/ColaboradorComponent.vue').default);
Vue.component('showmenu-component', require('./components/ShowMenuComponent.vue').default);
Vue.component('showpedido-component', require('./components/ShowPedidoComponent.vue').default);
Vue.component('showpedidosdia-component', require('./components/ShowPedidosDiaComponent.vue').default);
Vue.component('showinvitado-component', require('./components/ShowInvitadoComponent.vue').default);
Vue.component('crearcolaborador-component', require('./components/CrearColaboradorComponent.vue').default);
Vue.component('editarcolaborador-component', require('./components/EditarColaboradorComponent.vue').default);
Vue.component('showpuntuacion-component', require('./components/ShowPuntuacionComponent.vue').default);
Vue.component('showpuntuacionmensual-component', require('./components/ShowPuntuacionMensualComponent.vue').default);
Vue.component('mescolaborador-component', require('./components/MesColaboradorComponent.vue').default);
Vue.component('puntuacion-component', require('./components/PuntuacionComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
