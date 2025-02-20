<template>
  <div class="bg-white p-3 rounded-lg shadow-lg">
    <h3 class="text-xl font-semibold text-gray-700">Monthly</h3>
    <p class="text-3xl font-bold text-red-600 mt-2">Bs {{ amount }}</p>
  </div>
</template>

<script>
export default {
  name: 'MonthlySpendingCard',
  props: {
    amount: {
      type: Number,
      required: true
    }
  }
}
</script>

<style scoped>
/* Estilos específicos si lo necesitas */
</style>
