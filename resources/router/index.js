import { createRouter, createWebHistory } from 'vue-router';

// 定义中间件
const middleware = {
  guest: (to, from, next) => {
    if (!localStorage.getItem('token')) {
      return next();
    } else {
      return next("/");
    }
  },
  auth: (to, from, next) => {
    if (localStorage.getItem('token')) {
      return next();
    } else {
      return next("/login");
    }
  }
}

const routes = [
  {
    path: '/login',
    name: 'login',
    meta: {
      title: '登录',
    },
    beforeEnter: [middleware.guest],
    component: () => import('@/pages/login.vue')
  },
  {
    path: '/',
    name: 'home',
    meta: {
      title: '首页',
    },
    beforeEnter: [middleware.auth],
    component: () => import('@/pages/home.vue')
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

router.beforeEach((to, from, next) => {
  window.document.title = import.meta.env.APP_NAME + " - " + to.meta.title;
  next();
});

export default router;
