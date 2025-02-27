<template>
  <div class="bg-white p-6 rounded-lg shadow-lg text-gray-800 overflow-hidden">
    <div class="overflow-x-auto scrollbar-hide" style="width: 100%; touch-action: auto;">
      <div :style="{ width: containerWidth + 'px', height: '300px', marginLeft: categories.length === 1 ? '-200px' : '0' }">
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
import { computed } from 'vue'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip)

export default {
  name: 'CategoryChart',
  components: { Bar },
  props: {
    categories: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  setup(props) {
    const containerWidth = computed(() => {
      return Math.max(props.categories.length * 80, 200)
    })

    const labelsPlugin = {
      id: 'labelsInBars',
      afterDraw: (chart) => {
        const ctx = chart.ctx;
        const meta = chart.getDatasetMeta(0);
        
        meta.data.forEach((bar, index) => {
          const category = props.categories[index];
          const value = Math.abs(category.total_amount);
          const isZero = value === 0;
          
          ctx.save();
          ctx.fillStyle = 'white';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          
          // Category Name
          ctx.font = '20px Arial';
          const nameY = isZero ? bar.y + 25 : bar.y + bar.height - 35;
          ctx.fillText(category.name, bar.x, nameY);
          
          // Amount Value
          ctx.font = 'bold 15px arial';
          const valueY = isZero ? bar.y + 45 : bar.y + bar.height - 15;
          ctx.fillText(value, bar.x, valueY);
          
          ctx.restore();
        });
      }
    }

    const chartData = computed(() => ({
      labels: props.categories.map(cat => cat.name),
      datasets: [{
        data: props.categories.map(cat => {
          const value = Math.abs(cat.total_amount);
          return value === 0 ? 10 : value;
        }),
        backgroundColor: Array(props.categories.length).fill('#424242'),
        borderSkipped: false,
        borderRadius: 8,
        barThickness: 55,
        maxBarThickness: 55
      }]
    }))

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
          min: 0,
          max: Math.max(...props.categories.map(cat => Math.abs(cat.total_amount || 0))) * 1.2 || 100,
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