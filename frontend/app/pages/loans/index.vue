<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Loans</h1>
                        <p class="text-gray-600 mt-2">Manage your borrowed and lent money</p>
                    </div>
                    <NuxtLink to="/loans/create" class="flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                        <span>Add Loan</span>
                    </NuxtLink>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Borrowed -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Borrowed</p>
                            <p class="text-2xl font-bold text-red-600 mt-2">$8,500.00</p>
                            <p class="text-xs text-gray-500 mt-1">Outstanding balance</p>
                        </div>
                        <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                            <Icon name="heroicons:arrow-trending-down" class="w-6 h-6 text-red-600" />
                        </div>
                    </div>
                </div>

                <!-- Total Lent -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Lent</p>
                            <p class="text-2xl font-bold text-green-600 mt-2">$3,200.00</p>
                            <p class="text-xs text-gray-500 mt-1">Outstanding balance</p>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                            <Icon name="heroicons:arrow-trending-up" class="w-6 h-6 text-green-600" />
                        </div>
                    </div>
                </div>

                <!-- Net Position -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Net Position</p>
                            <p class="text-2xl font-bold text-gray-900 mt-2">-$5,300.00</p>
                            <p class="text-xs text-gray-500 mt-1">Borrowed - Lent</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center">
                            <Icon name="heroicons:banknotes" class="w-6 h-6 text-gray-600" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="flex border-b border-gray-200">
                    <button
                        :class="[activeTab === 'all' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700', 'px-6 py-4 border-b-2 font-medium text-sm transition-colors']"
                        @click="activeTab = 'all'">
                        All
                        <span class="ml-2 px-2 py-1 text-xs rounded-full" :class="activeTab === 'all' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                            {{ loans?.length || 0 }}
                        </span>
                    </button>
                    <button
                        :class="[activeTab === 'borrowed' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700', 'px-6 py-4 border-b-2 font-medium text-sm transition-colors']"
                        @click="activeTab = 'borrowed'">
                        Borrowed
                        <span class="ml-2 px-2 py-1 text-xs rounded-full" :class="activeTab === 'borrowed' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                            {{ borrowedLoans.length }}
                        </span>
                    </button>
                    <button
                        :class="[activeTab === 'lent' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700', 'px-6 py-4 border-b-2 font-medium text-sm transition-colors']"
                        @click="activeTab = 'lent'">
                        Lent
                        <span class="ml-2 px-2 py-1 text-xs rounded-full" :class="activeTab === 'lent' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                            {{ lentLoans.length }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Searching -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-1 relative">
                        <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search loans..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            @input="handleSearch" />
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading || isSearching" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <div class="flex flex-col items-center justify-center space-y-4 py-8">
                    <div class="relative">
                        <div class="w-16 h-16 border-4 border-indigo-100 rounded-full"></div>
                        <div class="w-16 h-16 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ isSearching ? "Searching loans..." : "Loading loans..." }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ isSearching ? "Finding what you're looking for..." : "Please wait a moment" }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="hasError" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <Icon name="heroicons:exclamation-triangle" class="w-12 h-12 text-red-500 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-red-900 mb-2">Error loading loans</h3>
                <p class="text-red-600 mb-4">Something went wrong while loading your loans</p>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" @click="loadLoans">Try Again</button>
            </div>

            <!-- Loans Grid -->
            <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div v-for="loan in displayedLoans" :key="loan.id" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-3 flex-1">
                            <!-- Icon -->
                            <div :class="['w-12 h-12 rounded-lg flex items-center justify-center shrink-0', loan.type === 'borrowed' ? 'bg-red-50' : 'bg-green-50']">
                                <Icon :name="loan.type === 'borrowed' ? 'heroicons:arrow-trending-down' : 'heroicons:arrow-trending-up'" :class="['w-6 h-6', loan.type === 'borrowed' ? 'text-red-600' : 'text-green-600']" />
                            </div>

                            <!-- Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <h3 class="font-semibold text-gray-900">{{ loan.name }}</h3>
                                    <span :class="['inline-flex items-center px-2 py-1 text-xs font-medium rounded-full', loan.type === 'borrowed' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700']">
                                        {{ loan.type === "borrowed" ? "Borrowed" : "Lent" }}
                                    </span>
                                </div>

                                <!-- Amount Details -->
                                <div class="grid grid-cols-3 gap-4 mt-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Total</p>
                                        <p class="text-sm font-medium text-gray-900 mt-0.5">${{ loan.totalAmount.toLocaleString() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Paid</p>
                                        <p class="text-sm font-medium text-gray-900 mt-0.5">${{ loan.totalPaid.toLocaleString() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Balance</p>
                                        <p :class="['text-sm font-medium mt-0.5', loan.type === 'borrowed' ? 'text-red-600' : 'text-green-600']">${{ loan.balance.toLocaleString() }}</p>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-4">
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                        <span>Progress</span>
                                        <span class="font-medium">{{ loan.progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div :class="['h-2 rounded-full transition-all', loan.type === 'borrowed' ? 'bg-red-600' : 'bg-green-600']" :style="{ width: `${loan.progress}%` }" />
                                    </div>
                                </div>

                                <!-- Meta Info -->
                                <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                    <div class="flex items-center">
                                        <Icon name="heroicons:calendar" class="w-4 h-4 mr-1" />
                                        {{ loan.startDate }}
                                    </div>
                                    <div class="flex items-center">
                                        <Icon name="heroicons:document-text" class="w-4 h-4 mr-1" />
                                        {{ loan.paymentCount }} payments
                                    </div>
                                    <div v-if="loan.lastPaymentDate" class="flex items-center">
                                        <Icon name="heroicons:clock" class="w-4 h-4 mr-1" />
                                        Last: {{ loan.lastPaymentDate }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-1 ml-2">
                            <NuxtLink :to="`/loans/${loan.id}`" class="p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors">
                                <Icon name="heroicons:eye" class="w-4 h-4" />
                            </NuxtLink>
                            <button class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" @click="handleDelete(loan.id)">
                                <Icon name="heroicons:trash" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="!isLoading && displayedLoans.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <template v-if="searchQuery">
                    <Icon name="heroicons:magnifying-glass" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No results found</h3>
                    <p class="text-gray-600 mb-6">We couldn't find any loans matching "{{ searchQuery }}"</p>
                    <button class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors" @click="clearSearch">
                        <Icon name="heroicons:x-mark" class="w-5 h-5" />
                        <span>Clear search</span>
                    </button>
                </template>
                <template v-else>
                    <Icon name="heroicons:banknotes" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No loans found</h3>
                    <p class="text-gray-600 mb-6">Get started by creating your first loan</p>
                    <NuxtLink to="/loans/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                        <span>Create Loan</span>
                    </NuxtLink>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Loans - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const activeTab = ref("all");
    const { success, error: toastError } = useToast();
    const route = useRoute();
    const router = useRouter();

    const searchQuery = ref("");
    const isSearching = ref(false);
    const isLoading = ref(false);
    const hasError = ref(false);

    if (route.query.search) {
        searchQuery.value = route.query.search;
    }

    // Hardcoded data
    const loans = ref([
        {
            id: 1,
            type: "borrowed",
            name: "Personal Loan - Bank XYZ",
            totalAmount: 5000,
            totalPaid: 2000,
            balance: 3000,
            progress: 40,
            startDate: "Jan 15, 2024",
            lastPaymentDate: "Nov 15, 2024",
            paymentCount: 8,
        },
        {
            id: 2,
            type: "borrowed",
            name: "Car Loan",
            totalAmount: 4000,
            totalPaid: 1500,
            balance: 2500,
            progress: 37.5,
            startDate: "Mar 10, 2024",
            lastPaymentDate: "Nov 10, 2024",
            paymentCount: 5,
        },
        {
            id: 3,
            type: "borrowed",
            name: "Emergency Loan - Friend",
            totalAmount: 3500,
            totalPaid: 500,
            balance: 3000,
            progress: 14.3,
            startDate: "Oct 5, 2024",
            lastPaymentDate: "Nov 5, 2024",
            paymentCount: 1,
        },
        {
            id: 4,
            type: "lent",
            name: "Loan to John Doe",
            totalAmount: 2000,
            totalPaid: 800,
            balance: 1200,
            progress: 40,
            startDate: "Aug 20, 2024",
            lastPaymentDate: "Nov 20, 2024",
            paymentCount: 4,
        },
        {
            id: 5,
            type: "lent",
            name: "Business Loan - Sarah",
            totalAmount: 3000,
            totalPaid: 1000,
            balance: 2000,
            progress: 33.3,
            startDate: "Sep 1, 2024",
            lastPaymentDate: "Nov 1, 2024",
            paymentCount: 3,
        },
    ]);

    const borrowedLoans = computed(() => {
        return loans.value.filter((loan) => loan.type === "borrowed");
    });

    const lentLoans = computed(() => {
        return loans.value.filter((loan) => loan.type === "lent");
    });

    const displayedLoans = computed(() => {
        let filtered = loans.value;

        // Filter by tab
        if (activeTab.value === "borrowed") {
            filtered = borrowedLoans.value;
        } else if (activeTab.value === "lent") {
            filtered = lentLoans.value;
        }

        // Filter by search query
        if (searchQuery.value) {
            const query = searchQuery.value.toLowerCase();
            filtered = filtered.filter((loan) => loan.name.toLowerCase().includes(query));
        }

        return filtered;
    });

    const handleSearch = async () => {
        isSearching.value = true;
        const query = { ...route.query };
        if (searchQuery.value) {
            query.search = searchQuery.value;
        } else {
            delete query.search;
        }
        await router.replace({ query });

        // Simulate search delay
        setTimeout(() => {
            isSearching.value = false;
        }, 300);
    };

    const clearSearch = () => {
        searchQuery.value = "";
        handleSearch();
    };

    const loadLoans = () => {
        isLoading.value = true;
        hasError.value = false;

        // Simulate API call
        setTimeout(() => {
            isLoading.value = false;
        }, 1000);
    };

    const handleDelete = async (id) => {
        if (!confirm("Are you sure you want to delete this loan?")) {
            return;
        }

        try {
            // Simulate deletion
            const index = loans.value.findIndex((loan) => loan.id === id);
            if (index !== -1) {
                loans.value.splice(index, 1);
                success("Loan deleted successfully.");
            }
        } catch (err) {
            toastError("Failed to delete loan.");
        }
    };
</script>
