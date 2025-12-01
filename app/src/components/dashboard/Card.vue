<template>
  <div class="card border-0 shadow-sm h-100 overflow-hidden dashboard-card">
    <div class="card-body d-flex flex-column justify-content-between">

      <div class="d-flex align-items-center">
        <div class="me-3 icon-wrapper rounded-circle d-flex align-items-center justify-content-center">
          <i :class="icon" class="text-white fs-3"></i>
        </div>
        <h6 class="mb-0 text-muted fw-semibold">{{ title }}</h6>
      </div>

      <div>
        <h4 class="fw-bold mb-0">{{ value }}</h4>

        <p v-if="subtitle" class="text-muted small">
          {{ subtitle }}
        </p>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  title: String,
  value: [String, Number],
  subtitle: String,
  icon: String
});
</script>

<style scoped>
.dashboard-card {
  background: #ffffff;
  border-radius: 12px;
  position: relative;
}

.icon-wrapper {
  width: 44px;
  height: 44px;
  background: linear-gradient(135deg, #0d6efd, #3b82f6);
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
</style>
