<template>
  <form v-on:submit.prevent="save()">
    <div class="field">
      <label class="label">Name</label>
      <div class="control">
        <input class="input" v-model="seller.name" type="text" placeholder="Nome do vendedor">
      </div>
    </div>

    <div class="field">
      <label class="label">Email</label>
      <div class="control">
        <input class="input" v-model="seller.email" type="email" placeholder="email@dominio.com.br">
      </div>
    </div>

    <div class="field is-grouped">
      <div class="control">
        <button type="submit" class="button is-link" :class="{ 'is-loading': isLoading }">
          {{ sellerId ? 'Editar' : 'Cadastrar' }}
        </button>
      </div>
      <div class="control">
        <button type="button" class="button is-link is-light" @click="goToSellersList">Cancelar</button>
      </div>
    </div>

    <div>
      <p v-if="successMessage" class="has-text-success">{{ successMessage }}</p>
      <p v-if="errorMessage" class="has-text-danger">{{ errorMessage }}</p>
    </div>

  </form>

</template>

<script setup>
import http from '../services/http.js';
import { onMounted, reactive, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const isLoading = ref(false);
const errorMessage = ref(null);
const successMessage = ref(null);
const sellerId = ref(null);

onMounted(() => {
  sellerId.value = route.params.id;

  if (sellerId.value) {
    getSeller();
  }
});

const seller = reactive({
  name: '',
  email: ''
});

async function getSeller() {
  isLoading.value = true;
  try {
    const {data} = await http.get(`/sellers/${sellerId.value}`);

    seller.name = data.name;
    seller.email = data.email;

    successMessage.value = null;
    errorMessage.value = null;
  } catch (error) {
    successMessage.value = null;
    errorMessage.value = error?.response?.data.error;
  } finally {
    isLoading.value = false;
  }
}

function save() {
  if (sellerId) {
    updateSeller();
  } else {
    createSeller();
  }
}

async function createSeller() {
  isLoading.value = true;
  try {
    await http.post('/sellers', seller);
    successMessage.value = 'Vendedor cadastrado com sucesso.'
    errorMessage.value = null;
  } catch (error) {
    successMessage.value = null;
    errorMessage.value = error?.response?.data.error;
  } finally {
    isLoading.value = false;
  }
}

async function updateSeller() {
  isLoading.value = true;
  try {
    await http.put(`/sellers/${sellerId.value}`, seller);
    successMessage.value = 'Vendedor atualizado com sucesso.'
    errorMessage.value = null;
  } catch (error) {
    successMessage.value = null;
    errorMessage.value = error?.response?.data.error;
  } finally {
    isLoading.value = false;
  }
}

function goToSellersList() {
  router.push({name: 'sellers'})
}
</script>


<style>

</style>
