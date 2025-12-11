<template>
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Everything You Need</h2>
                <p class="text-xl text-gray-600">Powerful features to help you manage your finances</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="(feature, index) in features" :key="feature.title" :data-aos="'fade-up'" :data-aos-delay="index * 100" class="group p-6 rounded-xl border border-gray-200 hover:border-indigo-600 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-indigo-600 transition-colors">
                        <Icon :name="feature.icon" class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ feature.title }}</h3>
                    <p class="text-gray-600">{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
    const features = [
        {
            icon: "heroicons:banknotes",
            title: "Multiple Accounts",
            description: "Manage all your bank accounts, credit cards, and cash in one place.",
        },
        {
            icon: "heroicons:chart-bar",
            title: "Smart Analytics",
            description: "Visualize your spending patterns with beautiful charts and insights.",
        },
        {
            icon: "heroicons:clock",
            title: "Budget Tracking",
            description: "Set budgets and get alerts when you're approaching limits.",
        },
        {
            icon: "heroicons:currency-dollar",
            title: "Savings Goals",
            description: "Create tabung for your goals and track progress effortlessly.",
        },
        {
            icon: "heroicons:shield-check",
            title: "Secure & Private",
            description: "Your financial data is encrypted and protected at all times.",
        },
        {
            icon: "heroicons:squares-2x2",
            title: "Categories",
            description: "Organize transactions with customizable categories and tags.",
        },
    ];
</script>
