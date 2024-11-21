<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h2 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        User Manager
      </h2>
    </div>
  </header>
  <div class="py-8">
    <div
      class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
    >
      <form
        @submit.prevent="postUser"
        class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
      >
        <label class="block">
          Name
          <input
            v-model="name"
            type="text"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Email
          <input
            v-model="email"
            type="email"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Roles
          <select
            v-model="roles_selected"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
            @change="getRoles"
          >
            <option disabled value="" selected>Please select one</option>
            <option v-for="role in roles" :key="role.id" :value="role.name">
              {{ role.name }}
            </option>
          </select>
        </label>
        <label class="block">
          Password
          <input
            v-model="password"
            type="password"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <button
          type="submit"
          class="px-4 py-1 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
        >
          <i class="fa fa-building"></i> Add
        </button>
      </form>
    </div>
  </div>
  <div class="py-8">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <table class="w-full text-white table-auto">
        <thead>
          <tr
            class="text-left bg-orange-500 border-2 border-orange-600 border-solid"
          >
            <th class="px-1 py-2">ID</th>
            <th class="px-1 py-2">Avatar</th>
            <th class="px-1 py-2">Name</th>
            <th class="px-1 py-2">Email</th>
            <th class="px-1 py-2">Roles</th>
            <th class="px-1 py-2">Created date</th>
            <th class="px-1 py-2">Updated date</th>
            <th class="px-1 py-2" colspan="3">Action</th>
          </tr>
        </thead>
        <tbody v-for="user in users" :key="user.user_id">
          <tr
            class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
          >
            <td class="px-1 py-2">{{ user.user_id }}</td>
            <td class="px-1 py-2">{{ user.avatar }}</td>
            <td class="px-1 py-2">{{ user.name }}</td>
            <td class="px-1 py-2">{{ user.email }}</td>
            <td class="px-1 py-2">{{ slicedRoles(user.roles) }}</td>
            <td class="px-1 py-2">{{ user.created_at }}</td>
            <td class="px-1 py-2">{{ user.updated_at }}</td>
            <td class="px-2 py-2">
              <button
                @click="getUsers(user.user_id)"
                class="flex items-center h-8 px-1 py-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-blue-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                <i class="fa fa-eye"></i>
              </button>
            </td>
            <td class="px-2 py-2">
              <button
                @click="getUsers(user.user_id)"
                class="flex items-center h-8 px-1 py-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-green-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                <i class="fa fa-edit"></i>
              </button>
            </td>
            <td class="px-2 py-2">
              <button
                @click="deleteUsers(user.user_id)"
                class="flex items-center h-8 px-1 py-2 text-xs font-semibold text-white uppercase transition duration-150 ease-in-out bg-red-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import "font-awesome/css/font-awesome.min.css";
import axios from "axios";
export default {
  data() {
    return {
      users: false,
      roles_selected: "",
      name: "",
      roles: [],
      email: "",
      password: "",
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getUsers();
    this.getRoles();
  },
  methods: {
    async getRoles() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "https://10.236.102.139/api/list_role",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.roles = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching roles");
        }
      } catch (error) {
        console.log(error);
      }
    },

    async postUser() {
      try {
        const token = this.$store.state.token;
        const config = {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        };

        const postData = {
          name: this.name,
          role: this.roles_selected,
          email: this.email,
          password: this.password,
        };

        const response = await axios.post(
          "https://10.236.102.139/api/create_user",
          postData,
          config
        );

        if (response.status === 200) {
          console.log(response.data.data);
          this.getUsers();
        } else {
          console.log("Error posting user");
        }
      } catch (error) {
        console.log(error);
      }
    },

    async getUsers() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "https://10.236.102.139/api/list_user",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.users = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching users");
        }
      } catch (error) {
        console.log(error);
      }
    },

    async deleteUsers(user_id) {
      try {
        const token = this.$store.state.token;
        const response = await axios.delete(
          `https://10.236.102.139/api/delete_user/${user_id}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.users = response.data.data;
          console.log(response.data.data);
          this.getUsers();
        } else {
          console.log("Error deleting user");
        }
      } catch (error) {
        console.log(error);
      }
    },
    slicedRoles(roles) {
      return roles.toString().toUpperCase();
    },
  },
};
</script>
