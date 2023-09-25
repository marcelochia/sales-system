<template>
  <div>
    <div class="link">
      <router-link :to="{ name: 'sellers' }">Vendedores</router-link>
    </div>
    <div class="link">
      <router-link :to="{ name: 'sales' }">Vendas</router-link>
    </div>
    <div class="link">
      <router-link :to="{ name: 'salesPerSeller' }">Vendas por vendedor</router-link>
    </div>
    <div class="link">
      <router-link :to="{ name: 'salesTotalPerDay' }">Relatório de vendas por dia</router-link>
    </div>
  </div>
</template>

<style scoped>
.link {
  text-align: left;
  margin: 0.5rem 0;
  padding: 0.25rem 1rem;
  background-color: #062139;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.link:hover {
  background-color: #1a1a1a;
}

a {
  color: white;
}
</style>