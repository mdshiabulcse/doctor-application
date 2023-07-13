
import { defineStore } from 'pinia'
import axiosInstance from "@/services/axiosService";

export const useAuth = defineStore('auth', {
  state: () => ({errors:{}}),
  actions:{
    async login(formData){
      try {
        let res = await axiosInstance.post("/user/login",formData);
        if (res.status === 200){
          console.log(res.data)
        }

      }catch (error){
        if (error.response.data){
          this.errors = error.response.data.errors
        }
      }
    },
  },
})
