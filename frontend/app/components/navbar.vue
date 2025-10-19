<template>
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <NuxtLink to="/" class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                        <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                        <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
                    </svg>
                    <span class="text-2xl font-bold text-indigo-600">Expenso</span>
                </NuxtLink>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <NuxtLink to="/#features" class="text-gray-600 hover:text-indigo-600 transition-colors cursor-pointer">Features</NuxtLink>
                    <NuxtLink to="/#how-it-works" class="text-gray-600 hover:text-indigo-600 transition-colors cursor-pointer">How It Works</NuxtLink>
                    <NuxtLink to="/#testimonials" class="text-gray-600 hover:text-indigo-600 transition-colors cursor-pointer">Testimonials</NuxtLink>
                    <NuxtLink to="/#contact" class="text-gray-600 hover:text-indigo-600 transition-colors cursor-pointer">Contact Us</NuxtLink>
                </div>

                <!-- Desktop Auth Buttons -->
                <div v-if="!isAuthenticated" class="hidden md:flex items-center space-x-4">
                    <NuxtLink to="/login" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium"> Login </NuxtLink>
                    <NuxtLink to="/register" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105 font-medium"> Get Started </NuxtLink>
                </div>

                <div v-else class="hidden md:flex items-center space-x-4">
                    <NuxtLink to="/dashboard" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105 font-medium"> Dashboard </NuxtLink>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" @click="isOpen = !isOpen">
                    <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" x2="20" y1="12" y2="12" />
                        <line x1="4" x2="20" y1="6" y2="6" />
                        <line x1="4" x2="20" y1="18" y2="18" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="isOpen" class="md:hidden border-t border-gray-200 bg-white">
            <div class="px-4 py-4 space-y-3">
                <NuxtLink to="/#features" class="block px-4 py-2 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors cursor-pointer" @click="isOpen = false"> Features </NuxtLink>
                <NuxtLink to="/#how-it-works" class="block px-4 py-2 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors cursor-pointer" @click="isOpen = false"> How It Works </NuxtLink>
                <NuxtLink to="/#testimonials" class="block px-4 py-2 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors cursor-pointer" @click="isOpen = false"> Testimonials </NuxtLink>
                <NuxtLink to="/#contact" class="block px-4 py-2 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors cursor-pointer" @click="isOpen = false"> Contact Us </NuxtLink>

                <div v-if="!isAuthenticated" class="pt-4 border-t border-gray-200 space-y-2">
                    <NuxtLink to="/login" class="block w-full text-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors font-medium" @click="isOpen = false"> Login </NuxtLink>
                    <NuxtLink to="/register" class="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium" @click="isOpen = false"> Get Started </NuxtLink>
                </div>

                <div v-else class="pt-4 border-t border-gray-200 space-y-2">
                    <NuxtLink to="/dashboard" class="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium" @click="isOpen = false"> Dashboard </NuxtLink>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
    const isOpen = ref(false);

    const { isAuthenticated } = useSanctumAuth();
</script>
