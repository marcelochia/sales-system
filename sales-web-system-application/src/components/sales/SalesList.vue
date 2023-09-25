<template>

  <div>
    <p  v-if="successMessage" class="has-text-success">{{ successMessage }}</p>
    <p  v-if="errorMessage" class="has-text-danger">{{ errorMessage }}</p>
  </div>

  <div v-if="isLoading"><Loading /></div>
  <div v-else class="table-container box mt-3">
    <table class="table is-striped is-narrow is-hoverable">
      <thead>
        <th>Data</th>
        <th>Valor</th>
        <th>Vendedor</th>
        <th>Ação</th>
      </thead>
      <tbody>
        <tr v-for="sale in sales" :key="sale.id">
          <td>{{ formatDate(sale.date) }}</td>
          <td>{{ formatCurrency(sale.value) }}</td>
          <td>{{ sale.seller.name }}</td>
          <button class="button is-info is-small is-danger ml-1" @click="deleteSale(sale.id)">Excluir</button>
        </tr>
      </tbody>
    </table>
  </div>

</template>

<script setup>
import { formatDate, formatCurrency } from "../../helpers/format.js";
import http from '../../services/http.js';
import Loading from '../Loading.vue';
import { onMounted, reactive, ref } from "vue";

onMounted(() => {
  getSales();
});

const isLoading = ref(false);
const successMessage = ref(null);
const errorMessage = ref(null);

const sales = reactive([]);

async function getSales() {
  try {
    isLoading.value = true;

    sales.length = 0;

    const response = await http.get('/sales');

    response.data.forEach(data => {
      sales.push({
        id: data.id,
        value: data.value,
        date: data.date,
        seller: {
          id: data.seller.id,
          name: data.seller.name
        }
      });
    });

    await bindingSalesAndSellers();

    errorMessage.value = null;
  } catch (error) {
    if (error.code === 'ERR_NETWORK') {
      errorMessage.value = 'Ocorreu um erro ao conectar ao servidor.';
    }
  } finally {
    isLoading.value = false;
  }
}

async function bindingSalesAndSellers() {
  try {
    isLoading.value = true;

    sellers.length = 0;

    const {data} = await http.get('/sellers', {
      params: {
        sortBy: 'name',
        order: 'asc'
      }
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

async function deleteSale(id) {
  isLoading.value = true;
  try {
    await http.delete(`/sales/${id}`);
    successMessage.value = 'Venda excluída com sucesso.';
    errorMessage.value = null;

    getSales();
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
