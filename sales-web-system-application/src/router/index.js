import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/vendedores',
      name: 'sellers',
      component: () => import('../views/Sellers.vue')
    },
    {
      path: '/vendedores/cadastrar',
      name: 'SellerForm',
      component: () => import('../views/SellerForm.vue')
    },
    {
      path: '/vendedores/:id/editar',
      name: 'editSeller',
      component: () => import('../views/SellerForm.vue')
    },
    {
      path: '/vendas',
      name: 'salesList',
      component: () => import('../views/SalesList.vue')
    },
  ]
})

export default router
