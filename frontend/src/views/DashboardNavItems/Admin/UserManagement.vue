<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full px-4 py-6 max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        User Manager
      </h1>
    </div>
  </header>
  <div class="flex h-80">
    <div class="bg-white sm:w-screen">
      <div class="flex items-center justify-center">
      <div class="float-left pl-8">
        <h1
          class="flex items-center justify-center text-2xl font-bold text-center"
        >
          User List
        </h1>
      </div>
      <div class="float-right pl-96">
        <router-link to="admin/user_management/user_add">
          <button
            class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-center text-white uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25"
          >
            <i class="fa fa-user-plus"></i>
          </button>
        </router-link>
      </div>
    </div>
    <br />
      <div class="flex items-center justify-center w-auto">
      <table class="w-auto mx-auto table-auto table-responsive sm:text-sm">
        <thead>
          <tr class="text-white bg-orange-500">
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Avatar</th>
            <th class="px-4 py-2 border">Name</th>
            <th class="px-4 py-2 border">Email</th>
            <th class="px-4 py-2 border">Role</th>
            <th class="px-4 py-2 border">Site</th>
            <th class="px-4 py-2 border" colspan="3">Actions</th>
          </tr>
        </thead>
        <tbody v-for="user in users" :key="user.user_id">
          <tr class="bg-gray-200">
            <td class="px-4 py-2 border">{{ user.user_id }}</td>
            <td class="px-4 py-2 border">{{ user.avatar }}</td>
            <td class="px-4 py-2 uppercase border">{{ user.name }}</td>
            <td class="px-4 py-2 border">{{ user.email }}</td>
            <td class="px-4 py-2 border">{{ slicedRoles(user.roles) }}</td>
            <td class="px-4 py-2 border">{{ user.site }}</td>
            <td class="px-0 py-2 border">
              <button
                @click="getUsers(user.user_id)"
                class="flex items-center h-8 px-4 py-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-blue-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                <router-link :to="`/user_show/${user.user_id}`">
                  <i class="fa fa-eye"></i>
                </router-link>
              </button>
            </td>
            <td class="px-0 py-2 border">
              <button
                @click="getUsers(user.user_id)"
                class="flex items-center h-8 px-4 py-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-green-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                <router-link :to="`/user_edit/${user.user_id}`">
                  <i class="fa fa-edit"></i>
                </router-link>
              </button>
            </td>
            <td class="px-0 py-2 border">
              <button
                @click="deleteUsers(user.user_id)"
                class="flex items-center h-8 px-4 py-2 text-xs font-semibold text-white uppercase transition duration-150 ease-in-out bg-red-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getUsers();
  },
  methods: {
    async getUsers() {
      await axios
        .get("http://127.0.0.1:8000/api/list_user")
        .then((response) => {
          this.users = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async deleteUsers(user_id) {
      await axios
        .delete("http://127.0.0.1:8000/api/delete_user/" + user_id)
        .then((response) => {
          this.users = response.data.data;
          console.log(response.data.data);
          this.getUsers();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    slicedRoles(roles) {
      return roles.toString().toUpperCase();
    },
  },
};
</script>
