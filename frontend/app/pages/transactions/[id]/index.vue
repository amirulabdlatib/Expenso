<template>
    <div class="min-h-screen bg-gray-50 pt-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-3">
                        <button class="p-2 rounded-lg hover:bg-white transition-colors" @click="goBack">
                            <Icon name="heroicons:arrow-left" class="w-5 h-5 text-gray-600" />
                        </button>
                        <div>
                            <h1 class="text-xl md:text-2xl font-bold text-gray-900">Transaction Details</h1>
                            <p class="text-gray-500 text-sm">View your transaction details</p>
                        </div>
                    </div>
                    <div v-if="transaction" class="flex items-center space-x-2">
                        <NuxtLink v-if="transaction.type != 'transfer'" :to="`/transactions/${route.params.id}/edit`" class="px-4 py-2 p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" @click="refresh">
                            <Icon name="heroicons:pencil-square" />
                        </NuxtLink>
                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete transaction" aria-label="Delete transaction" @click="deleteTransaction(route.params.id)">
                            <Icon name="heroicons:trash" />
                        </button>
                    </div>
                    <div v-else class="flex items-center space-x-2">
                        <button class="text-green-600 hover:bg-green-50 rounded-lg transition-colors" @click="refresh">
                            <Icon name="heroicons:arrow-path" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="pending" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="mt-4 text-gray-600">Loading transaction...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-white rounded-xl shadow-sm border border-red-200 p-8">
                <div class="text-center">
                    <Icon name="heroicons:exclamation-circle" class="w-16 h-16 text-red-500 mx-auto mb-4" />
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Error Loading Transaction</h3>
                    <p class="text-gray-600">{{ error.statusMessage }}</p>
                    <NuxtLink to="/transactions" class="inline-block mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"> Back to Transactions </NuxtLink>
                </div>
            </div>

            <!-- Transaction Details Card -->
            <div v-else-if="transaction" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <!-- Transaction Type Badge -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                            :class="{
                                'bg-red-100 text-red-700': transaction.type === 'expense',
                                'bg-green-100 text-green-700': transaction.type === 'income',
                                'bg-blue-100 text-blue-700': transaction.type === 'transfer',
                            }"
                        >
                            <Icon :name="transaction.type === 'expense' ? 'heroicons:arrow-trending-down' : transaction.type === 'income' ? 'heroicons:arrow-trending-up' : 'heroicons:arrow-path'" class="w-4 h-4 mr-1" />
                            {{ transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1) }}
                        </span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Amount</div>
                        <div
                            class="text-lg font-bold"
                            :class="{
                                'text-red-600': transaction.type === 'expense',
                                'text-green-600': transaction.type === 'income',
                                'text-blue-600': transaction.type === 'transfer',
                            }"
                        >
                            {{ transaction.account.currency }} {{ Number(transaction.amount).toFixed(2) }}
                        </div>
                    </div>
                </div>

                <!-- Transaction Info -->
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Transaction Name</label>
                        <p class="text-lg font-semibold text-gray-900">{{ transaction.name }}</p>
                    </div>

                    <!-- Description -->
                    <div v-if="transaction.description">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                        <p class="text-gray-700">{{ transaction.description }}</p>
                    </div>

                    <!-- Account -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">
                            {{ transaction.type === "transfer" ? "From Account" : "Account" }}
                        </label>
                        <div class="flex items-center space-x-3 p-3 rounded-lg">
                            <Icon :name="transaction.account.icon" />
                            <span class="font-medium text-gray-900">{{ transaction.account.name }}</span>
                        </div>
                    </div>

                    <!-- Related Account (for transfers) -->
                    <div v-if="transaction.type === 'transfer' && transaction.related_account">
                        <label class="block text-sm font-medium text-gray-500 mb-2">To Account</label>
                        <div class="flex items-center space-x-3 p-3 rounded-lg">
                            <Icon :name="transaction.related_account.icon" />
                            <span class="font-medium text-gray-900">{{ transaction.related_account.name }}</span>
                        </div>
                    </div>

                    <!-- Category -->
                    <div v-if="transaction.category">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Category</label>
                        <div class="flex items-center space-x-3 p-3 rounded-lg">
                            <Icon :name="transaction.category.icon" />
                            <div>
                                <div class="font-medium text-gray-900">{{ transaction.category.name }}</div>
                                <div class="text-sm text-gray-500 capitalize">{{ transaction.category.type }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Date and Time -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Date</label>
                            <p class="text-gray-900 font-medium">{{ formatDate(transaction.transaction_date) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Time</label>
                            <p class="text-gray-900 font-medium">{{ formatTime(transaction.transaction_date) }}</p>
                        </div>
                    </div>

                    <!-- Receipt Display -->
                    <div v-if="transaction.receipt">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Receipt</label>
                        <FileUpload v-model="receiptData" :readonly="true" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Transaction Details - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth", "verified"],
    });

    const route = useRoute();
    const client = useSanctumClient();
    const { formatDate, formatTime } = useUtils();
    const { success, error: toastError } = useToast();
    const { deleteTransaction: deleteAction, loadReceipt } = useTransactions();
    const receiptData = ref(null);

    const router = useRouter();

    const goBack = () => {
        router.back();
    };

    const { data: transaction, pending, error, refresh } = await useAsyncData(`transaction-${route.params.id}`, () => client(`/api/transactions/${route.params.id}`));

    // Load receipt if available
    watch(
        transaction,
        async (newTransaction) => {
            if (newTransaction && newTransaction.receipt) {
                try {
                    receiptData.value = await loadReceipt(route.params.id);
                } catch (error) {
                    console.error("Failed to load receipt:", error);
                }
            }
        },
        { immediate: true }
    );

    const deleteTransaction = async (id) => {
        if (confirm("Are you sure you want to delete this transaction?")) {
            try {
                await deleteAction(id);
                success("Transaction deleted!");
                navigateTo("/transactions");
            } catch (err) {
                console.log(err);
                toastError("Transaction fail to delete.");
            }
        }
    };
</script>
