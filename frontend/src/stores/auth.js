import { defineStore } from 'pinia'
import axiosInstance from "@/services/axiosService.js";
import {ElNotification} from "element-plus";
import router from "@/router/index.js";

export const useAuth = defineStore("auth", {
    state: () => ({
        sliders:{},
        loading:false
    }),
    actions: {
        async getData() {
            try {
                const  res = await axiosInstance.post(
                    "/user/login",
                    formData
                );
                if(res.status === 200){
                    console.log(res.data);
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