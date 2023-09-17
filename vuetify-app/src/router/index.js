// Composables
import {createRouter, createWebHistory} from 'vue-router'
import DashboardHome from "@/views/dashboard/DashboardHome.vue";
import UserProfile from "@/views/dashboard/UserProfile.vue";
import UserLogin from "@/views/auth/Login.vue";
import DefaultPage from "@/layouts/default/Default.vue";
import {useAuth} from "@/store/auth.js";
import {DoctorList,UserInfo} from "@/views/dashboard/Index";

const routes = [
  {
    path: '/', name: 'user.login', component: UserLogin, meta:{title:"User Login", guest:true},
  },
  {
    path: '/dashboard',
    component: DefaultPage,
    children: [
      {
        path: '/dashboard', name: 'user.dashboard', component: DashboardHome, meta:{title:"User Dashboard", requiresAuth:true},

      },
      {
        path: '/doctor-list', name: 'doctor.list', component: DoctorList, meta:{title:"Doctor List", requiresAuth:true},
      },
      {
        path: '/user-profile', name: 'user.profile', component: UserProfile, meta:{title:"User Profile", requiresAuth:true},
      },
      {
        path: '/user-list', name: 'user.list', component: UserInfo, meta:{title:"User Info", requiresAuth:true},
      },
    ],
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
