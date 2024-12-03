import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from '@/views/RegisterView.vue'
import LoginView from '@/views/LoginView.vue'
import ProfileView from '@/views/ProfileView.vue'
import NotFoundView from '@/views/NotFoundView.vue'
import CreateItemView from '@/views/CreateItemView.vue'
import EditItemView from '@/views/EditItemView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    },
    {
      path: '/profile',
      name: 'profile',
      component: ProfileView,
    },
    {
      path: '/item/create',
      name: 'create-item',
      component: CreateItemView,
    },
    {
      path: '/item/edit/:id',
      name: 'edit-item',
      component: EditItemView,
    },
    {
        path: '/:catchAll(.*)',
        name: 'not-found',
        component: NotFoundView
    }
  ],
})

export default router
