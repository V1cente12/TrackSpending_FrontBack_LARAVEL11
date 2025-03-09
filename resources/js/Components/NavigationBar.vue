<template>
  <div class="bg-gray-800 text-white p-4 flex justify-around mb-4 mx-4 rounded-lg shadow-lg">
    <Link href="/dashboard" class="hover:text-gray-300">Home</Link>
    <button class="hover:text-gray-300">Profile</button>
    <button class="hover:text-gray-300">Reports</button>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
  name: 'NavigationBar',
  components: {
    Link
  }
}
</script>

<style scoped>
/* Estilos personalizados para la barra de navegación si lo deseas */
</style>