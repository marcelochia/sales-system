<template>
  <h1>Vendedores</h1>

  <div>
    <router-link :to="{name: 'createSeller'}" class="button is-info">Cadastrar</router-link>
  </div>

  <SellersList />
</template>

<script setup>
import SellersList from "../components/sellers/SellersList.vue";
</script>

<style>

</style>
