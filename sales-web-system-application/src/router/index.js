import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/Sellers.vue')
    },
    {
      path: '/vendedores',
      name: 'sellers',
      component: () => import('../views/Sellers.vue')
    },
    {
      path: '/vendedores/cadastrar',
      name: 'createSeller',
      component: () => import('../views/SellerForm.vue')
    },
    {
      path: '/vendedores/:id/editar',
      name: 'editSeller',
      component: () => import('../views/SellerForm.vue')
    },
    {
      path: '/vendas',
      name: 'sales',
      component: () => import('../views/Sales.vue')
    },
    {
      path: '/vendas/adicionar',
      name: 'createSale',
      component: () => import('../components/sales/SalesForm.vue')
    },
    {
      path: '/vendas/total-por-dia',
      name: 'salesTotalPerDay',
      component: () => import('../components/sales/SalesTotalPerDay.vue')
    },
    {
      path: '/vendas/por-vendedor',
      name: 'salesPerSeller',
      component: () => import('../components/sales/SalesPerSeller.vue')
    },
  ]
})

export default router
