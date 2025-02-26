<template>
  <div class="bg-white p-6 rounded-lg shadow-lg text-gray-800 overflow-hidden">
    <div class="overflow-x-auto scrollbar-hide" style="width: 100%; touch-action: auto;">
      <div :style="{ width: containerWidth + 'px', height: '300px', marginLeft: data.length === 1 ? '-200px' : '0' }">
        <Bar :data="chartData" :options="chartOptions" :plugins="[labelsPlugin]" />
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
    const data = [1500, 1000, 500]
    const categories = ["🚌", "👖", "🌮"]
    const colors = ['#424242', '#424242', '#424242']

    const containerWidth = computed(() => {
      return Math.max(data.length * 80, 200)
    })

    const labelsPlugin = {
      id: 'labelsInBars',
      afterDraw: (chart) => {
        const ctx = chart.ctx;
        const meta = chart.getDatasetMeta(0);
        
        meta.data.forEach((bar, index) => {
          const value = data[index];
          const label = categories[index];
          const isZero = value === 0;
          
          ctx.save();
          ctx.fillStyle = 'white';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          
          // Emoji
          ctx.font = '20px Arial';
          const emojiY = isZero ? bar.y + 25 : bar.y + bar.height - 35;
          ctx.fillText(label, bar.x, emojiY);
          
          // Valor (incluyendo cero)
          ctx.font = 'bold 15px arial';
          const valueY = isZero ? bar.y + 45 : bar.y + bar.height - 15;
          ctx.fillText(value, bar.x, valueY);
          
          ctx.restore();
        });
      }
    }

    const chartData = {
      labels: categories,
      datasets: [{
        data: data.map(v => v === 0 ? 80 : v),
        backgroundColor: colors,
        borderSkipped: false,
        borderRadius: 8,
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
          barPercentage: 0.4,
          categoryPercentage: 0.8
        },
        y: {
          display: false,
          grid: { display: false },
          max: 2000,
          min: 0,
          ticks: {
            beginAtZero: true
          }
        }
      }
    }

    return {
      chartData,
      chartOptions,
      containerWidth,
      data,
      labelsPlugin
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