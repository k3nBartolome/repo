import { createStore } from 'vuex'

export default createStore({
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
          return !!state.role;
        }
    },
})
