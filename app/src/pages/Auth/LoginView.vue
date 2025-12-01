<template>
  <div class="login-page d-flex align-items-center justify-content-center">
    <div class="card login-card shadow-lg p-4 border-0">
      <div class="text-center mb-4">
        <img
          
          alt="Logo"
          class="img-fluid mb-2"
          style="width: 90px; height: auto;"
        />
        <h4 class="fw-bold text-primary mb-0">Bem-vindo de volta</h4>
        <small class="text-muted">Faça login para continuar</small>
      </div>

      <form @submit.prevent="handleLogin">
        <div class="mb-3">
          <label class="form-label">E-mail</label>
          <input
            v-model="email"
            type="text"
            class="form-control form-control-lg"
            placeholder="Digite seu e-mail"
            required
          />
        </div>

        <div class="mb-3">
          <label class="form-label">Senha</label>
          <input
            v-model="password"
            type="password"
            class="form-control form-control-lg"
            placeholder="Digite sua senha"
            required
          />
        </div>

        <button
          type="submit"
          class="btn btn-primary w-100 py-2 fw-semibold"
          :disabled="auth.loading"
        >
          <span
            v-if="auth.loading"
            class="spinner-border spinner-border-sm me-2"
          ></span>
          {{ auth.loading ? 'Entrando...' : 'Entrar' }}
        </button>

        <div
          v-if="error"
          class="alert alert-danger mt-3 mb-0 text-center py-2 small"
        >
          {{ error }}
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/authStore' 

const router = useRouter()
const auth = useAuthStore()

const email = ref('')
const password = ref('')
const error = ref('')

onMounted(async () => {
  const isLogged = auth.user || await auth.checkAuth()
  if (isLogged) {
    router.push('/')
  }
})

const handleLogin = async () => {
  const success = await auth.login(email.value, password.value)
  if (success) {
    router.push('/')
  } else {
    error.value = 'E-mail ou senha incorretos.'
  }
}
</script>

<style scoped>
/* --- Estrutura geral --- */
.login-page {
  min-height: 100vh;
  background: linear-gradient(
      rgba(0, 0, 0, 0.5),
      rgba(0, 0, 0, 0.5)
    ),
    url('@/assets/logo.png') center/cover no-repeat;
    
  padding: 1rem;
}

/* --- Card central --- */
.login-card {
  width: 100%;
  max-width: 400px;
  background-color: #ffffffd9;
  border-radius: 1rem;
  backdrop-filter: blur(6px);
  animation: fadeIn 0.6s ease-in-out;
}

/* --- Transições suaves --- */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* --- Ajustes para dispositivos móveis --- */
@media (max-width: 576px) {
  .login-page {
    background: #f8f9fa;
  }

  .login-card {
    box-shadow: none !important;
    max-width: 100%;
    height: 100%;
    border-radius: 0;
    justify-content: center;
  }

  .login-card form {
    margin-top: 2rem;
  }
}
</style>
