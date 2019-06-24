import axios from 'axios';
//var AES = require("crypto-js/aes");
const apiBaseURL = "http://127.0.0.1:8000/api/";

const AuthStore = {
  key: [],
  logout(baseURL) {
    return new Promise((resolve,reject)=>{
      axios.get(baseURL + 'logout').then(function(data) {
        localStorage.removeItem('ticktac');
        resolve(data);
      }).catch(function(data) {
        reject(data);
      });
  });
  },
  isLoggedin() {
    let token= '';
    if(null != (token = localStorage.getItem('ticktac'))){
      axios.defaults.headers.common['Authorization'] = "Bearer " + token;
      return true;
    }
    else 
    {
      axios.defaults.headers.common['Authorization'] = '';
      return false;
    }
  },

login(email,password,baseURL) {
    //
    return new Promise((resolve,reject)=>{
          axios.post(baseURL + 'login',{email:email,password:password},
          {
              headers: {
                  'Content-Type': 'application/json',
              }
          }
          ).then(function(data) {
            localStorage.setItem("ticktac",data.data.token);
            axios.defaults.headers.common['Authorization'] = "Bearer " + data.data.token;
            resolve(data);
          }).catch(function(data) {
            reject(data);
          });
    });
  }
};

const AuthPlugin = {
  install(Vue) {
    let app = new Vue({
      data: {
        baseUrl: apiBaseURL,
        authStore: AuthStore
      },
      methods: {
        isLoggedIn(){
          return this.authStore.isLoggedin();
        },
        logout(){
          return this.authStore.logout(this.baseUrl);
        },
        login(email,password) {
          return this.authStore.login(email,password,this.baseUrl);
        },
        getBaseURL(){
          return this.baseUrl;
        }
      },
    });
    Vue.prototype.$auth={}; 
    Vue.prototype.$auth.logout = app.logout;
    Vue.prototype.$auth.login = app.login;
    Vue.prototype.$auth.isLoggedIn = app.isLoggedIn;
    Vue.prototype.$baseAPI = app.getBaseURL();
    app.isLoggedIn();
  }
};

export default AuthPlugin;
