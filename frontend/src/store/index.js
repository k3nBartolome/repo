import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import axios from "axios";

export default createStore({
  plugins: [
    createPersistedState({
      storage: window.sessionStorage,
    }),
  ],
  state: {
    user: null,
    role: null,
    token: null,
    isLoggedIn: false,
  },
  mutations: {
    setUser(state, user) {
      if (!state.isLoggedIn) {
        state.user = user;
        state.token = user.token;
        state.role = user.role;
        state.isLoggedIn = true;
      } else {
        alert("Another user is already logged in. Please log out first.");
      }
    },
    setToken(state, token) {
      state.token = token;
    },
    setRole(state, role) {
      state.role = role;
    },
    logout(state) {
      state.user = null;
      state.token = null;
      state.role = null;
      state.isLoggedIn = false;
      sessionStorage.clear();
    },
  },
  actions: {
    login({ commit }, { email, password }) {
      return new Promise((resolve, reject) => {
        axios
          .post("http://127.0.0.1:8000/api/login", {
            email,
            password,
          })
          .then((response) => {
            const user = response.data.user;
            const token = response.data.token;
            const role = response.data.role;
            commit("setUser", user);
            commit("setToken", token);
            commit("setRole", role);
            resolve(role);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    logout({ commit }) {
      commit("logout");
    },
  },
  getters: {
    isLoggedIn(state) {
      return !!state.token;
    },
    returnRole(state) {
      return state.role;
    },
  },
});
