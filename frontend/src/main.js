import { createApp } from 'vue'
import App from './App.vue'
import { createRouter, createWebHashHistory } from 'vue-router'
import ChatEmptyPage from '@/views/ChatEmptyPage'
import LoginPage from '@/views/LoginPage'
import RegisterPage from '@/views/RegisterPage'
import HomePage from '@/views/HomePage'
import AuthPage from '@/views/AuthPage'
import axios from 'axios'
import store from './store/index'
import MainPage from '@/views/MainPage'
import ChatPage from '@/views/ChatPage'

const routes = [
  {
    path: '',
    component: MainPage,
    beforeEnter: () => {
      if(!localStorage.getItem('accessToken')) {
        router.push('/login')

        return false;
      }

      return true
    },
    redirect: () => ({ path: '/home/chat' }),
    children: [
      {
        path: '/home',
        component: HomePage,
        redirect: () => ({ path: '/home/chat' }),
        children: [
          { path: 'chat/:userId', component: ChatPage},
          { path: 'chat', component: ChatEmptyPage},
        ]
      },

    ]
  },
  {
    path: '',
    component: AuthPage,
    beforeEnter: () => {
      if(localStorage.getItem('accessToken')) {
        router.push('/home')

        return false;
      }

      return true
    },
    redirect: () => {
      return { path: '/login' }
    },
    children: [
      { path: '/login', component: LoginPage },
      { path: '/register', component: RegisterPage },
    ]
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

axios.interceptors.request.use(config => {
  const accessToken = localStorage.getItem('accessToken')
  if (accessToken) {
    config.headers.Authorization = `Bearer ${accessToken}`
  }

  config.withCredentials = true;

  return config
}, error => {
  return Promise.reject(error)
})

axios.interceptors.response.use(response => {
  return response
}, error => {
  if (error.response.status === 401) {

    store.dispatch('logout')

    return router.push('/login')
  }

  return Promise.reject(error)
})

createApp(App)
  .use(store)
  .use(router)
  .mount('#app')