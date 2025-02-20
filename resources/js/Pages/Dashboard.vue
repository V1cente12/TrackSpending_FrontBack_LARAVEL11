<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
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
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Welcome Component -->
                    

                    <!-- Tarjetas con saldo total y gasto del mes -->
                    <div class="bg-gray-200 bg-opacity-25 flex flex-row gap-6 p-6 lg:p-8">
                        <TotalBalanceCard :amount="totalBalance" class="flex-1" />
                        <MonthlySpendingCard :amount="monthlySpending" class="flex-1" />
                    </div>

                    <!-- Gráficas de Categorías -->
                    <div class="flex justify-center items-center p-6">
                        <CategoryChart class="w-full max-w-3xl" />
                    </div>

                    <!-- Botón para añadir nueva transacción -->
                    <div class="flex justify-center items-center p-4">
                        <AddTransactionButton />
                    </div>

                    <!-- Barra de navegación inferior -->
                    <NavigationBar />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
