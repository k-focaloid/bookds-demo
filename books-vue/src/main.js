import Vue from 'vue'
import App from './App.vue'
import router from './router/router'
import config from './router/config'
import './registerServiceWorker'
import Addons from './plugins/includes'

Vue.config.productionTip = false
Vue.use(Addons)

new Vue({
  router,
  render: h => h(App)
}).$mount('#app');

