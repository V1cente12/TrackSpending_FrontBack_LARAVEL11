<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import TotalBalanceCard from '@/Components/TotalBalanceCard.vue';
import MonthlySpendingCard from '@/Components/MonthlySpendingCard.vue';
import CategoryChart from '@/Components/CategoryChart.vue';
import AddTransactionButton from '@/Components/AddTransactionButton.vue';
import NavigationBar from '@/Components/NavigationBar.vue';

const props = defineProps({
    currencySymbol: String,
    totalBalance: Number,
    categories: {
        type: Array,
        required: true,
        default: () => []
    }
});

const categories = ref(props.categories);
const totalBalance = ref(props.totalBalance);

const updateDashboard = (data) => {
    categories.value = data.categories;
    totalBalance.value = data.totalBalance;
};
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="h-full flex flex-col items-center justify-start pt-12 px-4">
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
                        class="flex-1"
                    />
                </div>

                <!-- Gráficas de Categorías -->
                <div class="mb-6">
                    <CategoryChart :categories="categories" />
                </div>

                <!-- Botón para añadir nueva transacción -->
                <div class="mb-6">
                    <AddTransactionButton
                        :categories="categories"
                        @transaction-added="updateDashboard" 
                    />
                </div>

                <!-- Barra de navegación inferior -->
                <NavigationBar />
            </div>
        </div>
    </AppLayout>
</template>