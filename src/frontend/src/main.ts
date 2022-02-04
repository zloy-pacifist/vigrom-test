import Vue from 'vue'
import App from './App.vue'
import router from './router'
import LoaderPlugin from '@/components/loader/plugin';
import BackendApiPlugin from '@/components/backend-api/plugin';

console.log(process.env.VUE_APP_API_URL);

Vue.config.productionTip = false;

Vue.use(new LoaderPlugin());
Vue.use(new BackendApiPlugin(), {
    baseURL: process.env.VUE_APP_API_URL,
});

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
