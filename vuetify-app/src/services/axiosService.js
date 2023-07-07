import axios from "axios";

const axiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_URL+"/api/v1",


});
axiosInstance.interceptors.request.use((config) => {
  const token = document.head.querySelector('meta[name="csrf-token"]');
  if (token) {
    config.headers['X-CSRF-TOKEN'] = token.content;
  }
  return config;
}, (error) => {
  return Promise.reject(error);
});
export default axiosInstance;
