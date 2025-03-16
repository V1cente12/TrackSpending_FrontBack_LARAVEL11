<template>
    <TransitionRoot appear :show="isOpen" as="template">
      <div class="fixed inset-0 z-50">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/30" @click="$emit('close')" />
        </TransitionChild>
  
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="translate-y-full"
          enter-to="translate-y-0"
          leave="ease-in duration-200"
          leave-from="translate-y-0"
          leave-to="translate-y-full"
        >
          <div class="fixed inset-x-0 bottom-0 z-50">
            <div class="bg-gray-900 text-gray-100 w-full rounded-t-3xl p-6 min-h-[85vh]">
              <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                  <span class="text-xl">{{ category?.icon }}</span>
                  <span>{{ category?.name }}</span>
                </div>
                <button @click="$emit('close')" class="text-gray-400">
                  ✕
                </button>
              </div>
  
              <div class="space-y-4 h-[calc(85vh-120px)] overflow-y-auto">
                <div v-if="isLoading" class="h-full flex items-center justify-center text-gray-400">
                  Loading transactions...
                </div>
                <div v-else-if="transactions.length === 0" class="h-full flex items-center justify-center text-gray-400">
                  No transactions found
                </div>
                <div v-else class="pt-2">
                  <div v-for="transaction in transactions" 
                       :key="transaction.id"
                       class="flex justify-between items-center p-4 bg-gray-800 rounded-xl mb-3">
                    <div>
                      <p class="font-medium">{{ transaction.description }}</p>
                      <p class="text-sm text-gray-400">{{ transaction.created_at }}</p>
                    </div>
                    <p :class="transaction.type === 'expense' ? 'text-red-500' : 'text-green-500'">
                      {{ transaction.type === 'expense' ? '-' : '+' }} Bs {{ transaction.amount }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </TransitionChild>
      </div>
    </TransitionRoot>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue'
  import { TransitionRoot, TransitionChild } from '@headlessui/vue'
  import axios from 'axios'
  
  const props = defineProps({
    isOpen: Boolean,
    category: Object
  })
  
  const emit = defineEmits(['close'])
  const transactions = ref([])
  const isLoading = ref(false)
  
  const fetchTransactions = async () => {
    if (!props.category) return
    
    isLoading.value = true
    try {
      const response = await axios.get(`/category-transactions/${props.category.id}`)
      transactions.value = response.data.transactions
    } catch (error) {
      console.error('Error fetching transactions:', error)
      transactions.value = []
    } finally {
      isLoading.value = false
    }
  }
  
  watch(() => props.isOpen, (newValue) => {
    if (newValue) {
      fetchTransactions()
    } else {
      transactions.value = []
    }
  })
  </script>