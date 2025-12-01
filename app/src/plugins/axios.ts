import axios from 'axios'
import { useAuthStore } from '../stores/authStore' 

const axiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:3000',
  timeout: 10000,
})

// Intercepta todas as requisições
axiosInstance.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore()
    const token = authStore.token || localStorage.getItem('token')

    if (token && config.headers) {
      config.headers.Authorization = `Bearer ${token}`
    }

    return config
  },
  (error) => Promise.reject(error)
)

// Intercepta respostas e trata expiradas
axiosInstance.interceptors.response.use(
  (response) => response,
  async (error) => {
    const authStore = useAuthStore()
    const originalRequest = error.config

    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true

      try {
        const refreshed = await authStore.refreshToken()
        if (refreshed) {
          originalRequest.headers.Authorization = `Bearer ${authStore.token}`
          return axiosInstance(originalRequest)
        }
      } catch (refreshErr) {
        console.warn('Sessão expirada, deslogando...')
        authStore.logout()
        window.location.href = '/login'
      }
    }

    return Promise.reject(error)
  }
)

export default axiosInstance
