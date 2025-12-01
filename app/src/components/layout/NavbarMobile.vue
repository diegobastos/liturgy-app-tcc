<template>
  <nav class="navbar bg-primary text-white p-2 d-flex justify-content-between">
    <button class="btn btn-sm btn-light" @click="$emit('toggleDrawer')">
      <i class="bi bi-list"></i>
    </button>
    <span class="fw-bold">Plataforma</span>
    <div></div>
  </nav>
</template>

<script setup lang="ts"></script>
