import { createStore } from 'vuex';
import createPersistedState from 'vuex-persistedstate';
import axios from 'axios';

export default createStore({
  plugins: [
    createPersistedState({
      storage: window.localStorage,
    }),
  ],
  state: {
    user: null,
    role: null,
    token: null,
    user_id: null,
    permissions: [],
  },
  mutations: {
    setUser(state, { user, role, token, permissions }) {
      if (!state.token) {
        state.user = user;
        state.role = role;
        state.token = token;
        state.permissions = permissions;
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      } else {
        alert('Another user is already logged in. Please log out first.');
      }
    },
    setUserId(state, user_id) {
      state.user_id = user_id;
    },
    setRole(state, role) {
      state.role = role;
    },
    setToken(state, token) {
      state.token = token;
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    },
  },
  actions: {},
  getters: {
    isLoggedIn(state) {
      return !!state.token;
    },
    returnRole(state) {
      return state.role;
    },
    returnUserId(state) {
      return state.user_id;
    },
    hasPermission(state) {
      return (permission) => {
        return state.permissions.includes(permission);
      };
    },
  },
});