import { createStore } from 'vuex'
import createPersistedState from "vuex-persistedstate"

export default createStore({
    plugins: [createPersistedState({
        storage: window.sessionStorage
    })],
    state: {
        user: 'user',
        role:'role',
        token: null,
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        setRole(state, role) {
          state.role = role;
      },
        setToken(state, token) {
            state.token = token;
        },
        setLogout(state) {
            state.user = false;
            state.token = false;
        },
    },
    actions: {},
    getters: {
        isLoggedIn(state) {
            return !!state.token;
        },
        returnRole(state){
          return state.role;
        }
    },
})
