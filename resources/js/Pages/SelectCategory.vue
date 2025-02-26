<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    category: Array,
});

const form = useForm({
    category_id: '',
});

const submit = () => {
    form.post(route('set-category'));
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Select Categories
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium">Please select your categories</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    Choose the categories you want to track your expenses with.
                                </p>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div v-for="cat in category" :key="cat.id" class="flex items-center">
                                    <input
                                        type="checkbox"
                                        :id="'category-' + cat.id"
                                        :value="cat.id"
                                        v-model="form.category_id"
                                        class="mr-2"
                                    >
                                    <label :for="'category-' + cat.id">
                                        {{ cat.name }}
                                    </label>
                                </div>
                            </div>

                            <div v-if="form.errors.category_id" class="text-red-500">
                                {{ form.errors.category_id }}
                            </div>

                            <PrimaryButton :disabled="form.processing">
                                Continue
                            </PrimaryButton>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>