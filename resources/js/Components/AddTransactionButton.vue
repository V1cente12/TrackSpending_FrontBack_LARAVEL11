<script setup>
import { ref } from 'vue';
import { TransitionRoot, TransitionChild } from '@headlessui/vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
  paymentMethods: {
    type: Array,
    required: true
  },
  categories: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['transactionAdded']);
const isModalOpen = ref(false);
const selectedCategory = ref(null);
const selectedPaymentMethod = ref(null);
const transactionType = ref('expense');
const description = ref('');
const amount = ref('');

const form = useForm({
  description: '',
  amount: '',
  category_id: '',
  payment_method_id: '',
  type: 'expense'
});

const toggleModal = () => {
  isModalOpen.value = !isModalOpen.value;
  if (!isModalOpen.value) {
    resetForm();
  }
};

const resetForm = () => {
  form.reset();
  description.value = '';
  amount.value = '';
  selectedCategory.value = null;
  selectedPaymentMethod.value = null;
  transactionType.value = 'expense';
};

const selectCategory = (category) => {
  selectedCategory.value = category.id;
  form.category_id = category.id;
};

const selectPaymentMethod = (method) => {
  selectedPaymentMethod.value = method.id;
  form.payment_method_id = method.id;
};

const handleAmountInput = (event) => {
  let value = event.target.value.replace(',', '.');
  if (!isNaN(value) && value !== '') {
    amount.value = value;
  }
};

const submitTransaction = async () => {
  if (!selectedCategory.value) {
    alert('Please select a category');
    return;
  }

  if (!selectedPaymentMethod.value) {
    alert('Please select a payment method');
    return;
  }

  form.description = description.value;
  form.amount = amount.value;
  form.type = transactionType.value;

  try {
    const response = await axios.post('/transactions', form.data());
    if (response.data.success) {
      emit('transactionAdded', {
        categories: response.data.categories,
        totalBalance: response.data.totalBalance,
        monthlySpending: response.data.monthlySpending,
        forceChartUpdate: true
      });
      resetForm();
      toggleModal();
    }
  } catch (error) {
    console.error('Error saving transaction:', error);
  }
};
</script>

<template>
  <div>
    <!-- Button -->
    <div class="p-4 flex justify-center">
      <button @click="toggleModal" 
              class="flex items-center justify-center gap-2 bg-gray-800 text-gray-300 px-4 py-2.5 rounded-xl shadow-lg hover:bg-gray-700 transition-all duration-200 font-medium w-48">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Add new transaction
      </button>
    </div>

    <!-- Modal -->
    <TransitionRoot appear :show="isModalOpen" as="template">
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
          <div class="fixed inset-0 bg-black/30" @click="toggleModal" />
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
            <div class="bg-gray-900 text-gray-100 w-full rounded-t-3xl p-6">
              <!-- Modal Header -->
              <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                  <span>Today</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
                <button @click="toggleModal" class="text-gray-400">
                  ✕
                </button>
              </div>

              <!-- Transaction Form -->
              <form @submit.prevent="submitTransaction" class="space-y-6">
                <!-- Description Input -->
                <input
                  v-model="description"
                  type="text"
                  placeholder="Description"
                  class="w-full bg-transparent border-0 border-b border-gray-700 focus:border-gray-500 focus:ring-0 text-lg pb-2"
                />

                <!-- Amount Input -->
                <input
                  :value="amount"
                  @input="handleAmountInput"
                  type="text"
                  inputmode="decimal"
                  placeholder="Amount"
                  pattern="[0-9]*[.,]?[0-9]*"
                  class="w-full bg-transparent border-0 border-b border-gray-700 focus:border-gray-500 focus:ring-0 text-lg pb-2"
                />

                <!-- Categories -->
                <div class="space-y-2">
                  <p class="text-sm text-gray-400">Categories</p>
                  <div class="flex gap-3 overflow-x-auto py-2 no-scrollbar">
                    <button
                      type="button"
                      v-for="category in categories"
                      :key="category.id"
                      @click="selectCategory(category)"
                      :class="[
                        'flex items-center gap-1.5 px-4 py-2 rounded-full text-sm whitespace-nowrap',
                        selectedCategory === category.id ? 'bg-blue-500' : 'bg-gray-800'
                      ]"
                    >
                      <span class="text-xl">{{ category.icon }}</span>
                      <span>{{ category.name }}</span>
                    </button>
                  </div>
                </div>

                <!-- Payment Methods -->
                <div class="space-y-2">
                  <p class="text-sm text-gray-400">Payment Method</p>
                  <div class="flex gap-3 overflow-x-auto py-2 no-scrollbar">
                    <button
                      type="button"
                      v-for="method in paymentMethods"
                      :key="method.id"
                      @click="selectPaymentMethod(method)"
                      :class="[
                        'flex items-center gap-1.5 px-4 py-2 rounded-full text-sm whitespace-nowrap',
                        selectedPaymentMethod === method.id ? 'bg-blue-500' : 'bg-gray-800'
                      ]"
                    >
                      <span class="text-xl">{{ method.icon }}</span>
                      <span>{{ method.name }}</span>
                    </button>
                  </div>
                </div>

                <!-- Type Selector -->
                <div class="flex gap-2 mt-4">
                  <button 
                    type="button"
                    @click="transactionType = 'expense'"
                    class="flex-1 py-3 rounded-xl"
                    :class="transactionType === 'expense' ? 'bg-red-500' : 'bg-gray-800'"
                  >
                    -
                  </button>
                  <button 
                    type="button"
                    @click="transactionType = 'income'"
                    class="flex-1 py-3 rounded-xl"
                    :class="transactionType === 'income' ? 'bg-green-500' : 'bg-gray-800'"
                  >
                    +
                  </button>
                </div>

                <!-- Submit Button -->
                <button
                  type="submit"
                  class="w-full bg-blue-500 text-white py-3 rounded-xl hover:bg-blue-600 transition-colors"
                >
                  Save Transaction
                </button>
              </form>
            </div>
          </div>
        </TransitionChild>
      </div>
    </TransitionRoot>
  </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>