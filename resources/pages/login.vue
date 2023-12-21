<template>
  <div class="h-screen flex flex-col justify-center pt-0 pb-64 px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto h-10 w-auto" src="@/assets/logo.svg" alt="lumen-im" />
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-2" @submit.prevent="submit">
        <div>
          <label for="username" class="block text-sm font-medium leading-6 text-gray-900">用户名</label>
          <div class="mt-2">
            <input id="username" name="username" type="text" v-model="loginForm.username" autocomplete="username" required class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
          </div>
          <p class="text-sm text-red-600 leading-tight">{{ store.errors.username }}</p>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium leading-6 text-gray-900">密码</label>
          <div class="mt-2">
            <input id="password" name="password" type="password" v-model="loginForm.password" autocomplete="current-password" required class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
          </div>
          <p class="text-sm text-red-600 leading-tight">{{ store.errors.password }}</p>
        </div>

        <div class="flex justify-between text-sm">
          <div class="flex items-center">
            <input id="remember-me" name="remember" type="checkbox" v-model="loginForm.remember" class="h-4 w-4 rounded">
            <label for="remember-me" class="ml-2">记住我</label>
          </div>
          <a href="#" class="font-semibold text-blue-600 hover:text-blue-500">忘记密码？</a>
        </div>

        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">登录</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import {reactive} from "vue";

import {login} from "@/axios/requests/login";
import {useStore} from '@/stores';
import router from '@/router';

const store = useStore();
// 初始化数据
const loginForm = reactive({
  username: 'xiaozhi',
  password: '123456',
  remember: false,
});

// 事件处理
const submit = async () => {
  await login(loginForm).then((res) => {
    localStorage.setItem('name', res.name);
    router.push("/");
  });
}
</script>
