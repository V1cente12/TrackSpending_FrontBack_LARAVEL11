<template>
  <div class="bg-gray-800 p-3 rounded-lg shadow-lg">
    <div class="flex justify-between items-center">
      <h3 class="text-xl font-semibold text-gray-300">Total Balance</h3>
      <button @click="toggleVisibility" class="text-gray-600 hover:text-gray-200">
        <i class="fas" :class="isHidden ? 'fa-eye-slash' : 'fa-eye'"></i>
      </button>
    </div>
    <p class="text-[1.75rem] font-bold text-green-600 mt-2" :class="{ 'blur-sm': isHidden }">
      {{ currencySymbol }} {{ amount }}
    </p>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'

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
  },
  setup() {
    const isHidden = ref(false)
    
    onMounted(() => {
      const savedState = localStorage.getItem('balanceHidden')
      isHidden.value = savedState === 'true'
    })
    
    const toggleVisibility = () => {
      isHidden.value = !isHidden.value
      localStorage.setItem('balanceHidden', isHidden.value)
    }

    return {
      isHidden,
      toggleVisibility
    }
  }
}
</script>
<style scoped>
.blur-sm {
  filter: blur(6px);
}
</style>