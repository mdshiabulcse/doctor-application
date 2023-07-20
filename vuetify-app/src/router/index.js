// Composables
import {createRouter, createWebHistory} from 'vue-router'
import DashboardHome from "@/views/dashboard/DashboardHome.vue";
import UserLogin from "@/views/auth/Login.vue";
import {useAuth} from "@/store/auth.js";

const routes = [
  {
    path: '/',
    component: () => import('@/layouts/default/Default.vue'),
    children: [
      {
        path: '', name: 'user.login', component: UserLogin, meta:{title:"User Login", guest:true},
      },
    ],
  },
  {
    path: '/dashboard', name: 'user.dashboard', component: DashboardHome, meta:{title:"User Dashboard", requiresAuth:true}

  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

const DEFAULT_TITLE= "404";

router.beforeEach((to,from,next) => {
  document.title=to.meta.title||DEFAULT_TITLE;
  const loggedIn = useAuth();
  if (to.matched.some((record)=>record.meta.requiresAuth)){
    if(!loggedIn.user.meta){
      next({name:"user.login"});
    }else{
      next()
    }
  }else if (to.matched.some((record)=>record.meta.guest)){
    if(loggedIn.user.meta){
      next({name:"user.dashboard"});
    }else{
      next()
    }
  }
  else{
    next();
  }

});
export default router
