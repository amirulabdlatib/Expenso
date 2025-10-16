<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">How can we help you?</h1>
                <p class="text-gray-600 text-lg mb-8">Get answers to your questions and learn how to use Expenso</p>

                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto relative">
                    <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search for help articles..."
                        class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-lg shadow-sm" />
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all cursor-pointer">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <Icon name="heroicons:book-open" class="w-6 h-6 text-indigo-600" />
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Getting Started</h3>
                    <p class="text-sm text-gray-600">Learn the basics of Expenso</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all cursor-pointer">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <Icon name="heroicons:play-circle" class="w-6 h-6 text-green-600" />
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Video Tutorials</h3>
                    <p class="text-sm text-gray-600">Watch step-by-step guides</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all cursor-pointer">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <Icon name="heroicons:chat-bubble-left-right" class="w-6 h-6 text-purple-600" />
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Contact Support</h3>
                    <p class="text-sm text-gray-600">Get help from our team</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all cursor-pointer">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <Icon name="heroicons:users" class="w-6 h-6 text-yellow-600" />
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Community</h3>
                    <p class="text-sm text-gray-600">Join our user community</p>
                </div>
            </div>

            <!-- FAQ Categories -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Browse by Category</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <button
                        @click="selectedCategory = 'general'"
                        :class="[selectedCategory === 'general' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 bg-white', 'p-4 rounded-lg border-2 hover:border-indigo-600 transition-all text-left']">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:information-circle" class="w-6 h-6 text-indigo-600" />
                            <div>
                                <h3 class="font-semibold text-gray-900">General</h3>
                                <p class="text-sm text-gray-600">Basic app information</p>
                            </div>
                        </div>
                    </button>

                    <button
                        @click="selectedCategory = 'transactions'"
                        :class="[selectedCategory === 'transactions' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 bg-white', 'p-4 rounded-lg border-2 hover:border-indigo-600 transition-all text-left']">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:arrow-path" class="w-6 h-6 text-indigo-600" />
                            <div>
                                <h3 class="font-semibold text-gray-900">Transactions</h3>
                                <p class="text-sm text-gray-600">Managing your expenses</p>
                            </div>
                        </div>
                    </button>

                    <button
                        @click="selectedCategory = 'budgets'"
                        :class="[selectedCategory === 'budgets' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 bg-white', 'p-4 rounded-lg border-2 hover:border-indigo-600 transition-all text-left']">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:chart-bar" class="w-6 h-6 text-indigo-600" />
                            <div>
                                <h3 class="font-semibold text-gray-900">Budgets & Goals</h3>
                                <p class="text-sm text-gray-600">Financial planning</p>
                            </div>
                        </div>
                    </button>

                    <button
                        @click="selectedCategory = 'accounts'"
                        :class="[selectedCategory === 'accounts' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 bg-white', 'p-4 rounded-lg border-2 hover:border-indigo-600 transition-all text-left']">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:building-library" class="w-6 h-6 text-indigo-600" />
                            <div>
                                <h3 class="font-semibold text-gray-900">Accounts</h3>
                                <p class="text-sm text-gray-600">Managing accounts</p>
                            </div>
                        </div>
                    </button>

                    <button
                        @click="selectedCategory = 'security'"
                        :class="[selectedCategory === 'security' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 bg-white', 'p-4 rounded-lg border-2 hover:border-indigo-600 transition-all text-left']">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:shield-check" class="w-6 h-6 text-indigo-600" />
                            <div>
                                <h3 class="font-semibold text-gray-900">Security</h3>
                                <p class="text-sm text-gray-600">Account security</p>
                            </div>
                        </div>
                    </button>

                    <button
                        @click="selectedCategory = 'troubleshooting'"
                        :class="[selectedCategory === 'troubleshooting' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 bg-white', 'p-4 rounded-lg border-2 hover:border-indigo-600 transition-all text-left']">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:wrench-screwdriver" class="w-6 h-6 text-indigo-600" />
                            <div>
                                <h3 class="font-semibold text-gray-900">Troubleshooting</h3>
                                <p class="text-sm text-gray-600">Fix common issues</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- FAQ Accordion -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h2>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-200">
                    <div v-for="faq in filteredFAQs" :key="faq.id" class="p-6">
                        <button @click="toggleFAQ(faq.id)" class="w-full flex items-center justify-between text-left">
                            <h3 class="text-lg font-semibold text-gray-900">{{ faq.question }}</h3>
                            <Icon :name="openFAQ === faq.id ? 'heroicons:chevron-up' : 'heroicons:chevron-down'" class="w-5 h-5 text-gray-400 flex-shrink-0 ml-4" />
                        </button>
                        <div v-if="openFAQ === faq.id" class="mt-4 text-gray-600 leading-relaxed">
                            <p>{{ faq.answer }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-lg p-8 text-white mb-12">
                <div class="max-w-3xl mx-auto text-center">
                    <Icon name="heroicons:chat-bubble-left-right" class="w-16 h-16 mx-auto mb-4 opacity-90" />
                    <h2 class="text-3xl font-bold mb-4">Still need help?</h2>
                    <p class="text-lg mb-6 opacity-90">Our support team is here to assist you with any questions or issues</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <button class="w-full sm:w-auto px-8 py-3 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition-colors font-semibold flex items-center justify-center space-x-2">
                            <Icon name="heroicons:envelope" class="w-5 h-5" />
                            <span>Email Support</span>
                        </button>
                        <button class="w-full sm:w-auto px-8 py-3 bg-white bg-opacity-20 text-white rounded-lg hover:bg-opacity-30 transition-colors font-semibold flex items-center justify-center space-x-2">
                            <Icon name="heroicons:chat-bubble-left-ellipsis" class="w-5 h-5" />
                            <span>Live Chat</span>
                        </button>
                    </div>
                    <div class="mt-6 flex items-center justify-center space-x-6 text-sm opacity-90">
                        <div class="flex items-center space-x-2">
                            <Icon name="heroicons:clock" class="w-4 h-4" />
                            <span>Average response: 2 hours</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <Icon name="heroicons:star" class="w-4 h-4" />
                            <span>4.9/5 satisfaction rate</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Useful Resources -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Useful Resources</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="#" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all group">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <Icon name="heroicons:document-text" class="w-6 h-6 text-blue-600" />
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">User Guide</h3>
                                <p class="text-sm text-gray-600">Complete guide to using Expenso</p>
                            </div>
                            <Icon name="heroicons:arrow-right" class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" />
                        </div>
                    </a>

                    <a href="#" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all group">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <Icon name="heroicons:academic-cap" class="w-6 h-6 text-green-600" />
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">Financial Tips</h3>
                                <p class="text-sm text-gray-600">Learn better money management</p>
                            </div>
                            <Icon name="heroicons:arrow-right" class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" />
                        </div>
                    </a>

                    <a href="#" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all group">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <Icon name="heroicons:newspaper" class="w-6 h-6 text-purple-600" />
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">Release Notes</h3>
                                <p class="text-sm text-gray-600">What's new in Expenso</p>
                            </div>
                            <Icon name="heroicons:arrow-right" class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" />
                        </div>
                    </a>

                    <a href="#" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all group">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <Icon name="heroicons:code-bracket" class="w-6 h-6 text-yellow-600" />
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">API Documentation</h3>
                                <p class="text-sm text-gray-600">For developers and integrations</p>
                            </div>
                            <Icon name="heroicons:arrow-right" class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" />
                        </div>
                    </a>

                    <a href="#" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all group">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <Icon name="heroicons:megaphone" class="w-6 h-6 text-red-600" />
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">Feature Requests</h3>
                                <p class="text-sm text-gray-600">Suggest new features</p>
                            </div>
                            <Icon name="heroicons:arrow-right" class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" />
                        </div>
                    </a>

                    <a href="#" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all group">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <Icon name="heroicons:bug-ant" class="w-6 h-6 text-indigo-600" />
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">Report a Bug</h3>
                                <p class="text-sm text-gray-600">Help us improve Expenso</p>
                            </div>
                            <Icon name="heroicons:arrow-right" class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" />
                        </div>
                    </a>
                </div>
            </div>

            <!-- Footer Links -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm text-gray-600">Need immediate assistance?</p>
                        <p class="text-sm font-semibold text-gray-900">support@expenso.com</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Privacy Policy</a>
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Terms of Service</a>
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Help and Support - Expenso",
    });

    // State
    const searchQuery = ref("");
    const selectedCategory = ref("general");
    const openFAQ = ref(null);

    // FAQ Data
    const faqs = ref([
        {
            id: 1,
            category: "general",
            question: "What is Expenso?",
            answer: "Expenso is a comprehensive personal finance management application that helps you track expenses, manage budgets, set financial goals, and gain insights into your spending habits. It's designed to make managing your money simple and intuitive.",
        },
        {
            id: 2,
            category: "general",
            question: "Is Expenso free to use?",
            answer: "Expenso offers both free and premium plans. The free plan includes basic expense tracking, budgeting, and reporting features. Premium plans unlock advanced features like unlimited accounts, custom categories, and detailed analytics.",
        },
        {
            id: 3,
            category: "transactions",
            question: "How do I add a transaction?",
            answer: "Click the 'Add Transaction' button on the transactions page or use the quick add button in the navigation bar. Fill in the amount, category, account, and optional notes, then save. You can also edit or delete transactions anytime.",
        },
        {
            id: 4,
            category: "transactions",
            question: "Can I import transactions from my bank?",
            answer: "Yes! Expenso supports importing transactions via CSV files from most banks. Go to Transactions > Import and follow the instructions. We're also working on direct bank integration for automatic syncing.",
        },
        {
            id: 5,
            category: "budgets",
            question: "How do budgets work?",
            answer: "Budgets help you set spending limits for different categories. Create a budget by selecting a category, setting a monthly limit, and tracking your progress. You'll receive alerts when you reach 80% of your budget to help you stay on track.",
        },
        {
            id: 6,
            category: "budgets",
            question: "How do I create a savings goal?",
            answer: "Navigate to Goals > Create Goal. Enter your goal name, target amount, deadline, and optionally set up automatic savings contributions. Track your progress with visual indicators and milestone celebrations.",
        },
        {
            id: 7,
            category: "accounts",
            question: "Can I manage multiple accounts?",
            answer: "Absolutely! You can add unlimited accounts including bank accounts, credit cards, cash, e-wallets, and investment accounts. Each transaction can be assigned to a specific account for accurate tracking.",
        },
        {
            id: 8,
            category: "accounts",
            question: "How do I transfer money between accounts?",
            answer: "Use the 'Transfer Money' option in the quick add menu. Select the source account, destination account, amount, and optional notes. The transfer will update both account balances automatically.",
        },
        {
            id: 9,
            category: "security",
            question: "Is my financial data secure?",
            answer: "Yes! We take security seriously. All data is encrypted in transit and at rest using industry-standard encryption. We also offer two-factor authentication and never store your bank login credentials.",
        },
        {
            id: 10,
            category: "security",
            question: "Can I export my data?",
            answer: "Yes, you can export all your data anytime from Settings > Privacy > Download Your Data. Your data will be provided in CSV format for easy import into other applications or for your personal records.",
        },
        {
            id: 11,
            category: "troubleshooting",
            question: "Why are my charts not showing data?",
            answer: "Charts require at least 2 transactions to display properly. Make sure you have logged some transactions and selected the correct date range. If issues persist, try refreshing the page or clearing your browser cache.",
        },
        {
            id: 12,
            category: "troubleshooting",
            question: "I forgot my password. What should I do?",
            answer: "Click the 'Forgot Password' link on the login page. Enter your email address and we'll send you instructions to reset your password. The reset link is valid for 24 hours.",
        },
    ]);

    // Computed
    const filteredFAQs = computed(() => {
        let filtered = faqs.value.filter((faq) => faq.category === selectedCategory.value);

        if (searchQuery.value) {
            filtered = faqs.value.filter((faq) => faq.question.toLowerCase().includes(searchQuery.value.toLowerCase()) || faq.answer.toLowerCase().includes(searchQuery.value.toLowerCase()));
        }

        return filtered;
    });

    // Methods
    const toggleFAQ = (id) => {
        openFAQ.value = openFAQ.value === id ? null : id;
    };
</script>
