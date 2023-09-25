<template>

  <h1>Vendas por vendedor</h1>

  <div>
    <p  v-if="successMessage" class="has-text-success">{{ successMessage }}</p>
    <p  v-if="errorMessage" class="has-text-danger">{{ errorMessage }}</p>
  </div>

  <div class="box">

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
          <button type="submit" class="button is-info ml-3" :disabled="!sellerId">Buscar</button>
        </div>
      </div>

    </form>

    <div v-if="isLoading"><Loading /></div>
    <div v-else class="table-container">
      <table class="table is-striped is-narrow is-hoverable">
        <thead>
          <th>Data</th>
          <th>Total</th>
          <th>Ação</th>
        </thead>
        <tbody>
          <tr v-for="sales in sales" :key="sales.id">
            <td>{{ formatDate(sales.date) }}</td>
            <td>{{ formatCurrency(sales.value) }}</td>
            <td>
              <button class="button is-info is-small" @click="resendReport(sales.date)">Reenviar relatório para vendedor</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="isSearched && sales.length === 0 && !isLoading">
      <p>O vendedor ainda não realizou nenhuma venda.</p>
    </div>
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

const isSearched = ref(false);
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
    if (sellerId.value === null) {
      return;
    }

    isLoading.value = true;

    sales.length = 0;

    const {data} = await http.get(`/sellers/${sellerId.value}/sales`);

    data.forEach(data => {
      sales.push({
        value: data.value,
        date: data.date,
      });
    });

    isSearched.value = true;

    errorMessage.value = null;
  } catch (error) {
    if (error.code === 'ERR_NETWORK') {
      errorMessage.value = 'Ocorreu um erro ao conectar ao servidor.';
    }
  } finally {
    isLoading.value = false;
  }
}

async function resendReport(date) {
  try {
    isLoading.value = true;

    await http.post(`/sellers/${sellerId.value}/sales/send-report`, {
      date: date
    });

    successMessage.value = 'Relatório enviado com sucesso.';
    errorMessage.value = null;
  } catch (error) {
    if (error.code === 'ERR_NETWORK') {
      errorMessage.value = 'Ocorreu um erro ao conectar ao servidor.';
    } else {
      errorMessage.value = error?.response?.data.error;
    }

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
