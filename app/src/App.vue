<template>
  <component :is="layoutComponent" />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppLayout from './components/layout/AppLayout.vue'
import LoginView from './pages/Auth/LoginView.vue'

const route = useRoute()

const layoutComponent = computed(() => {
  if (route.name === 'Login') return LoginView
  return AppLayout
})
</script>
