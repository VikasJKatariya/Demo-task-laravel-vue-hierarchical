import { createRouter, createWebHistory } from 'vue-router';
import PageView from '../views/PageView.vue';
import PageList from '../views/PageList.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: PageList
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'page',
      component: PageView
    }
  ]
});

export default router;