<template>
    <div>
      <!-- Parte superior con dos tarjetas -->
      <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
        
        <!-- Tarjeta: Saldo Total -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
          <h3 class="text-xl font-semibold text-gray-700">Saldo Total</h3>
          <p class="text-3xl font-bold text-green-600 mt-2">$5,200.00</p>
        </div>
  
        <!-- Tarjeta: Gasto del mes -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
          <h3 class="text-xl font-semibold text-gray-700">Gasto del Mes</h3>
          <p class="text-3xl font-bold text-red-600 mt-2">$1,350.00</p>
        </div>
      </div>
  
      <!-- Gráficas de barras con datos ficticios -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">
          <h3 class="text-xl font-semibold text-gray-700 mb-4">Gastos por Categoría</h3>
          <BarChart :data="chartData" :options="chartOptions" />
        </div>
        
        <!-- Se puede agregar más gráficas aquí si es necesario -->
      </div>
  
      <!-- Botón para añadir nueva transacción -->
      <div class="p-6">
        <button class="bg-blue-500 text-white p-3 rounded-lg shadow-md hover:bg-blue-600">Añadir Nueva Transacción</button>
      </div>
  
      <!-- Barra de navegación inferior -->
      <div class="bg-gray-800 text-white p-4 flex justify-around mb-4 mx-4 rounded-lg shadow-lg">
        <button class="hover:text-gray-300">Inicio</button>
        <button class="hover:text-gray-300">Perfil</button>
        <button class="hover:text-gray-300">Reportes</button>
      </div>
    </div>
  </template>
  
  <script>
  // Importamos las librerías necesarias
  import { Bar } from 'vue-chartjs'
  import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js'
  import { ref, defineComponent } from 'vue'
  
  ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)
  
  export default defineComponent({
    name: 'Control',
    components: {
      BarChart: Bar
    },
    setup() {
      // Datos ficticios para la gráfica
      const chartData = ref({
        labels: ['Comida', 'Transporte', 'Vivienda', 'Entretenimiento', 'Otros'],
        datasets: [
          {
            label: 'Gastos en Categorías',
            backgroundColor: '#4CAF50',
            data: [500, 200, 300, 150, 200]
          }
        ]
      })
  
      const chartOptions = ref({
        responsive: true,
        scales: {
          x: {
            ticks: {
              color: 'rgba(0, 0, 0, 0.7)'
            }
          },
          y: {
            beginAtZero: true,
            ticks: {
              color: 'rgba(0, 0, 0, 0.7)'
            }
          }
        }
      })
  
      return { chartData, chartOptions }
    }
  })
  </script>
  
  <style scoped>
  /* Estilos personalizados */
  </style>
  