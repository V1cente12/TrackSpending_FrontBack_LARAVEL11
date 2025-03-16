<template>
  <div class="text-white p-4 flex justify-around border-t border-x border-gray-800 rounded-t-2xl bg-gray-800">
    <Link href="/dashboard" class="hover:text-gray-300">Home</Link>
    <button class="hover:text-gray-300">Profile</button>
    <button @click="showReports = true" class="hover:text-gray-300">Reports</button>
    <button class="hover:text-gray-300">Categories</button>
  </div>

  <ReportsModal 
    :is-open="showReports"
    @close="showReports = false"
  />
</template>

<script>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import ReportsModal from './ReportsModal.vue'

export default {
  name: 'NavigationBar',
  components: {
    Link,
    ReportsModal
  },
  setup() {
    const showReports = ref(false)
    
    return {
      showReports
    }
  }
}
</script>