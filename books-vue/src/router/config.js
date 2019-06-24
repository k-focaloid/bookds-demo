import router from './router'

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
      if(localStorage.getItem('ticktac')){
        next();
      }
      else{
        next('/login');
      }
      
    }
    else{
      next();
    }
  })
