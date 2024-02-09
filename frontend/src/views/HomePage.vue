<template>
  <div class="app-container">
    <div class="telegram-layout">
      <SidebarComponent></SidebarComponent>
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import SidebarComponent from '@/components/SidebarComponent'

export default {
  components: {
    SidebarComponent
  }
}
</script>

<style scoped>
.app-container {
  height: 100vh;
  overflow: hidden;
}
.telegram-layout {
  display: flex;
  height: 100%;
}
</style>