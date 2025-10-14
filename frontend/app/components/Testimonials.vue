<template>
    <section id="testimonials" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Testimonials</h2>
                <p class="text-xl text-gray-600">Join thousands of satisfied users managing their finances better</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Expenso has completely transformed how I manage my finances. I can finally see where my money goes and save for my goals!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white font-semibold mr-4">AS</div>
                        <div>
                            <p class="font-semibold text-gray-900">Ahmad Syafiq</p>
                            <p class="text-sm text-gray-600">Software Engineer</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"The budget tracking feature is amazing! I've reduced my spending by 30% in just two months. Highly recommend!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold mr-4">SL</div>
                        <div>
                            <p class="font-semibold text-gray-900">Sarah Lee</p>
                            <p class="text-sm text-gray-600">Small Business Owner</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Clean interface, easy to use, and the tabung feature helped me save for my dream vacation. Worth every penny... oh wait, it's free!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-semibold mr-4">RK</div>
                        <div>
                            <p class="font-semibold text-gray-900">Raj Kumar</p>
                            <p class="text-sm text-gray-600">Graphic Designer</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="grid md:grid-cols-4 gap-8 mt-16 pt-16 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-4xl font-bold text-indigo-600 mb-2">10K+</p>
                    <p class="text-gray-600">Active Users</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-indigo-600 mb-2">RM 50M+</p>
                    <p class="text-gray-600">Tracked Expenses</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-indigo-600 mb-2">25K+</p>
                    <p class="text-gray-600">Savings Goals</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-indigo-600 mb-2">4.9/5</p>
                    <p class="text-gray-600">User Rating</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
    // Testimonials component
</script>
