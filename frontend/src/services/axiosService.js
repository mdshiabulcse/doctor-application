import axios from "axios";
import {useAuth} from "@/stores";
import {storeToRefs} from "pinia";


const axiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_URL+"/api/v1",

});

axiosInstance.interceptors.request.use(function (config) {
    const authInfo=useAuth();
    if (authInfo.user?.meta?.token) {
        config.headers.Authorization = "Bearer " + authInfo.user.meta.token;
    }

    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});
axiosInstance.interceptors.response.use(
    response => {
        return response;
    },
    async error => {
        if (error.response.status === 401) {
            const authInfo=useAuth();
            authInfo.user = []
            router.push({name:"user.login"})
        }
        return Promise.reject(error);
    }
);


export default axiosInstance;