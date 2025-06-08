<template>
    <div class="relative mb-4">
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Find a transaction..."
        class="w-full bg-gray-800 text-gray-100 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
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
    const filtered = props.transactions.filter(transaction => 
      transaction.description.toLowerCase().includes(query)
    );
    emit('search', filtered);
  };
  
  watch(() => props.transactions, () => {
    searchQuery.value = '';
    search();
  });
  </script>