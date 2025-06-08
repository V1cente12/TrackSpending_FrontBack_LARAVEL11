<template>
    <div class="relative mb-4">
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Find a transaction..."
        class="w-full bg-gray-800 text-gray-100 rounded-xl px-4 py-2 "
        @input="search"
      />
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  
  const props = defineProps({
    transactions: {
      type: Array,
      required: true
    }
  });
  
  const emit = defineEmits(['search']);
  const searchQuery = ref('');
  
  const search = () => {
  const query = searchQuery.value.toLowerCase();

  const filtered = props.transactions.filter(transaction => {
    const formattedDate = new Date(transaction.date).toLocaleDateString('es-BO', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    }).toLowerCase();

    return (
      transaction.description.toLowerCase().includes(query) ||
      transaction.type.toLowerCase().includes(query) ||
      transaction.category?.name?.toLowerCase().includes(query) ||
      transaction.payment_method?.name?.toLowerCase().includes(query) ||
      transaction.amount.toString().includes(query) ||
      formattedDate.includes(query) // Ahora se puede buscar: "2 de junio de 2025"
    );
  });

  emit('search', filtered);
};

  
  watch(() => props.transactions, () => {
    searchQuery.value = '';
    search();
  });
  </script>