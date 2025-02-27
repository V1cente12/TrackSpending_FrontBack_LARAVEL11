<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    category: Array,
});

const form = useForm({
    category_id: [],
});

const submit = () => {
    form.post(route('set-category'));
};
</script>

<template>
    <AppLayout title="Select Categories">
        <div class="h-full flex flex-col items-center justify-start pt-12 px-4">
            <div class="w-full max-w-md text-center">
                <h2 class="text-2xl font-bold mb-3">Select your categories</h2>
                <p class="text-gray-600 mb-8">Choose the categories you want to track</p>

                <div class="grid grid-cols-3 gap-2 mb-8">
                    <label 
                        v-for="cat in category" 
                        :key="cat.id" 
                        :for="'category-' + cat.id"
                        class="relative cursor-pointer"
                    >
                        <input
                            type="checkbox"
                            :id="'category-' + cat.id"
                            :value="cat.id"
                            v-model="form.category_id"
                            class="sr-only peer"
                        >
                        <div class="p-2 bg-white border-2 rounded-lg transition-all duration-200
                                    peer-checked:border-indigo-500 peer-checked:bg-indigo-50
                                    hover:border-gray-300 group">
                            <div class="flex flex-col items-center justify-center min-h-[60px]">
                                <span class="text-xs font-medium text-gray-900 group-hover:text-indigo-600">
                                    {{ cat.name }}
                                </span>
                                <span class="text-[10px] text-gray-500">
                                    {{ cat.type }}
                                </span>
                            </div>
                            <div class="absolute top-1 right-1 opacity-0 peer-checked:opacity-100 text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </label>
                </div>

                <button 
                    @click="submit"
                    class="w-full bg-blue-500 text-white rounded-lg py-4 font-semibold hover:bg-blue-600"
                    :disabled="form.processing">
                    Next
                </button>
            </div>
        </div>
    </AppLayout>
</template>