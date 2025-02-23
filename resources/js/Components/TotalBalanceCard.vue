<template>
  <div class="bg-white p-3 rounded-lg shadow-lg">
    <h3 class="text-xl font-semibold text-gray-700">Total Balance</h3>
    <p class="text-3xl font-bold text-green-600 mt-2">{{ currencySymbol }} {{ amount }}</p>
  </div>
</template>

<script>
export default {
  name: 'TotalBalanceCard',
  props: {
    amount: {
      type: Number,
      required: true
    },
    currencySymbol: {
      type: String,
      required: true
    }
  }
}
</script>

<style scoped>
/* Puedes añadir estilos específicos si lo deseas */
</style>
