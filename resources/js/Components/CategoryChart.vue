<template>
  <div class="bg-white p-6 rounded-lg shadow-lg text-gray-800">
    <div class="overflow-x-auto">
      <div class="min-w-[750px]">
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
  Legend
} from 'chart.js'
import { ref } from 'vue'

// Registramos los módulos necesarios de Chart.js
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

export default {
  name: 'CategoryChart',
  components: {
    Bar
  },
  setup() {
    // Datos ficticios con emojis en las etiquetas
    const chartData = ref({
      labels: ['👖', '🥑', '🛒', '💎', '💎', '💎','💎', '💎', '💎'],
      datasets: [
        {
          label: 'Expenses',
          backgroundColor: ['#FF3B30', '#FF9500', '#FFD60A', '#32D74B', '#32D74B', '#32D74B','#32D74B', '#32D74B', '#32D74B'],
          borderSkipped: false,  // Esto permite que se muestren todos los bordes
          borderRadius: 10,
          barPercentage: 1,
          data: [500, 435, 350, 50, 350, 350,50, 350, 350]
        }
      ]
    })

    const chartOptions = ref({
      responsive: true,
      maintainAspectRatio: true,
      aspectRatio: 2,  // Añadido para controlar la altura
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function (tooltipItem) {
              return `Bs ${tooltipItem.raw}`
            }
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          border: { display: false },
          ticks: {
            font: { size: 20 },
            color: '#1F2937'
          },
          min: 0,
          maxBarThickness: 120,
          barPercentage: 1,      // Máximo ancho posible
          categoryPercentage: 1   // Sin espacio entre categorías
        },
        y: {
          display: false
        }
      }
    })

    return { chartData, chartOptions }
  }
}
</script>

<style scoped>
.overflow-x-auto {
  scrollbar-width: none; /* Para Firefox */
  -ms-overflow-style: none; /* Para Internet Explorer y Edge */
}

.overflow-x-auto::-webkit-scrollbar {
  display: none; /* Para Chrome, Safari y Opera */
}
</style>
