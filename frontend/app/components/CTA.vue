<template>
    <section class="relative py-24 overflow-hidden bg-gray-50">
        <!-- Subtle Pattern Overlay -->
        <div class="absolute inset-0 opacity-[0.03]">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, #1e40af 1px, transparent 0); background-size: 32px 32px"></div>
        </div>

        <!-- Floating Shapes for Visual Interest -->
        <div class="absolute top-10 left-10 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 1s"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up">
            <div class="text-center">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 rounded-full border border-indigo-100 mb-6">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-indigo-700">Join 10,000+ Smart Savers</span>
                </div>

                <!-- Heading -->
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Ready to Take Control of
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600">Your Finances?</span>
                </h2>

                <!-- Subheading -->
                <p class="text-lg sm:text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">Start tracking expenses, building budgets, and achieving your financial goals with Expenso. No credit card required.</p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <NuxtLink
                        to="/register"
                        class="group inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-4 rounded-xl hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl font-semibold text-lg">
                        Get Started Free
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </NuxtLink>
                </div>

                <!-- Trust Indicators -->
                <div class="mt-12 flex flex-wrap justify-center items-center gap-8 text-gray-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium">Free Forever</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium">Bank-Level Security</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium">No Credit Card Required</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
    // Modern, clean CTA component for Expenso financial app
</script>
