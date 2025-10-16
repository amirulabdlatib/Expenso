<template>
    <section id="faq" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600">Everything you need to know about Expenso</p>
            </div>

            <div class="space-y-4" data-aos="fade-up" data-aos-delay="100">
                <!-- FAQ Item 1 -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button @click="toggleFaq(0)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="text-lg font-semibold text-gray-900">Is Expenso really free?</span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-indigo-600 transform transition-transform duration-300"
                            :class="{ 'rotate-180': openIndex === 0 }"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div class="transition-all duration-300 ease-in-out overflow-hidden" :class="openIndex === 0 ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'">
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <p class="text-gray-600">
                                Yes! Expenso is completely free to use. We believe everyone should have access to powerful financial tracking tools. There are no hidden fees, no credit card required, and no premium features locked behind a
                                paywall.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button @click="toggleFaq(1)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="text-lg font-semibold text-gray-900">Is my financial data secure?</span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-indigo-600 transform transition-transform duration-300"
                            :class="{ 'rotate-180': openIndex === 1 }"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div class="transition-all duration-300 ease-in-out overflow-hidden" :class="openIndex === 1 ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'">
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <p class="text-gray-600">
                                Absolutely. We take security very seriously. All your data is encrypted both in transit and at rest. We use industry-standard security protocols and never share your personal information with third parties.
                                Your financial data belongs to you, and we're committed to keeping it safe.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button @click="toggleFaq(2)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="text-lg font-semibold text-gray-900">Can I use Expenso on mobile?</span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-indigo-600 transform transition-transform duration-300"
                            :class="{ 'rotate-180': openIndex === 2 }"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div class="transition-all duration-300 ease-in-out overflow-hidden" :class="openIndex === 2 ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'">
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <p class="text-gray-600">
                                Yes! Expenso is fully responsive and works great on all devices. You can access your account from your phone, tablet, or computer. Your data syncs automatically across all devices, so you can track expenses
                                on the go.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button @click="toggleFaq(5)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="text-lg font-semibold text-gray-900">Do I need to link my bank accounts?</span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-indigo-600 transform transition-transform duration-300"
                            :class="{ 'rotate-180': openIndex === 5 }"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div class="transition-all duration-300 ease-in-out overflow-hidden" :class="openIndex === 5 ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'">
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <p class="text-gray-600">
                                No, you don't have to. Expenso allows you to manually track your accounts without connecting to your bank. You simply create account profiles and add transactions yourself. This gives you full control and
                                privacy over your financial data.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
    const openIndex = ref(null);

    const toggleFaq = (index) => {
        if (openIndex.value === index) {
            openIndex.value = null;
        } else {
            openIndex.value = index;
        }
    };
</script>
