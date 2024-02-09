<template>
  <div>
    <h2>Log in</h2>
    <form @submit.prevent="login">
      <input type="email" v-model="email" placeholder="Email" required>
      <input type="password" v-model="password" placeholder="Пароль" required>
      <button type="submit">Submit</button><br>
      <router-link to="/register">Register</router-link>
    </form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    login () {
      this.$store.dispatch('login', { email: this.email, password: this.password })
          .then((auth) => {
            localStorage.setItem('accessToken', auth.access_token)
            this.$router.push('/home')
          })
          .catch(error => {
            console.error('Error logging in:', error)
          })
    }
  }
}
</script>

<style scoped>

</style>