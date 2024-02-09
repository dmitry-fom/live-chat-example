<template>
  <div>
    <h2>Registration</h2>
    <form @submit.prevent="register">
      <input type="text" v-model="name" placeholder="name" required>
      <input type="email" v-model="email" placeholder="Email" required>
      <input type="password" v-model="password" placeholder="Password" required>
      <input type="password" v-model="password_confirmation" placeholder="Repeat Password" required>
      <button type="submit">Submit</button>
    </form>
    <router-link to="/login">Login</router-link>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }
  },
  methods: {
    register() {
      axios.post('http://api.local:8000/api/auth/register', {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation
      })
          .then(() => this.$router.push('/login'))
          .catch(error => console.log(error));
    }
  }
}
</script>

<style scoped>
/* Стилизуйте форму по своему усмотрению */
</style>