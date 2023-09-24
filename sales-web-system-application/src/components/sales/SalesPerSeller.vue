<template>

  <h1>Vendas por vendedor</h1>

  <div>
    <p  v-if="successMessage" class="has-text-success">{{ successMessage }}</p>
    <p  v-if="errorMessage" class="has-text-danger">{{ errorMessage }}</p>
  </div>

  <form v-on:submit.prevent="getSalesPerSeller()">
    <label class="label">Vendedor</label>
    <div class="field is-grouped">
      <div class="field">
        <div class="control">
          <div class="select">
            <select v-model="sellerId">
              <option v-for="seller in sellers" :key="seller.id" :value="seller.id">{{ seller.name }}</option>
            </select>
          </div>
        </div>
      </div>

      <div class="control">
        <button type="submit" class="button is-link ml-3" :class="{ 'is-loading': isLoading }">Buscar</button>
      </div>
    </div>

  </form>

  <div v-if="isLoading"><Loading /></div>
  <div v-else class="table-container">
    <table class="table is-striped is-narrow is-hoverable">
      <thead>
        <th>Data</th>
        <th>Total</th>
      </thead>
      <tbody>
        <tr v-for="sales in sales" :key="sales.id">
          <td>{{ formatDate(sales.date) }}</td>
          <td>{{ formatCurrency(sales.value) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

</template>

<script setup>
import { formatCurrency, formatDate } from '../../helpers/format';
import http from '../../services/http.js';
import Loading from '../Loading.vue';
import { onMounted, reactive, ref } from "vue";

onMounted(() => {
  getSellers();
});

const isLoading = ref(false);
const successMessage = ref(null);
const errorMessage = ref(null);

const sellerId = ref(null);
const sales = reactive([]);
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

async function getSalesPerSeller() {
  try {
    isLoading.value = true;

    sales.length = 0;

    const {data} = await http.get(`/sellers/${sellerId.value}/sales`);

    data.forEach(data => {
      sales.push({
        value: data.value,
        date: data.date,
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
</script>

<style scoped>
.table-container {
  max-height: 80vh;
  overflow-y: auto;
}
</style>
