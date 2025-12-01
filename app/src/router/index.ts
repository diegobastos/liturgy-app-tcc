import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '../stores/authStore'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/pages/Auth/LoginView.vue'),
    meta: { public: true }
  },
  {
    path: '/',
    name: 'Dashboard',
    component: () => import('@/pages/Dashboard/Dashboard.vue')
  },
  {
    path: '/profile',
    name: 'Profile',
    component: () => import('@/pages/Profile/Profile.vue')
  },
  {
    path: '/settings',
    name: 'Settings',
    component: () => import('@/pages/Settings.vue')
  },
  {
    path: '/events',
    name: 'Events',
    component: () => import('@/pages/Event/EventList.vue')
  },
  {
    path: '/events/new',
    name: 'EventCreate',
    component: () => import('@/pages/Event/EventForm.vue')
  },
  {
    path: '/events/edit/:id',
    name: 'EventEdit',
    component: () => import('@/pages/Event/EventForm.vue'),
    props: true
  },
  {
    path: '/musics',
    name: 'Musics',
    component: () => import('@/pages/Music/MusicList.vue')
  },
  {
    path: '/musics/new',
    name: 'MusicCreate',
    component: () => import('@/pages/Music/MusicForm.vue')
  },
  {
    path: '/musics/edit/:id',
    name: 'MusicEdit',
    component: () => import('@/pages/Music/MusicForm.vue'),
    props: true
  },
  {
    path: '/scales',
    name: 'Scales',
    component: () => import('@/pages/Scale/ScaleList.vue')
  },
  {
    path: '/scales/new',
    name: 'ScaleCreate',
    component: () => import('@/pages/Scale/ScaleForm.vue')
  },
  {
    path: '/scales/edit/:id',
    name: 'ScaleEdit',
    component: () => import('@/pages/Scale/ScaleForm.vue'),
    props: true
  },
  {
    path: '/users',
    name: 'Users',
    component: () => import('@/pages/User/UserList.vue')
  },
  {
    path: '/users/new',
    name: 'UserCreate',
    component: () => import('@/pages/User/UserForm.vue')
  },
  {
    path: '/users/edit/:id',
    name: 'UserEdit',
    component: () => import('@/pages/User/UserForm.vue'),
    props: true
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/pages/NotFound.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, _from, next) => {
  const authStore = useAuthStore()
  const isPublic = to.meta.public === true

  if (isPublic) return next()

  // Se não tiver token, volta pro login
  if (!authStore.token) {
    return next({ name: 'Login', query: { redirect: to.fullPath } })
  }

  // Se tiver token mas não tiver user carregado, valida
  if (!authStore.user) {
    const valid = await authStore.checkAuth()
    if (!valid) {
      return next({ name: 'Login', query: { redirect: to.fullPath } })
    }
  }

  return next()
})



export default router
