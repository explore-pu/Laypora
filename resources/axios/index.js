import axios from "axios";
import {useStore} from '@/stores';
import router from "@/router";

const axiosApp = axios.create({
    baseURL: import.meta.env.APP_API,
    timeout: 2000,
});

// 添加请求拦截器
axiosApp.interceptors.request.use((config) => {// 在发送请求之前做些什么
        let token = localStorage.getItem("token");
        config.headers.Authorization = "Bearer " + token;

        console.log('####  request.succeed', config);

        return config;
    }, (error) => {// 对请求错误做些什么
        console.log('####  request.error', error);

        return Promise.reject(error);
    }
);

// 添加响应拦截器
axiosApp.interceptors.response.use((response) => {// 对响应数据做点什么
        console.log('####  response.succeed', response);

        if (response.status === 200 && 'authorization' in response.headers) {
            localStorage.setItem('token', response.headers.authorization.slice(7));
        }
        return response.data;
    }, (error) => {// 对响应错误做点什么
        console.log('####  response.error', error);

        // 未认证处理
        if (error.response.status === 401) {
            localStorage.clear();
            router.push("/login").then(r => {
            });
        }
        // 表单验证错误处理
        if (error.response.status === 422) {
            const store = useStore();
            store.setErrors(error.response.data);
        }
        return Promise.reject(error.response);
    }
);

export const get = (url, data = {}) => {
    return new Promise((resolve, reject) => {
        axiosApp.get(url, {params: data}).then((res) => {
            resolve(res);
        }).catch((err) => {
            reject(err);
        });
    });
}

export const post = (url, data = {}) => {
    return new Promise((resolve, reject) => {
        axiosApp.post(url, data).then((res) => {
            resolve(res);
        }).catch((err) => {
            reject(err);
        });
    });
}

export const put = (url, data = {}) => {
    return new Promise((resolve, reject) => {
        axiosApp.put(url, data).then((res) => {
            resolve(res);
        }).catch((err) => {
            reject(err);
        });
    });
}

export const destroy = (url, data = {}) => {
    return new Promise((resolve, reject) => {
        axiosApp.delete(url, {params: data}).then((res) => {
            resolve(res);
        }).catch((err) => {
            reject(err);
        });
    });
};

export default {get, post, put, destroy}
