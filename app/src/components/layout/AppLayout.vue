<template>
  <div class="bg-wrapper">
    <div class="d-flex flex-column flex-lg-row vh-100">
      <!-- Sidebar desktop -->
      <Sidebar
        class="d-none d-lg-flex"
        :collapsed="collapsed"
        @toggle="collapsed = !collapsed"
      />
  
      <!-- Conteúdo principal -->
      <div class="flex-grow-1 position-relative">
        <!-- Navbar mobile -->
        <NavbarMobile @toggleDrawer="drawerOpen = !drawerOpen" class="d-lg-none" />
  
        <!-- Drawer mobile -->
        <div
          class="drawer bg-light position-fixed top-0 start-0 vh-100 p-3 d-lg-none"
          v-if="drawerOpen"
          style="width: 250px; z-index: 1050;"
        >
          <button class="btn btn-sm btn-outline-secondary mb-3" @click="drawerOpen = false">
            Fechar
          </button>
          <Sidebar />
        </div>
  
        <div class="p-4">
          <router-view />
        </div>
  
        <!-- Bottom navigation mobile -->
        <BottomNav class="d-lg-none fixed-bottom" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Sidebar from './Sidebar.vue'
import NavbarMobile from './NavbarMobile.vue'
import BottomNav from './BottomNav.vue'

const collapsed = ref(false)   // desktop retrátil
const drawerOpen = ref(false)  // mobile drawer
</script>

<style>
.bg-wrapper {
  background-color: #f0f0f0; /* #F5F7F9;*/
}
</style>
