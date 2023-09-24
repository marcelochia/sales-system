<script setup>
import Sidebar from "./components/Sidebar.vue"
</script>

<template>
  <main class="columns is-gapless is-multiline">
    <div class="column is-one-quarter">
      <Sidebar />
    </div>
    <div class="column is-three-quarter content">
      <div class="dash">
        <router-view />
      </div>
    </div>
  </main>
</template>

<style>
.dash {
  padding: 1.25rem;
}

main {
  --bg-primary: #fff;
  --text-primary: #000;
}

.content {
  background-color: var(--bg-primary);
}
</style>
