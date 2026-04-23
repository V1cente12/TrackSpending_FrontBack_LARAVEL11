<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
            <div class="flex items-center justify-center mt-4">
                <span class="text-sm text-gray-600">Don't have an account?</span>
                <Link 
                    :href="route('register')" 
                    class="ml-2 text-sm text-blue-600 hover:text-blue-800 font-semibold"
                >
                    Register here
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>