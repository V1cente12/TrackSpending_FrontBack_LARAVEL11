<template>
  <div class="bg-white p-6 rounded-lg shadow-lg text-gray-800 overflow-hidden">
    <div class="overflow-x-auto scrollbar-hide" style="width: 100%; touch-action: auto;">
      <div :style="{ width: containerWidth + 'px', height: '300px' }">
        <Bar :data="chartData" :options="chartOptions" />
      </div>
    </div>
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
} from 'chart.js'
import { computed, ref } from 'vue'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip)

export default {
  name: 'CategoryChart',
  components: { Bar },
  setup() {
    const data = [200, 350, 180, 420,200, 350, 180, 420]
    const categories = ["📱", "🍔", "🚗", "🏠","📱", "🍔", "🚗", "🏠"]
    const colors = ["#FF4F4F", "#FFA500", "#FFD700", "#00C853","#FF4F4F", "#FFA500", "#FFD700", "#00C853"]

    const containerWidth = computed(() => {
      return Math.max(data.length * 100, 200)
    })

    const chartData = {
      labels: categories,
      datasets: [{
        data: data,
        backgroundColor: colors,
        borderSkipped: false,  // Importante para que se muestren todos los bordes
        borderRadius: 8,
        barPercentage: 0.9,
        categoryPercentage: 0.8,
        barThickness: 55,
        maxBarThickness: 55
      }]
    }

    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      animation: {
        duration: 800,
        easing: 'easeOutQuart'
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: (context) => `Bs ${context.raw}`
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          border: { display: false },
          ticks: {
            font: { size: 20 }
          }
        },
        y: {
          display: false,
          grid: { display: false },
          max: 500,
          min: 0
        }
      },
      layout: {
        padding: 0
      }
    }

    return {
      chartData,
      chartOptions,
      containerWidth
    }
  }
}
</script>

<style>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>