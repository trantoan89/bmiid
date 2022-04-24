require('./bootstrap');

// Import modules...
import Vue from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import { createInertiaApp, Link } from '@inertiajs/inertia-vue';
import PortalVue from 'portal-vue';
import * as VueGoogleMaps from 'vue2-google-maps';
import '@fortawesome/fontawesome-free/css/all.css'
import '@fortawesome/fontawesome-free/js/all.js'
import Vuelidate from 'vuelidate';
import ToggleButton from 'vue-js-toggle-button';
// import VuePhoneNumberInput from 'vue-phone-number-input';
// import 'vue-phone-number-input/dist/vue-phone-number-input.css';
 
// Vue.component('vue-phone-number-input', VuePhoneNumberInput);

Vue.use(ToggleButton);
Vue.use(require('vue-moment'));
Vue.mixin({ methods: { route } });
Vue.use(InertiaPlugin);
Vue.component("inertia-link", Link);
Vue.use(PortalVue);
Vue.use(VueGoogleMaps, {
    load: {
        key: '',
    }
})

window.getPaginatedData = function (vue_com, prop, pg_link, pg_id, url, http_method='get', data={}) {
    const config = {
        method: http_method.toLowerCase(),
        url: url,
        data: data
    }
    axios(config).then(response => {
        vue_com[prop] = response.data[0];
        vue_com[pg_link] = response.data[1];
        
        vue_com.$nextTick(function () {
            var list_a = document.getElementById(pg_id).getElementsByTagName('A');

            for (let a of list_a) {
                let href = a.href;
                a.onclick = function() {
                    getPaginatedData(vue_com, prop, pg_link, pg_id, href, http_method, data);
                };
                a.removeAttribute("href");
            }
        });
    })
}

const app = document.getElementById('app');

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
