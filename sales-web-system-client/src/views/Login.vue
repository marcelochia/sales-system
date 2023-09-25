<template>

  <div id="app" class="container">
    <div class="columns is-centered">
      <div class="column is-one-third">
        <div class="box">
          <h1 class="title">Login</h1>
          <form @submit.prevent="login">
            <div class="field">
              <label class="label">Usuário</label>
              <div class="control">
                <input v-model="username" class="input" type="text" placeholder="Seu usuário">
              </div>
            </div>
            <div class="field">
              <label class="label">Senha</label>
              <div class="control">
                <input v-model="password" class="input" type="password" placeholder="Sua senha">
              </div>
            </div>
            <div class="field">
              <div class="control">
                <button class="button is-info">Entrar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</template>

<script setup>

</script>

<style scoped>

</style>