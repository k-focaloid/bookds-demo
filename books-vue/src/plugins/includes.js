import "@/assets/vendor/nucleo/css/nucleo.css";
import "@/assets/scss/style.scss";
import components from "./components";
import AuthPlugin from '../components/Authentication/index'
export default {
  install(Vue) {
    Vue.use(components);
    Vue.use(AuthPlugin);
  }
};
