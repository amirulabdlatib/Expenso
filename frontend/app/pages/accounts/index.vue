<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Accounts</h1>
                        <p class="text-gray-600 mt-2">Manage your financial accounts and balances</p>
                    </div>
                    <NuxtLink to="/accounts/create" class="flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                    </NuxtLink>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="status === 'pending'" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600" />
                <p class="mt-4 text-gray-600">Loading accounts...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <Icon name="heroicons:exclamation-triangle" class="w-12 h-12 text-red-500 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-red-900 mb-2">Error Loading Accounts</h3>
                <p class="text-red-700 mb-4">{{ error.message }}</p>
                <button class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors" @click="refresh">Try Again</button>
            </div>

            <template v-else>
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-lg p-6 border-b-4 border-indigo-600">
                        <div class="flex items-center justify-between mb-4">
                            <Icon name="heroicons:wallet" class="w-8 h-8 text-indigo-600" />
                            <span class="text-sm text-gray-500">Total Balance</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ formatCurrency(accountsData?.totalBalance || 0) }}</p>
                        <p class="text-sm text-gray-500 mt-2">Across {{ totalAccounts }} accounts</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-6 border-b-4 border-green-600">
                        <div class="flex items-center justify-between mb-4">
                            <Icon name="heroicons:arrow-trending-up" class="w-8 h-8 text-green-600" />
                            <span class="text-sm text-gray-500">Active Accounts</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ accountsData?.active_accounts || 0 }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ accountsData?.inactiveAccounts || 0 }} inactive</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-6 border-b-4 border-purple-600">
                        <div class="flex items-center justify-between mb-4">
                            <Icon name="heroicons:credit-card" class="w-8 h-8 text-purple-600" />
                            <span class="text-sm text-gray-500">Active Balance</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ formatCurrency(accountsData?.activeAccountsBalance || 0) }}</p>
                        <p class="text-sm text-gray-500 mt-2">From active accounts</p>
                    </div>
                </div>

                <!-- Filter and Sort -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">All</button>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                                <input type="text" placeholder="Search accounts..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accounts Container -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div v-if="accountsData.accounts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="account in accountsData.accounts" :key="account.id" class="bg-white rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-lg transition-all duration-200 overflow-hidden group">
                            <div class="p-6 bg-gradient-to-br" :class="getAccountColors(account.type).gradient">
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                            <Icon :name="account.icon" class="w-6 h-6 text-white" />
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-base text-white">{{ account.name }}</h3>
                                            <p class="text-sm text-white/80">{{ account.type }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <NuxtLink :to="`/accounts/${account.id}`" class="p-2 text-white/70 hover:text-white hover:bg-white/20 rounded-lg transition-all duration-200">
                                            <Icon name="heroicons:pencil" class="w-4 h-4" />
                                        </NuxtLink>
                                        <button class="p-2 text-white/70 hover:text-red-300 hover:bg-red-500/20 rounded-lg transition-all duration-200" @click="openDeleteModal(account.id)">
                                            <Icon name="heroicons:trash" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-white/80 mb-2 font-medium uppercase tracking-wide">Current Balance</p>
                                    <p class="text-3xl font-bold text-white">{{ formatCurrency(account.balance) }}</p>
                                </div>
                            </div>

                            <!-- Account Details -->
                            <div class="p-6 bg-white">
                                <!-- Quick Stats -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1 font-medium">Currency</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ account.currency || "MYR" }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1 font-medium">Type</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ account.type }}</p>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <span :class="[account.is_active ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-gray-50 text-gray-600 ring-1 ring-gray-600/20', 'px-3 py-1 text-xs font-semibold rounded-full']">
                                        {{ account.is_active ? "Active" : "Inactive" }}
                                    </span>
                                    <NuxtLink :to="`/accounts/${account.id}`" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center space-x-1 group/link">
                                        <span>Manage</span>
                                        <Icon name="heroicons:arrow-right" class="w-4 h-4 group-hover/link:translate-x-0.5 transition-transform" />
                                    </NuxtLink>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="p-6 text-center">
                        <Icon name="heroicons:building-library" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No accounts found</h3>
                        <p class="text-gray-600 mb-6">Add your first account to start tracking your finances</p>
                        <NuxtLink to="/accounts/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                            <Icon name="heroicons:plus" class="w-5 h-5" />
                            <span>Add Account</span>
                        </NuxtLink>
                    </div>
                </div>
            </template>
        </div>

        <DeleteModal :loading="isDeleting" :show="showDeleteModal" @close="closeDeleteModal" @confirm="handleDelete" />
    </div>
</template>

<script setup>
    useHead({
        title: "Accounts - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const { formatCurrency } = useCurrency();
    const { getAccountColors } = useAccountConstants();
    const { deleteAccount, errors } = useAccount();
    const { success, error: errorToast } = useToast();

    const client = useSanctumClient();

    const { data: accountsData, status, error, refresh } = await useAsyncData("accounts", () => client("/api/accounts"));

    const totalAccounts = computed(() => {
        const data = accountsData.value || {};
        return (data.active_accounts || 0) + (data.inactiveAccounts || 0);
    });

    // Delete modal state
    const showDeleteModal = ref(false);
    const accountToDelete = ref(null);
    const isDeleting = ref(false);

    const openDeleteModal = (id) => {
        accountToDelete.value = id;
        showDeleteModal.value = true;
    };

    const closeDeleteModal = () => {
        showDeleteModal.value = false;
        accountToDelete.value = null;
    };

    const handleDelete = async () => {
        isDeleting.value = true;
        try {
            await deleteAccount(accountToDelete.value);
            success("Account deleted successfully.");
            await refresh();
        } catch (err) {
            errorToast("There is some error occurred. Try again later");
            console.log(errors.value);
            console.log(err);
        } finally {
            isDeleting.value = false;
            closeDeleteModal();
        }
    };
</script>
