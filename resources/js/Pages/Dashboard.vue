<script setup>
import { ref, nextTick } from 'vue';
//import AppLayout from '@/Layouts/AppLayout.vue';
import TotalBalanceCard from '@/Components/TotalBalanceCard.vue';
import MonthlySpendingCard from '@/Components/MonthlySpendingCard.vue';
import CategoryChart from '@/Components/CategoryChart.vue';
import AddTransactionButton from '@/Components/AddTransactionButton.vue';
import NavigationBar from '@/Components/NavigationBar.vue';

const props = defineProps({
    currencySymbol: String,
    totalBalance: Number,
    monthlySpending: Number,
    paymentMethods: Array,
    categories: {
        type: Array,
        required: true,
        default: () => []
    }
});

const categories = ref(props.categories);
const totalBalance = ref(props.totalBalance);
const monthlySpending = ref(props.monthlySpending);
const chartKey = ref(0);

const updateDashboard = async (data) => {
    categories.value = data.categories;
    totalBalance.value = data.totalBalance;
    monthlySpending.value = data.monthlySpending;
    
    if (data.forceChartUpdate) {
        chartKey.value += 1;
    }
};
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="h-screen flex flex-col">
            <div class="flex-1 overflow-y-auto pb-20">
                <div class="flex flex-col items-center justify-start pt-12 px-4">
                    <div class="w-full max-w-md">
                        <!-- Tarjetas con saldo total y gasto del mes -->
                        <div class="flex flex-row gap-4 mb-6">
                            <TotalBalanceCard 
                                :amount="totalBalance" 
                                :currency-symbol="currencySymbol"
                                class="flex-1"
                            />
                            <MonthlySpendingCard 
                                :amount="monthlySpending" 
                                :currency-symbol="currencySymbol"
                                class="flex-1"
                            />
                        </div>

                        <!-- Gráficas de Categorías -->
                        <div class="mb-6">
                            <CategoryChart 
                                :key="chartKey"
                                :categories="categories" 
                            />
                        </div>

                        <!-- Botón para añadir nueva transacción -->
                        <div class="mb-6">
                            <AddTransactionButton
                                :categories="categories"
                                :payment-methods="paymentMethods"
                                @transaction-added="updateDashboard" 
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra de navegación inferior fija -->
            <div class="fixed bottom-0 w-full bg-transparent">
                <NavigationBar />
            </div>
        </div>
    </AppLayout>
</template>