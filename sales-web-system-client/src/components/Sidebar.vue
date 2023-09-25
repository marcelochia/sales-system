<script setup>
import Menu from "./Menu.vue";
</script>

<template>
  <header>

    <div class="box has-background-info">
      <h1 class="has-text-white	has-text-weight-bold is-size-4">Sistema de Vendas</h1>
    </div>

    <Menu />
  </header>
</template>

<style scoped>
header {
  padding: 1rem;
  background: #0d3b66;
  width: 100%;
  height: 100vh;
  text-align: center
}

@media only screen and (max-width: 768px) {
  header {
    padding: 2.5rem;
    height: auto;
  }
}
</style>