<template>
  <div class="p-2 text-gray-800 overflow-hidden">
    <div class="overflow-x-auto scrollbar-hide" style="width: 100%; touch-action: auto;">
      <div :style="{ width: containerWidth + 'px', height: '350px', marginLeft: categories.length === 1 ? '-200px' : '0' }">
        <Bar :data="chartData" :options="chartOptions" :plugins="[labelsPlugin]" />
      </div>
    </div>

    <!-- Modal de Transacciones -->
    <TransitionRoot appear :show="isModalOpen" as="template">
      <div class="fixed inset-0 z-50">
        <TransitionChild
          as="template"
          enter="ease-out duration-150"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-100"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/30" @click="toggleModal" />
        </TransitionChild>

        <TransitionChild
          as="template"
          enter="ease-out duration-150"
          enter-from="translate-y-full"
          enter-to="translate-y-0"
          leave="ease-in duration-100"
          leave-from="translate-y-0"
          leave-to="translate-y-full"
        >
          <div class="fixed inset-x-0 bottom-0 z-50">
            <div class="bg-gray-900 text-gray-100 w-full rounded-t-3xl p-6">
              <!-- Modal Header -->
              <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                  <span class="text-xl">{{ selectedCategory?.icon }}</span>
                  <span>{{ selectedCategory?.name }}</span>
                </div>
                <button @click="toggleModal" class="text-gray-400">
                  ✕
                </button>
              </div>

              <!-- Lista de Transacciones -->
              <div class="space-y-4 max-h-[60vh] overflow-y-auto">
                <div v-if="isLoading" class="text-center text-gray-400">
                  Loading transactions...
                </div>
                <div v-else-if="transactions.length === 0" class="text-center text-gray-400">
                  No transactions found
                </div>
                <div v-else v-for="transaction in transactions" 
                     :key="transaction.id"
                     class="flex justify-between items-center p-4 bg-gray-800 rounded-xl">
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
        </TransitionChild>
      </div>
    </TransitionRoot>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip } from 'chart.js'
import { TransitionRoot, TransitionChild } from '@headlessui/vue'
import axios from 'axios'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip)

export default {
  name: 'CategoryChart',
  components: { Bar, TransitionRoot, TransitionChild },
  props: {
    categories: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  setup(props) {
    const isModalOpen = ref(false)
    const selectedCategory = ref(null)
    const transactions = ref([])
    const isLoading = ref(false)

    const containerWidth = computed(() => {
      return Math.max(props.categories.length * 95, 200)
    })

    const toggleModal = () => {
      isModalOpen.value = !isModalOpen.value
      if (!isModalOpen.value) {
        transactions.value = []
        selectedCategory.value = null
        isLoading.value = false
      }
    }

    const fetchTransactions = async (categoryId) => {
      isLoading.value = true
      try {
        const response = await axios.get(`/category-transactions/${categoryId}`)
        transactions.value = response.data.transactions
      } catch (error) {
        console.error('Error fetching transactions:', error)
        transactions.value = []
      } finally {
        isLoading.value = false
      }
    }

    const handleBarClick = (categoryId) => {
      selectedCategory.value = props.categories.find(c => c.id === categoryId)
      isModalOpen.value = true
      fetchTransactions(categoryId)
    }

    const labelsPlugin = {
      id: 'labelsInBars',
      afterDraw: (chart) => {
        const ctx = chart.ctx;
        const meta = chart.getDatasetMeta(0);
        const xAxis = chart.scales.x;
        const yAxis = chart.scales.y;
        
        meta.data.forEach((bar, index) => {
          const category = props.categories[index];
          const value = Math.abs(category.total_amount);
          
          ctx.save();
          ctx.fillStyle = '#374151';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'top';
          
          ctx.font = '24px Arial';
          ctx.fillText(category.icon, bar.x, yAxis.bottom + 10);
          
          ctx.font = 'bold 14px Inter';
          ctx.fillText(value, bar.x, yAxis.bottom + 40);
          
          ctx.restore();
        });
      }
    }

    const chartData = computed(() => ({
      labels: Array(props.categories.length).fill(''),
      datasets: [{
        data: props.categories.map(cat => {
          const value = Math.abs(cat.total_amount);
          return value === 0 ? 0 : value;
        }),
        backgroundColor: Array(props.categories.length).fill('#424242'),
        borderSkipped: false,
        borderRadius: 8,
        barThickness: 75,
        maxBarThickness: 75
      }]
    }))

    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      layout: {
        padding: {
          bottom: 50
        }
      },
      animation: {
        duration: 800,
        easing: 'easeOutQuart'
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          enabled: false
        }
      },
      scales: {
      x: {
        grid: { display: false },
        border: { display: false },
        ticks: {
          display: false
        },
        barPercentage: 0.2,    // Reducido de 0.3 a 0.2
        categoryPercentage: 0.5 // Reducido de 0.6 a 0.5
      },
        y: {
          display: false,
          grid: { display: false },
          min: 0,
          max: Math.max(...props.categories.map(cat => Math.abs(cat.total_amount || 0))) * 1 || 100,
          ticks: {
            beginAtZero: true
          }
        }
      },
      onClick: (event, elements) => {
        if (elements.length > 0) {
          const categoryId = props.categories[elements[0].index].id
          handleBarClick(categoryId)
        }
      }
    }

    return {
      chartData,
      chartOptions,
      containerWidth,
      labelsPlugin,
      isModalOpen,
      selectedCategory,
      transactions,
      toggleModal,
      isLoading
    }
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>