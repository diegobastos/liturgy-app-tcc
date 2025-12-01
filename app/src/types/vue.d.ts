import axiosInstance from '@/plugins/axios'

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $axios: typeof axiosInstance
  }
}
