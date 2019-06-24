import Vue from 'vue'
import Router from 'vue-router'
import DashboardLayout from '../layout/DashboardLayout'
import AuthLayout from '../layout/AuthLayout'
Vue.use(Router)


export default new Router({
  linkExactActiveClass: 'active',
  routes: [
    {
      path: '/',
      redirect: 'books',
      component: DashboardLayout,
      children: [
        {
          path: '/books',
          name: 'books',
          component: () => import(/* webpackChunkName: "books" */ '../views/ListBooks.vue'),
          meta: { requiresAuth: true} 
        },
        {
          path: '/book/:id',
          name: 'book',
          component: () => import(/* webpackChunkName: "book" */ '../views/BooksDetail.vue'),
          meta: { requiresAuth: true} 
        },
        {
          path: '/addbook',
          name: 'addbook',
          component: () => import(/* webpackChunkName: "addbook" */ '../views/AddBook.vue'),
          meta: { requiresAuth: true} 
        },
        {
          path: '/editbook/:id',
          name: 'editbook',
          component: () => import(/* webpackChunkName: "editbook" */ '../views/EditBook.vue'),
          meta: { requiresAuth: true} 
        }
      ]
    },
    {
      path: '/',
      redirect: 'login',
      component: AuthLayout,
      children: [
        {
          path: '/login',
          name: 'login',
          component: () => import(/* webpackChunkName: "demo" */ '../views/Login.vue')
        },
        {
          path: '/register',
          name: 'register',
          component: () => import(/* webpackChunkName: "demo" */ '../views/Register.vue')
        }
      ]
    }
  ]
})
