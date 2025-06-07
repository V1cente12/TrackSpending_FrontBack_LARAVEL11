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
          <div class="fixed inset-0 bg-black/30" @click="closeModal" />
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
              <!-- Modal Header -->
              <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-4">
                  <button 
                    v-for="period in periods" 
                    :key="period"
                    @click="selectedPeriod = period"
                    :class="[
                      'px-4 py-2 rounded-full text-sm',
                      selectedPeriod === period ? 'bg-blue-500' : 'bg-gray-800'
                    ]"
                  >
                    {{ period }}
                  </button>
                </div>
                <button @click="closeModal" class="text-gray-400">
                  ✕
                </button>
              </div>
  
              <!-- Period Summary Section -->
              <div class="mb-6 p-4 bg-gray-800 rounded-xl">
                <div class="flex justify-between items-center">
                  <div>
                    <p class="text-sm text-gray-400">Total Expenses</p>
                    <p class="text-xl text-red-500">Bs {{ periodTotal.expenses }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-400">Total Income</p>
                    <p class="text-xl text-green-500">Bs {{ periodTotal.income }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-400">Net Balance</p>
                    <p class="text-xl" :class="periodTotal.net >= 0 ? 'text-green-500' : 'text-red-500'">Bs {{ periodTotal.net }}</p>
                  </div>
                </div>
              </div>
  
              <!-- Transactions List -->
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
                    <div class="flex items-start gap-3">
                      <div class="w-10 h-10 flex items-center justify-center bg-gray-700 rounded-full text-xl">
                        {{ transaction.category.icon }}
                      </div>
                      <div>
                        <p class="font-medium">{{ transaction.description }}</p>
                        <p class="text-sm text-gray-400">{{ transaction.category.name }}</p>
                        <p class="text-xs text-gray-500">{{ formatDate(transaction.date) }}</p>
                      </div>
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
  import { ref, watch, computed } from 'vue'
  import { TransitionRoot, TransitionChild } from '@headlessui/vue'
  import axios from 'axios'
  
  const props = defineProps({
    isOpen: Boolean
  })
  
  const emit = defineEmits(['close'])
  
  const periods = ['Day', 'Week', 'Month', 'All']
  const selectedPeriod = ref('Day')
  const transactions = ref([])
  const isLoading = ref(false)
  
  // Compute totals for the selected period
  const periodTotal = computed(() => {
    if (!transactions.value.length) return { expenses: 0, income: 0, net: 0 }
    
    return transactions.value.reduce((acc, transaction) => {
      const amount = parseFloat(transaction.amount)
      if (transaction.type === 'expense') {
        acc.expenses += amount
        acc.net -= amount
      } else {
        acc.income += amount
        acc.net += amount
      }
      return acc
    }, { expenses: 0, income: 0, net: 0 })
  })
  
  const closeModal = () => {
    emit('close')
  }
  
  const fetchTransactions = async () => {
    isLoading.value = true
    try {
      const response = await axios.get(`/transactions/${selectedPeriod.value.toLowerCase()}`)
      transactions.value = response.data.transactions
    } catch (error) {
      console.error('Error fetching transactions:', error)
      transactions.value = []
    } finally {
      isLoading.value = false
    }
  }
  const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}
  
  watch(selectedPeriod, () => {
    fetchTransactions()
  })
  
  watch(() => props.isOpen, (newValue) => {
    if (newValue) {
      fetchTransactions()
    }
  })
  
  const dailyTotal = computed(() => {
    if (!transactions.value.length) return { expenses: 0, income: 0, net: 0 }
    
    return transactions.value.reduce((acc, transaction) => {
      const amount = parseFloat(transaction.amount)
      if (transaction.type === 'expense') {
        acc.expenses += amount
        acc.net -= amount
      } else {
        acc.income += amount
        acc.net += amount
      }
      return acc
    }, { expenses: 0, income: 0, net: 0 })
  })
  </script>