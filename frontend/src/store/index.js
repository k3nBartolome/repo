import { createStore } from 'vuex'
import createPersistedState from "vuex-persistedstate"

export default createStore({
    plugins: [createPersistedState({
        storage: window.sessionStorage
    })],
    state: {
        user: null,
        role:null,
        token: null,
    },
    mutations: {
        setUser(state, user) {
            if (!state.token) {
                state.user = user;
            } else {
                // display an error message or redirect the user
                alert('Another user is already logged in. Please log out first.')
            }
        },
        setRole(state, role) {
          state.role = role;
        },
        setToken(state, token) {
            state.token = token;
        },
        logout(state) {
            state.user = null;
            state.token = null;
            state.role = null;
            // clear the sessionStorage
            sessionStorage.clear();
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
