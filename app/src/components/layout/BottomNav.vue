<template>
  <nav v-if="false" class="navbar navbar-light bg-light border-top">
    <ul class="nav justify-content-around w-100">
      <li class="nav-item">
        <router-link to="/" class="nav-link text-center">
          <i class="bi bi-speedometer2"></i>
          <div style="font-size: 0.75rem;">Dashboard</div>
        </router-link>
      </li>
      <li class="nav-item">
        <router-link to="/profile" class="nav-link text-center">
          <i class="bi bi-person"></i>
          <div style="font-size: 0.75rem;">Perfil</div>
        </router-link>
      </li>
      <li class="nav-item">
        <router-link to="/settings" class="nav-link text-center">
          <i class="bi bi-gear"></i>
          <div style="font-size: 0.75rem;">Config</div>
        </router-link>
      </li>
    </ul>
  </nav>
</template>

<script setup lang="ts"></script>
