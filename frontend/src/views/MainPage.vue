<template>
  <router-link to="/login" @click="logout">logout</router-link>
  <router-view></router-view>
</template>
<script>
export default {
  name: 'MainPage',
  methods: {
    logout() {
      this.$store.dispatch('logout')
    }
  },
  created () {
    this.$store.dispatch('initUser')
  }
}
</script>

<style scoped>

</style>