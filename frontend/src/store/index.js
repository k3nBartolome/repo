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
    position:null,
    user_id: null,
    name: null,
    site_id:null,
    persistedStateKey: persistedStateOptions.key,
  },
  mutations: {
    setUser(state, { user, name, role, token,site_id,position }) {
      if (!state.token) {
        state.user = user;
        state.role = role;
        state.token = token;
        state.position = position;
        state.site_id = site_id;
        state.name = name;
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
    setSite(state, site_id) {
      state.site_id = site_id;
    },
    setToken(state, token) {
      state.token = token;
      axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    },
    logout(state) {
      // Reset all properties to their initial values
      Object.assign(state, {
        user: null,
        role: null,
        token: null,
        user_id: null,
        name: null,
        permissions: [],
      });

      // Clear axios authorization header
      axios.defaults.headers.common["Authorization"] = "";

      // Clear local storage
      localStorage.clear();
      localStorage.removeItem(persistedStateOptions.key);
      localStorage.removeItem(state.persistedStateKey);
    }




  },
  actions: {
    async logout({ commit, state }) {
      try {
        if (state.token) {
          // Make a copy of the user ID
          const id = state.user_id;

          // Clear Vuex state and local storage
          commit('logout');

          console.log("Authentication Token:", state.token);
          await axios.post(`http://127.0.0.1:8000/api/logout/${id}`);

          // Remove specific item from local storage
          localStorage.removeItem(state.persistedStateKey);
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
    }


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
