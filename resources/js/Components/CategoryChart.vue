<template>
   <div class="p-1 text-gray-800 overflow-hidden">
    <!-- Tabs centrados -->
    <div class="flex justify-center gap-4">
      <button 
        v-for="type in ['Expense', 'Income']"
        :key="type"
        @click="selectedType = type.toLowerCase()"
        :class="[
          'px-4 py-1 rounded-full text-sm',
          selectedType === type.toLowerCase() ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-600'
        ]"
      >
        {{ type }}s
      </button>
    </div>

    <div class="overflow-x-auto scrollbar-hide" style="width: 100%; touch-action: auto;">
      <div :style="{ width: containerWidth + 'px', height: '420px', marginLeft: filteredCategories.length === 1 ? '-200px' : '0' }">
        <Bar :data="chartData" :options="chartOptions" :plugins="[labelsPlugin]" />
      </div>
    </div>

    <CategoryTransactionsModal 
      :is-open="isModalOpen"
      :category="selectedCategory"
      @close="toggleModal"
    />
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip } from 'chart.js'
import CategoryTransactionsModal from './CategoryTransactionsModal.vue'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip)

export default {
  name: 'CategoryChart',
  components: { 
    Bar,
    CategoryTransactionsModal
  },
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
    const selectedType = ref('expense')

    const filteredCategories = computed(() => {
      return props.categories.filter(cat => {
        return cat.type === selectedType.value
      })
    })

    const containerWidth = computed(() => {
      return Math.max(filteredCategories.value.length * 95, 200)
    })

    const toggleModal = () => {
      isModalOpen.value = !isModalOpen.value
      if (!isModalOpen.value) {
        selectedCategory.value = null
      }
    }

    const handleBarClick = (categoryId) => {
      selectedCategory.value = props.categories.find(c => c.id === categoryId)
      isModalOpen.value = true
    }

    const labelsPlugin = {
      id: 'labelsInBars',
      afterDraw: (chart) => {
        const ctx = chart.ctx;
        const meta = chart.getDatasetMeta(0);
        const yAxis = chart.scales.y;
        
        meta.data.forEach((bar, index) => {
          const category = filteredCategories.value[index];
          const value = Math.abs(category.total_amount);
          
          ctx.save();
          ctx.fillStyle = '#374151';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'top';
          
          ctx.font = '20px Arial';
          ctx.fillText(category.icon, bar.x, yAxis.bottom + 10);
          
          ctx.font = 'bold 12px Inter';
          ctx.fillText(value, bar.x, yAxis.bottom + 40);
          
          ctx.restore();
        });
      }
    }

    const chartData = computed(() => ({
      labels: Array(filteredCategories.value.length).fill(''),
      datasets: [{
        data: filteredCategories.value.map(cat => {
          const value = Math.abs(cat.total_amount);
          return value === 0 ? 0 : value;
        }),
        backgroundColor: Array(filteredCategories.value.length).fill('#1f2937'), // cambiado a gray-800
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
          barPercentage: 0.2,
          categoryPercentage: 0.5
        },
        y: {
          display: false,
          grid: { display: false },
          min: 0,
          max: Math.max(...filteredCategories.value.map(cat => Math.abs(cat.total_amount || 0))) * 1.2 || 100,
          ticks: {
            beginAtZero: true
          }
        }
      },
      onClick: (event, elements) => {
        if (elements.length > 0) {
          const categoryId = filteredCategories.value[elements[0].index].id
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
      toggleModal,
      selectedType,
      filteredCategories
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