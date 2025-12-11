<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <NuxtLink to="/loans" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <Icon name="heroicons:arrow-left" class="w-5 h-5 text-gray-600" />
                        </NuxtLink>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ loan?.name }}</h1>
                            <p class="text-gray-600 mt-1">Loan Details & Payment History</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button @click="showAddPaymentModal = true" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            <Icon name="heroicons:plus" class="w-4 h-4 inline mr-1" />
                            Add Payment
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                            <Icon name="heroicons:ellipsis-vertical" class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <div class="flex flex-col items-center justify-center space-y-4 py-8">
                    <div class="relative">
                        <div class="w-16 h-16 border-4 border-indigo-100 rounded-full"></div>
                        <div class="w-16 h-16 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div>
                    </div>
                    <p class="text-gray-600">Loading loan details...</p>
                </div>
            </div>

            <!-- Main Content -->
            <div v-else class="space-y-6">
                <!-- Loan Overview Card -->
                <div :class="['rounded-lg shadow-sm border-2 p-6', loan?.type === 'borrowed' ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200']">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div :class="['w-12 h-12 rounded-lg flex items-center justify-center', loan?.type === 'borrowed' ? 'bg-red-100' : 'bg-green-100']">
                                <Icon :name="loan?.type === 'borrowed' ? 'heroicons:arrow-trending-down' : 'heroicons:arrow-trending-up'" :class="['w-6 h-6', loan?.type === 'borrowed' ? 'text-red-600' : 'text-green-600']" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Loan Type</p>
                                <p class="text-lg font-semibold capitalize text-gray-900">{{ loan?.type }}</p>
                            </div>
                        </div>
                        <span :class="['px-3 py-1 rounded-full text-sm font-medium', loan?.type === 'borrowed' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700']">
                            {{ loan?.type === "borrowed" ? "I Borrowed" : "I Lent" }}
                        </span>
                    </div>

                    <!-- Amount Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                            <p class="text-xl font-medium text-gray-900">MYR {{ formatCurrency(loan?.total_amount) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Paid</p>
                            <p class="text-xl font-medium text-gray-900">MYR {{ formatCurrency(totalPaid) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Balance</p>
                            <p :class="['text-xl font-medium', loan?.type === 'borrowed' ? 'text-red-600' : 'text-green-600']">MYR {{ formatCurrency(balance) }}</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div>
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>Progress</span>
                            <span class="font-medium">{{ progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div :class="['h-3 rounded-full transition-all', loan?.type === 'borrowed' ? 'bg-red-600' : 'bg-green-600']" :style="{ width: `${progress}%` }"></div>
                        </div>
                    </div>

                    <!-- Loan Info -->
                    <div class="mt-6 pt-6 border-t border-gray-300 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Start Date</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ formatDate(loan?.start_date) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Initial Payment</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">MYR {{ formatCurrency(loan?.initial_paid_amount) }}</p>
                        </div>
                        <div v-if="loan?.description" class="md:col-span-2">
                            <p class="text-xs text-gray-500">Description</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ loan?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment History Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Payment History</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ payments?.length || 0 }} payment(s) recorded</p>
                    </div>

                    <!-- Payments List -->
                    <div v-if="payments && payments.length > 0" class="divide-y divide-gray-200">
                        <div v-for="payment in payments" :key="payment.id" class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div :class="['w-10 h-10 rounded-full flex items-center justify-center', payment.direction === 'in' ? 'bg-green-100' : 'bg-red-100']">
                                        <Icon name="heroicons:banknotes" :class="['w-5 h-5', payment.direction === 'in' ? 'text-green-600' : 'text-red-600']" />
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Payment #{{ payment.id }}</p>
                                        <p class="text-sm text-gray-600">{{ formatDate(payment.date) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p :class="['text-lg font-semibold', payment.direction === 'in' ? 'text-green-600' : 'text-red-600']">{{ payment.direction === "in" ? "+" : "-" }} MYR {{ formatCurrency(payment.amount) }}</p>
                                    <p class="text-xs text-gray-500">Transaction ID: #{{ payment.transaction_id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="p-12 text-center">
                        <Icon name="heroicons:banknotes" class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No payments yet</h3>
                        <p class="text-gray-600 mb-4">Start tracking payments for this loan</p>
                        <button @click="showAddPaymentModal = true" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            <Icon name="heroicons:plus" class="w-4 h-4 inline mr-1" />
                            Add First Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Loan Details - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth", "verified"],
    });

    const route = useRoute();
    const isLoading = ref(true);
    const showAddPaymentModal = ref(false);

    // Hardcoded data for now
    const loan = ref({
        id: 1,
        type: "borrowed",
        name: "Personal Loan - Bank XYZ",
        total_amount: 5000,
        initial_paid_amount: 500,
        start_date: "2024-01-15",
        description: "Emergency personal loan for home renovation",
    });

    const payments = ref([
        {
            id: 1,
            loan_id: 1,
            transaction_id: 101,
            amount: 500,
            direction: "out",
            date: "2024-01-15",
        },
        {
            id: 2,
            loan_id: 1,
            transaction_id: 102,
            amount: 250,
            direction: "out",
            date: "2024-02-15",
        },
        {
            id: 3,
            loan_id: 1,
            transaction_id: 103,
            amount: 250,
            direction: "out",
            date: "2024-03-15",
        },
    ]);

    // Computed values
    const totalPaid = computed(() => {
        const initialPaid = parseFloat(loan.value?.initial_paid_amount || 0);
        const paymentsPaid = payments.value.reduce((sum, payment) => sum + parseFloat(payment.amount), 0);
        return initialPaid + paymentsPaid;
    });

    const balance = computed(() => {
        return parseFloat(loan.value?.total_amount || 0) - totalPaid.value;
    });

    const progress = computed(() => {
        const total = parseFloat(loan.value?.total_amount || 0);
        if (total === 0) return 0;
        return Math.round((totalPaid.value / total) * 100);
    });

    // Format helpers
    const formatCurrency = (value) => {
        return parseFloat(value || 0).toLocaleString("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    };

    const formatDate = (date) => {
        if (!date) return "";
        return new Date(date).toLocaleDateString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    };

    // Simulate loading
    onMounted(() => {
        setTimeout(() => {
            isLoading.value = false;
        }, 500);
    });
</script>
