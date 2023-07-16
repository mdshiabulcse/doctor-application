
import { defineStore } from 'pinia'
import axiosInstance from "@/services/axiosService";

export const useAuth = defineStore('auth', {
  state: () => ({
    user:{}
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
  },
})
