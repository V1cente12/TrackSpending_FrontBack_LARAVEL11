<template>
    <TransitionRoot appear :show="isOpen" as="template">
      <div class="fixed inset-0 z-50">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/30" @click="closeModal" />
        </TransitionChild>
  
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="translate-y-full"
          enter-to="translate-y-0"
          leave="ease-in duration-200"
          leave-from="translate-y-0"
          leave-to="translate-y-full"
        >
          <div class="fixed inset-x-0 bottom-0 z-50">
            <div class="bg-gray-900 text-gray-100 w-full rounded-t-3xl p-6 min-h-[85vh]">
              <!-- Modal Header -->
              <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Mi Perfil</h2>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              
              <!-- User Profile Section -->
              <div class="flex items-center p-4 bg-gray-800 rounded-lg mb-6">
                <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-4">
                  <img class="h-16 w-16 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                </div>
                <div>
                  <div class="font-medium text-xl text-gray-100">
                    {{ $page.props.auth.user.name }}
                  </div>
                  <div class="font-medium text-sm text-gray-400">
                    {{ $page.props.auth.user.email }}
                  </div>
                </div>
              </div>
              
              <!-- Profile Options -->
              <div class="space-y-4">
                <Link :href="route('profile.show')" class="flex items-center p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  <span>Editar Perfil</span>
                </Link>
                
                <div v-if="$page.props.jetstream.hasApiFeatures">
                  <Link :href="route('api-tokens.index')" class="flex items-center p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <span>API Tokens</span>
                  </Link>
                </div>
                
                <form @submit.prevent="logout">
                  <button type="submit" class="w-full flex items-center p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Cerrar Sesión</span>
                  </button>
                </form>
              </div>

            </div>
          </div>
        </TransitionChild>
      </div>
    </TransitionRoot>
  </template>
  
  <script setup>
import { TransitionRoot, TransitionChild } from '@headlessui/vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
  isOpen: Boolean
})
const emit = defineEmits(['close'])

const logout = () => {
  router.post(route('logout'));
};

const closeModal = () => {
  emit('close')
}
  </script>