<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import TotalBalanceCard from '@/Components/TotalBalanceCard.vue';
import MonthlySpendingCard from '@/Components/MonthlySpendingCard.vue';
import CategoryChart from '@/Components/CategoryChart.vue';
import AddTransactionButton from '@/Components/AddTransactionButton.vue';
import NavigationBar from '@/Components/NavigationBar.vue';

// Datos ficticios para probar
const totalBalance = 5200.00;
const monthlySpending = 1350.00;
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Welcome Component -->
                    

                    <!-- Tarjetas con saldo total y gasto del mes -->
                    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
                        <TotalBalanceCard :amount="totalBalance" />
                        <MonthlySpendingCard :amount="monthlySpending" />
                    </div>

                    <!-- Gráficas de Categorías -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">
                        <CategoryChart />
                    </div>

                    <!-- Botón para añadir nueva transacción -->
                    <AddTransactionButton />

                    <!-- Barra de navegación inferior -->
                    <NavigationBar />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
