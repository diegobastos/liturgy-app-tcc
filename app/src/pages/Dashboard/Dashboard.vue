<script setup lang="ts">
import { useAuthStore } from '../../stores/authStore';
import DashboardEvent from './DashboardEvent.vue';
import DashboardMusic from './DashboardMusic.vue';

const authStore = useAuthStore()
</script>

<style>
</style>

<template>
  <div class="dashboard-wrapper">
    <div>
      <h2>Olá, {{ authStore.user?.name.split(' ')[0] }}</h2>
    </div>

    <DashboardEvent />
    <DashboardMusic />
  </div>
</template>
