<script setup>
import Menu from "./Menu.vue";
</script>

<template>
  <header>
    <h1>
      <img src="../assets/logo.png" alt="Alura Tracker">
    </h1>
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