<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full py-2  max-w-screen-xl sm:px-2 lg:px-2">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900 pl-8">
        User Manager
      </h1>
    </div>
  </header>
  <div class="py-8">
    <div class="px-4 py-6 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8 border-2 border-orange-600" >
      <form @submit.prevent="postUser" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5 font-semibold">
        <label class="block">
          Name
          <input v-model="name" type="text"
            class="block w-full mt-1 border rounded-md  focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required />
        </label>
        <label class="block">
          Email
          <input v-model="email" type="email"
            class="block w-full mt-1 border rounded-md  focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required />
        </label>
        <label class="block">
            Roles
            <select v-model="roles_selected"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required @change="getRoles">
              <option disabled value="" selected>Please select one</option>
              <option v-for="role in roles" :key="role.id" :value="role.name">
                {{ role.name }}
              </option>
            </select>
          </label>
        <label class="block">
          Password
          <input v-model="password" type="password"
            class="block w-full mt-1 border rounded-md  focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required />
        </label>
        <button type="submit" class="bg-orange-600 hover:bg-gray-600 text-white font-bold py-1 px-4 rounded">
          <i class="fa fa-building	"></i> Add
        </button>
      </form>
    </div>
  </div>
  <div class="py-8">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <table class="w-full table-auto text-white">
        <thead>
          <tr class="text-left bg-orange-600 border-solid border-2 border-orange-600">
            <th class="px-1 py-2 ">ID</th>
            <th class="px-1 py-2 ">Avatar</th>
            <th class="px-1 py-2 ">Name</th>
            <th class="px-1 py-2 ">Email</th>
            <th class="px-1 py-2 ">Roles</th>
            <th class="px-1 py-2 ">Created date</th>
            <th class="px-1 py-2 ">Updated date</th>
            <th class="px-1 py-2 " colspan="3">Action</th>
          </tr>
        </thead>
        <tbody v-for="user in users" :key="user.user_id">
          <tr class="bg-white text-black font-semibold border-2 border-solid border-gray-400">
            <td class="px-1 py-2 ">{{ user.user_id }}</td>
            <td class="px-1 py-2 ">{{ user.avatar }}</td>
            <td class="px-1 py-2 ">{{ user.name }}</td>
            <td class="px-1 py-2 ">{{ user.email }}</td>
            <td class="px-1 py-2 ">{{ slicedRoles(user.roles) }}</td>
            <td class="px-1 py-2 ">{{ user.created_at }}</td>
            <td class="px-1 py-2 ">{{ user.updated_at }}</td>
            <td class="px-2 py-2 "><button @click="getUsers(user.user_id)"
                class="flex items-center h-8 px-1 py-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-blue-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25">
                <i class="fa fa-eye"></i>
              </button>
            </td>
            <td class="px-2 py-2">
              <button @click="getUsers(user.user_id)"
                class="flex items-center h-8 px-1 py-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-green-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25">
                <i class="fa fa-edit"></i>
              </button>
            </td>
            <td class="px-2 py-2 ">
              <button   @click="deleteUsers(user.user_id)"
                class="flex items-center h-8 px-1 py-2 text-xs font-semibold text-white uppercase transition duration-150 ease-in-out bg-red-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25">
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
      console.log(this.roles_selected)
      await axios.get('http://127.0.0.1:8000/api/list_role')
        .then((response) => {
          this.roles = response.data.data;
          console.log(response.data.data)
        })
        .catch((error) => {
          console.log(error)
        })
    },
    async postUser() {
      await axios.post('http://127.0.0.1:8000/api/create_user', {
        name: this.name,
        role: this.roles_selected,
        email: this.email,
        password: this.password,

      })
        .then((response) => {
          console.log(response.data.data),
          this.getUsers();
        })
        .catch((error) => {
          console.log(error)
        })
    },
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
