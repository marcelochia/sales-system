<template>

  <h1>Total de vendas por dia</h1>

  <div>
    <p  v-if="successMessage" class="has-text-success">{{ successMessage }}</p>
    <p  v-if="errorMessage" class="has-text-danger">{{ errorMessage }}</p>
  </div>

  <div v-if="isLoading"><Loading /></div>

  <div v-else class="box mt-3 table-container">
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
  getSalesPerDay();
});

const isLoading = ref(false);
const successMessage = ref(null);
const errorMessage = ref(null);

const sales = reactive([]);

async function getSalesPerDay() {
  try {
    isLoading.value = true;

    sales.length = 0;

    const {data} = await http.get('/sales/daily-total');

    for (const key in data) {
      if (Object.hasOwnProperty.call(data, key)) {
        const value = data[key];

        sales.push({
          date: key,
          value: value
        });
      }
    }

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
