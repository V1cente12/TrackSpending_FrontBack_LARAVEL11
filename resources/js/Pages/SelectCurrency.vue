<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    currencies: Array
});

const form = useForm({
    currency_id: ''
});

const submitForm = () => {
    form.post(route('set-currency'));
};
</script>
<template>
    <AppLayout title="Select Currency">
        <div class="h-full flex flex-col items-center justify-start pt-12 px-4">
            <div class="w-full max-w-md text-center">
                <h2 class="text-2xl font-bold mb-3">Select your currency</h2>
                <p class="text-gray-600 mb-8">Choose the currency you want to use in your wallet</p>

                <div class="mb-8">
                    <select 
                        v-model="form.currency_id"
                        class="w-full text-xl p-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >
                        <option value="">Select a currency</option>
                        <option v-for="currency in currencies" 
                                :key="currency.id" 
                                :value="currency.id">
                            {{ currency.name }} ({{ currency.symbol }})
                        </option>
                    </select>
                </div>

                <button 
                    @click="submitForm"
                    class="w-full bg-blue-500 text-white rounded-lg py-4 font-semibold hover:bg-blue-600">
                    Next
                </button>
            </div>
        </div>
    </AppLayout>
</template>

