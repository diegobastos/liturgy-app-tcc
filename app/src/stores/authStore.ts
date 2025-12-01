import { defineStore } from 'pinia'
import { jwtDecode } from 'jwt-decode'
import axiosInstance from '@/plugins/axios' 

interface DecodedToken {
  sub: number
  name: string
  username: string
  email: string
  exp: number
  iat: number
}

interface User {
  id: number
  name: string
  username: string
  email: string
}

interface AuthState {
  token: string | null
  user: User | null
  refreshInterval: number | null
}

export const useAuthStore = defineStore('authStore', {
  state: (): AuthState => ({
    token: localStorage.getItem('token'),
    user: null,
    refreshInterval: null
  }),

  persist: true,

  getters: {
    isAuthenticated: (state) => !!state.token
  },

  actions: {
    async login(email: string, password: string) {
      const resp = await axiosInstance.post('/auth/login', { email, password })
      const { token } = resp.data

      if (!token) {
        return false
      }        

      this.token = token
      //localStorage.setItem('token', token)

      const decoded = jwtDecode<DecodedToken>(token)
      this.user = {
        id: decoded.sub,
        name: decoded.name,
        username: decoded.username,
        email: decoded.email
      }

      this.startTokenValidationLoop()
      return true
    },

    /**
     * Logout e limpeza de sessão
     */
    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('token')
      if (this.refreshInterval) clearInterval(this.refreshInterval)
      this.refreshInterval = null
    },

    /**
     * Inicializa loop que verifica o token a cada X minutos
     */
    startTokenValidationLoop() {
      if (this.refreshInterval) clearInterval(this.refreshInterval)

      this.refreshInterval = window.setInterval(async () => {
        const isValid = await this.checkAuth()
        if (!isValid) {
          console.warn('Token expirado durante o loop. Deslogando...')
          this.logout()
          window.location.href = '/login'
        }
      }, 10 * 60 * 1000) // 10 minutos
    },

    /**
     * Verifica validade do token JWT
     */
    async checkAuth(): Promise<boolean> {
      if (!this.token) return false

      try {
        const decoded = jwtDecode<DecodedToken>(this.token)
        const now = Math.floor(Date.now() / 1000)

        // Se faltar menos de 2 minutos, tenta renovar
        if (decoded.exp - now < 120) {
          const refreshed = await this.refreshToken()
          if (!refreshed) return false
        }

        return decoded.exp > now
      } catch (err) {
        console.error('Erro ao verificar token:', err)
        return false
      }
    },

    /**
     * Tenta renovar token JWT com o backend
     */
    async refreshToken(): Promise<boolean> {
      if (!this.token) return false

      try {
        const resp = await axiosInstance.post('/auth/refresh', { token: this.token })
        if (resp.data?.token) {
          this.token = resp.data.token
          localStorage.setItem('token', resp.data.token)

          const decoded = jwtDecode<DecodedToken>(resp.data.token)
          this.user = {
            id: decoded.sub,
            name: decoded.name,
            username: decoded.username,
            email: decoded.email
          }

          return true
        }
      } catch (err) {
        console.error('Erro ao renovar token:', err)
      }

      return false
    }
  }
})
