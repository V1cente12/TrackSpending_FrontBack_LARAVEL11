<template>
    <AppLayout title="Initial Balance">
        <div class="h-full flex flex-col items-center justify-start pt-12 px-4">
            <div class="w-full max-w-md text-center">
                <!-- Icono de monedas -->
                <div class="mb-8">
                    <div class="bg-cyan-400 rounded-full w-32 h-32 mx-auto flex items-center justify-center">
                        <img src="/images/coins.png" alt="coins" class="w-16 h-16">
                    </div>
                </div>

                <h2 class="text-2xl font-bold mb-3">Set up your cash balance</h2>
                <p class="text-gray-600 mb-8">How much cash do you have in your physical wallet?</p>

                <div class="mb-8 text-4xl font-bold flex justify-center items-center space-x-2" @click="focusInput">
                    <span>{{ form.total_balance || '0' }}</span>
                    <span class="text-gray-400">{{ props.currencySymbol }}</span>
                </div>

                <input 
                    type="number" 
                    v-model="form.total_balance"
                    class="opacity-0 absolute"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    ref="numberInput"
                >

                <button 
                    @click="submitForm"
                    class="w-full bg-blue-500 text-white rounded-lg py-4 font-semibold hover:bg-blue-600">
                    Next
                </button>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const numberInput = ref(null);
const props = defineProps({
    currencySymbol: String
});

const form = useForm({
    total_balance: ''
});

const focusInput = () => {
    numberInput.value.focus();
};

const submitForm = () => {
    form.post(route('set-initial-balance'));
};
</script>