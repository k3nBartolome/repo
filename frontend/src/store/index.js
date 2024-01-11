import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import axios from "axios";

const persistedStateOptions = {
  key: "vuex",
  storage: window.localStorage,
};

export default createStore({
  plugins: [createPersistedState(persistedStateOptions)],
  state: {
    user: null,
    role: null,
    token: null,
    user_id: null,
    name: null,
    permissions: [],
  },
  mutations: {
    setUser(state, { user, name, role, token, permissions }) {
      if (!state.token) {
        state.user = user;
        state.role = role;
        state.token = token;
        state.name = name;
        state.permissions = permissions;
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      } else {
        alert("Another user is already logged in. Please log out first.");
      }
    },
    setUserId(state, user_id) {
      state.user_id = user_id;
    },
    setUserName(state, name) {
      console.log("Setting user name:", name);
      state.name = name;
    },

    setRole(state, role) {
      state.role = role;
    },
    setToken(state, token) {
      state.token = token;
      axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    },
    logout(state) {
      console.log("Logout mutation called");
      state.user = null;
      state.role = null;
      state.token = null;
      state.user_id = null;
      state.name = null;
      state.permissions = [];
      axios.defaults.headers.common["Authorization"] = "";

      // Clear local storage directly
      Object.keys(localStorage).forEach((key) => {
        if (key.startsWith("vuex")) {
          localStorage.removeItem(key);
        }
      });
    },

  },
  actions: {
    async logout({ commit, state }) {
      console.log("User State Before Logout:", state);
      try {
        if (state.token) {
          const id = state.user_id;
          console.log("Authentication Token:", state.token);
await axios.post(`http://127.0.0.1:8000/api/logout/${id}`);
          commit("logout");
        } else {
          console.warn("User is not authenticated.");
        }
      } catch (error) {
        if (error.response) {
            console.warn("Error response during logout:", error.response);
        } else {
            console.error("Error during logout:", error);
        }
    }
    },
    // ... other actions
  },

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
    returnUser(state) {
      return state.user;
    },
  },
});
