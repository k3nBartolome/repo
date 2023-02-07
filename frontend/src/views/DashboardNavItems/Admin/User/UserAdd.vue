<template>
  <div class="flex items-center justify-center h-screen">
    <form @submit.prevent="postUser">
      <h3 class="text-lg font-medium">Create User</h3>
      <div class="form-group">
        <label class="block mb-2 font-medium text-gray-700">Name</label>
        <input type="text" v-model="name"
          class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" />
      </div>
      <div class="form-group">
        <label class="block mb-2 font-medium text-gray-700">Role</label>
        <select v-model="roles_selected"
          class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
          @change="getRoles">
          <option disabled value="" selected>Please select one</option>
          <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
        </select>
      </div>
      <div class="form-group">
        <label class="block mb-2 font-medium text-gray-700">Email</label>
        <input type="email" v-model="email"
          class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" />
      </div>
      <div class="form-group">
        <label class="block mb-2 font-medium text-gray-700">Password</label>
        <input type="password" v-model="password"
          class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" />
      </div>
      <button type="submit"
        class="px-4 py-2 font-medium text-white bg-gray-800 rounded hover:bg-gray-900 focus:outline-none focus:shadow-outline">Create
        User</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      roles_selected: "",
      name: "",
      roles: [],
      email: "",
      password: "",
    }
  },
  mounted() {
    console.log('Component mounted.')
    this.getRoles()
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
          console.log(response.data.data)
        })
        .catch((error) => {
          console.log(error)
        })
    }
  }
}
</script>
