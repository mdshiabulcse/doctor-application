import { defineStore } from 'pinia'
import axiosInstance from "@/services/axiosService";

export const useAuth = defineStore('auth', {
  actions:{
    async login(formData){
      // console.log("formData")
      // console.log(formData)
      // console.log("formData")
      try {
        let res = await axiosInstance.post("/user/login",formData);
        if (res.status === 200){
          console.log(res.data)
        }

      }catch (error){
        console.log(error)
      }
    },
  },
})
