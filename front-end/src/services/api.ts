import axios, { AxiosInstance } from "axios";

const api: AxiosInstance = axios.create({
  baseURL: "http://localhost:8000/api/",
  headers: {
    'Authorization': 'Bearer ' + localStorage.getItem('token') ?? ''
  }
});

export default api;