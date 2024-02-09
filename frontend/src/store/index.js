import { createStore } from 'vuex'
import axios from 'axios'

export default createStore({
  state: {
    user: null,
    chatUser: null
  },
  mutations: {
    setUser: (state, payload) => state.user = payload,
    unsetUser: (state) => state.user = null,
    setChatUser: (state, payload) => state.chatUser = payload
  },
  actions: {
    login ({ commit }, payload) {
      return axios.post('http://api.local:8000/api/auth/login', payload)
        .then((response) => {
          commit('setUser', response.data.user)

          return response.data.auth
        })
    },
    logout ({ commit }) {
      commit('unsetUser')

      localStorage.removeItem('accessToken')
    },
    initUser ({ commit }) {
      return axios.get('http://api.local:8000/api/auth/me')
        .then((response) => {
          commit('setUser', response.data)
          return response.data
        })
    },
    selectUser ({ commit }, user) {
      commit('setChatUser', user)
    }
  },
  getters: {
    // isLoggedIn: (state) => !!state.user
    isLoggedIn (state) {
      return !!localStorage.getItem('accessToken') || !!state.user
    },
    user (state) {
      return state.user
    }
  }
})
