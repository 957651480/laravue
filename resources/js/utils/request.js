import axios from 'axios';
import {Message} from 'element-ui';
import { getToken, setToken } from '@/utils/auth';
import store from "@/store";

// Create axios instance
const service = axios.create({
  baseURL: process.env.MIX_BASE_API,
  timeout: 10000, // Request timeout
});

// Request intercepter
service.interceptors.request.use(
  config => {
    const token = getToken();
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + getToken(); // Set JWT token
    }

    return config;
  },
  error => {
    // Do something with request error
    console.log(error); // for debug
    Promise.reject(error);
  }
);

// response pre-processing
service.interceptors.response.use(
  response => {
    let res = response.data;
    if(res.code!==200){
      Message(res.msg);
      return Promise.reject(new Error(res.msg || 'Error'))
    }

    if (response.headers.authorization) {
      setToken(response.headers.authorization);
      response.data.token = response.headers.authorization;
    }
    return response.data;

  },
  error => {
    /*let message = error.msg;
    Message({
      message: message,
      type: 'error',
      duration: 5 * 1000,
    });
    return Promise.reject(error);*/
  },
);

export default service;
