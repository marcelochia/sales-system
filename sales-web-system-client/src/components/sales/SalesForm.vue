<template>

  <Loading v-if="isLoading" />

  <div>
    <h1>Adicionar venda para vendedor</h1>
  </div>

  <form class="box" v-on:submit.prevent="save()">
    <div class="field">
      <label class="label">Valor</label>
      <div class="control">
        <input class="input" v-model="sale.value" type="text" placeholder="Valor da compra">
      </div>
    </div>

    <div class="field">
      <label class="label">Data</label>
      <div class="control">
        <input class="input" v-model="sale.date" type="date">
      </div>
    </div>

    <div class="field">
      <label class="label">Vendedor</label>
      <div class="control">
        <div class="select">
          <select v-model="sale.seller_id">
            <option v-for="seller in sellers" :key="seller.id" :value="seller.id">{{ seller.name }}</option>
          </select>
        </div>
      </div>
    </div>

    <div class="field is-grouped">
      <div class="control">
        <button type="submit" class="button is-info" :disabled="!sale.date || !sale.value || !sale.seller_id">Adicionar</button>
      </div>
      <div class="control">
        <button type="button" class="button is-info is-light" @click="goToSalesList">Cancelar</button>
      </div>
    </div>

    <div>
      <p v-if="successMessage" class="has-text-success">{{ successMessage }}</p>
      <p v-if="errorMessage" class="has-text-danger">{{ errorMessage }}</p>
    </div>

  </form>

</template>

<script setup>
import http from '../../services/http.js';
import { formatDateToYYYYMMDD } from "../../helpers/format.js";
import Loading from '../Loading.vue';
import { onMounted, reactive, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

const router = useRouter();
const isLoading = ref(false);
const errorMessage = ref(null);
const successMessage = ref(null);

onMounted(() => {
  getSellers();
});

const sale = reactive({
  value: null,
  date: formatDateToYYYYMMDD(new Date()),
  seller_id: 0
});

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

async function save() {
  isLoading.value = true;
  try {
    await http.post('/sales', sale);

    sale.value = null;
    sale.date = formatDateToYYYYMMDD(new Date());
    sale.seller_id = 0;

    successMessage.value = 'Venda cadastrada com sucesso.'
    errorMessage.value = null;
  } catch (error) {
    successMessage.value = null;
    errorMessage.value = error?.response?.data.error;
  } finally {
    isLoading.value = false;
  }
}

function goToSalesList() {
  router.push({name: 'sales'})
}
</script>


<style>

</style>
