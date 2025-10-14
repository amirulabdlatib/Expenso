<template>
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <NuxtLink to="/" class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                        <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                        <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
                    </svg>
                    <span class="text-2xl font-bold text-indigo-600">Expenso</span>
                </NuxtLink>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-indigo-600 transition-colors">Features</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-indigo-600 transition-colors">How It Works</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-indigo-600 transition-colors">Testimonials</a>
                    <a href="#contact" class="text-gray-600 hover:text-indigo-600 transition-colors">Contact Us</a>
                </div>

                <div class="flex items-center space-x-4">
                    <NuxtLink to="/login" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium"> Login </NuxtLink>
                    <NuxtLink to="/register" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105 font-medium"> Get Started </NuxtLink>
                </div>
            </div>
        </div>
    </nav>
</template>
