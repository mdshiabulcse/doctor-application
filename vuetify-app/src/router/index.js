// Composables
import { createRouter, createWebHistory } from 'vue-router'
import DashboardHome from "@/views/dashboard/DashboardHome.vue";

const routes = [
  {
    path: '/',
    component: () => import('@/layouts/default/Default.vue'),
    children: [
      {
        path: '',
        name: 'Home',
        component: () => import('@/views/auth/Login.vue'),
      },
    ],
  },
  {
        path: '/dashboard', name: 'Dashboard', component: DashboardHome,

  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

export default router
