<template>
  <div class="sidebar">
    <h2>Users</h2>
    <ul>
      <li v-for="(user, index) in users" :key="index">
        <router-link :to="'/home/chat/' + user.id">{{ user.name }}</router-link>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      name: 'SidebarComponent',
      users: [],
      selectedUser: null
    };
  },
  mounted () {
    this.fetchUsers()
  },
  methods: {
    fetchUsers () {
      axios.get('http://api.local:8000/api/user/get-users-to-chat')
          .then((response) => this.users = response.data)
          .catch(() => console.log('Error fetching users'))
    },
  }
};
</script>

<style>

.sidebar {
  width: 25%;
  padding: 20px;
  background-color: #f0f0f0;
  overflow-y: auto;
}

ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

li {
  cursor: pointer;
  padding: 10px 0;
}

li:hover {
  background-color: #eaeaea;
}

</style>
