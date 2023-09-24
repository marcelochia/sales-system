<template>
  <h1>Vendas</h1>

  <div>
    <router-link :to="{name: 'createSale'}" class="button is-link">Adicionar</router-link>
  </div>

  <SalesList />
</template>

<script setup>
import SalesList from "../components/sales/SalesList.vue";
</script>

<style>

</style>
