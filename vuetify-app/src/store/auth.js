
import { defineStore } from 'pinia'
import axiosInstance from "@/services/axiosService";
import {ElNotification} from "element-plus";
import router from "@/router/index.js";

export const useAuth = defineStore('auth',{
  state: () => ({
    user:{},
  }),
  persist: {
    paths: ['user'],
  },
  actions:{
    async login(formData) {
      try {
        const  res = await axiosInstance.post(
          "/user/login",
          formData
        );
        if(res.status === 200){
          // console.log(res.data);
          this.user= res.data;
          return new Promise((resolve)=>{
            resolve(res.data);
          })
        }
      }catch (error){
        if (error.response.data){
          // this.errors =error.response.data.errors;
          return new Promise((reject)=>{
            reject(error.response.data.errors);
          })
        }

      }
    },
    async logout(){

      try {
        let id =this.user.meta.user_access_log.id;
        const res=  await axiosInstance.post("/user/logout?id="+id).then(response =>{
          this.user=[];
          router.push({name:'user.login'});
          ElNotification({
            title: 'Logout',
            message: "Logout Successfully!",
            type: 'info',
            position: 'top-left',
          })

        });
        console.log(res);
      }catch (error){
        console.log(error);
      }
    },
  },
})
