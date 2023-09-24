<template>
  <h1>Vendedores</h1>

  <div>
    <router-link :to="{name: 'SellerForm'}" class="button is-link">Cadastrar</router-link>
  </div>

  <SellersList />
</template>

<script setup>
import SellersList from "../components/sellers/SellersList.vue";
</script>

<style>

</style>
