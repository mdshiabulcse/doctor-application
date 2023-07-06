
import { defineStore } from 'pinia'
import axios from "axios";
import axiosInstance from "@/services/axiosService";

export const useAuth = defineStore('auth', {
  // state: () => ({
  //   //
  // }),
  actions:{
    async login(formData){
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
