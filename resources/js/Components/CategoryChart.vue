<template>
  <div class="bg-black p-6 rounded-lg shadow-lg text-white">
    <h3 class="text-xl font-semibold mb-4 text-center">Expenses</h3>
    <Bar :data="chartData" :options="chartOptions" />
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
      labels: ['👖', '🥑', '🛒', '💎'], // Emojis en lugar de texto
      datasets: [
        {
          label: 'Expenses',
          backgroundColor: ['#FF3B30', '#FF9500', '#FFD60A', '#32D74B'],
          borderRadius: 10, // Bordes redondeados
          barPercentage: 0.5, // Tamaño de las barras
          data: [500, 435, 350, 50]
        }
      ]
    })

    const chartOptions = ref({
      responsive: true,
      plugins: {
        legend: { display: false }, // Ocultamos la leyenda
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
          grid: { display: false }, // Ocultar líneas del eje X
          ticks: {
            font: { size: 20 }, // Tamaño de los emojis
            color: '#FFFFFF'
          }
        },
        y: {
          display: false // Ocultar el eje Y para diseño más limpio
        }
      }
    })

    return { chartData, chartOptions }
  }
}
</script>

<style scoped>
/* Si necesitas agregar más estilos personalizados */
</style>
