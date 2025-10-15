<template>
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                            <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                            <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
                        </svg>
                        <span class="text-xl font-bold text-indigo-600">Expenso</span>
                    </div>
                    <p class="text-gray-600 text-sm">Smart expense tracking made simple. Take control of your finances today.</p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Product</h3>
                    <ul class="space-y-2 text-sm">
                        <li><NuxtLink to="/#features" class="text-gray-600 hover:text-indigo-600 transition-colors">Features</NuxtLink></li>
                        <li><NuxtLink to="/#testimonials" class="text-gray-600 hover:text-indigo-600 transition-colors">Testimonials</NuxtLink></li>
                        <li><a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">Security</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Company</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">About</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">Blog</a></li>
                        <li><NuxtLink to="/#contact" class="text-gray-600 hover:text-indigo-600 transition-colors">Contact</NuxtLink></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Legal</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">Privacy</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">Terms</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">License</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-8 text-center text-sm text-gray-600">
                <p>&copy; 2025 Expenso. All rights reserved. Licensed under MIT.</p>
            </div>
        </div>
    </footer>
</template>
