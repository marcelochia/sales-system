<template>

  <div>
    <p  v-if="successMessage" class="has-text-success">{{ successMessage }}</p>
    <p  v-if="errorMessage" class="has-text-danger">{{ errorMessage }}</p>
  </div>

  <div v-if="isLoading"><Loading /></div>
  <div v-else class="table-container box mt-3">
    <table class="table is-striped is-narrow is-hoverable">
      <thead>
        <th>Nome</th>
        <th>Email</th>
        <th style="text-align: center;">Ações</th>
      </thead>
      <tbody>
        <tr v-for="seller in sellers" :key="seller.id">
          <td>{{ seller.name }}</td>
          <td>{{ seller.email }}</td>
          <td class="is-flex is-justify-content-center">
            <router-link :to="{name: 'editSeller', params: {id: seller.id}}" class="button is-info is-small is-warning">Editar</router-link>
            <button class="button is-info is-small is-danger ml-1" @click="deleteSeller(seller.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

</template>

<script setup>
import http from '../../services/http.js';
import Loading from '../Loading.vue';
import { onMounted, reactive, ref } from "vue";

onMounted(() => {
  getSellers();
});

const isLoading = ref(false);
const successMessage = ref(null);
const errorMessage = ref(null);

const sellers = reactive([]);

async function getSellers() {
  try {
    isLoading.value = true;

    sellers.length = 0;

    const response = await http.get('/sellers', {
      params: {
        sortBy: 'name',
        order: 'asc'
      }
    });

    response.data.forEach(data => {
      sellers.push({
        id: data.id,
        name: data.name,
        email: data.email
      });
    });

    errorMessage.value = null;
  } catch (error) {
    if (error.code === 'ERR_NETWORK') {
      errorMessage.value = 'Ocorreu um erro ao conectar ao servidor.';
    }
  } finally {
    isLoading.value = false;
  }
}

async function deleteSeller(id) {
  isLoading.value = true;
  try {
    await http.delete(`/sellers/${id}`);
    successMessage.value = 'Vendedor excluído com sucesso.';
    errorMessage.value = null;

    getSellers();
  } catch (error) {
    errorMessage.value = error.response.data.error;
    successMessage.value = null;
  } finally {
    isLoading.value = false;
  }
}
</script>

<style scoped>
.table-container {
  max-height: 80vh;
  overflow-y: auto;
}
</style>
