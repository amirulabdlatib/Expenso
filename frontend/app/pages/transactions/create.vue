<template>
    <div class="min-h-screen bg-gray-50 pt-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <CreateTransactionHeader />

            <!-- Loading State -->
            <div v-if="isFetchingAccounts" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600" />
                <p class="mt-4 text-gray-600">Loading...</p>
            </div>

            <!-- Main Content -->
            <div v-else>
                <NoAccountsAlert v-if="accounts.length === 0" />

                <!-- Form Card -->
                <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <form class="space-y-6" @submit.prevent="handleSubmit">
                        <!-- Account -->
                        <div>
                            <label for="account" class="block text-sm font-medium text-gray-700 mb-2"> Account <span class="text-red-500">*</span> </label>
                            <VSelect v-model="form.account_id" :options="accounts" :reduce="(account) => account.id" label="name" placeholder="Select an account" class="vue-select-custom" :clearable="true" :disabled="isLoading">
                                <template #no-options>No accounts found</template>
                            </VSelect>
                            <p v-if="errors.account_id" class="text-red-400">{{ errors.account_id[0] }}</p>
                        </div>

                        <!-- Transaction Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2"> Transaction Name <span class="text-red-500">*</span> </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="e.g., Grocery Shopping"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                :class="{ 'bg-gray-50 cursor-not-allowed opacity-60': isLoading }"
                                :disabled="isLoading"
                            />
                            <p v-if="errors.name" class="text-red-400">{{ errors.name[0] }}</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2"> Description (Optional) </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                placeholder="Add any notes about this transaction..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                                :class="{ 'bg-gray-50 cursor-not-allowed opacity-60': isLoading }"
                                :disabled="isLoading"
                            />
                            <p v-if="errors.description" class="text-red-400">{{ errors.description[0] }}</p>
                        </div>

                        <!-- Transaction Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Transaction Type <span class="text-red-500">*</span> </label>
                            <div class="grid grid-cols-3 gap-3">
                                <button
                                    type="button"
                                    class="relative p-4 border-2 rounded-lg transition-all hover:border-red-500"
                                    :class="form.type === 'expense' ? 'border-red-500 bg-red-50' : 'border-gray-200'"
                                    @click="form.type = 'expense'"
                                >
                                    <Icon name="heroicons:arrow-trending-down" class="w-6 h-6 mx-auto mb-2" :class="form.type === 'expense' ? 'text-red-500' : 'text-gray-400'" />
                                    <span class="text-sm font-medium block" :class="form.type === 'expense' ? 'text-red-700' : 'text-gray-700'">Expense</span>
                                </button>

                                <button
                                    type="button"
                                    class="relative p-4 border-2 rounded-lg transition-all hover:border-green-500"
                                    :class="form.type === 'income' ? 'border-green-500 bg-green-50' : 'border-gray-200'"
                                    @click="form.type = 'income'"
                                >
                                    <Icon name="heroicons:arrow-trending-up" class="w-6 h-6 mx-auto mb-2" :class="form.type === 'income' ? 'text-green-500' : 'text-gray-400'" />
                                    <span class="text-sm font-medium block" :class="form.type === 'income' ? 'text-green-700' : 'text-gray-700'">Income</span>
                                </button>

                                <button
                                    type="button"
                                    class="relative p-4 border-2 rounded-lg transition-all"
                                    :class="[form.type === 'transfer' ? 'border-blue-500 bg-blue-50' : 'border-gray-200', accounts.length > 1 && form.account_id ? 'hover:border-blue-500 cursor-pointer' : 'opacity-50 cursor-not-allowed']"
                                    :disabled="accounts.length <= 1 || !form.account_id"
                                    @click="accounts.length > 1 && form.account_id && (form.type = 'transfer')"
                                >
                                    <Icon name="heroicons:arrow-path" class="w-6 h-6 mx-auto mb-2" :class="form.type === 'transfer' ? 'text-blue-500' : 'text-gray-400'" />
                                    <span class="text-sm font-medium block" :class="form.type === 'transfer' ? 'text-blue-700' : 'text-gray-700'">Transfer</span>
                                </button>
                            </div>
                            <p v-if="errors.type" class="text-red-400">{{ errors.type[0] }}</p>
                        </div>

                        <!-- Categories dropdown -->
                        <div v-if="form.type == 'expense' || form.type == 'income'">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2"> Category <span class="text-red-500">*</span> </label>

                            <!-- Loading Categories -->
                            <div v-if="isFetchingCategories" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">Loading categories...</div>

                            <!-- Category Select -->
                            <VSelect
                                v-else
                                v-model="form.category_id"
                                :options="filteredCategories"
                                :reduce="(category) => category.id"
                                label="name"
                                placeholder="Select a category"
                                class="vue-select-custom"
                                :clearable="true"
                                :disabled="isLoading"
                            >
                                <template #no-options>No categories found</template>
                            </VSelect>
                            <p v-if="errors.category_id" class="text-red-400">{{ errors.category_id[0] }}</p>
                        </div>

                        <!-- Related Account (for transfers) -->
                        <div v-if="form.type === 'transfer'">
                            <label for="related_account" class="block text-sm font-medium text-gray-700 mb-2"> To Account <span class="text-red-500">*</span> </label>
                            <select
                                id="related_account"
                                v-model.number="form.related_account_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                :class="{ 'bg-gray-50 cursor-not-allowed opacity-60': isLoading }"
                                :required="form.type === 'transfer'"
                                :disabled="isLoading"
                            >
                                <option value="null" disabled>Select destination account</option>
                                <option v-for="account in transferredAccounts" :key="account.id" :value="account.id">{{ account.name }}</option>
                            </select>
                            <p v-if="errors.related_account_id" class="text-red-400">{{ errors.related_account_id[0] }}</p>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2"> Amount <span class="text-red-500">*</span> </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium text-lg">MYR</span>
                                <input
                                    id="amount"
                                    v-model.number="form.amount"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00"
                                    class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-lg font-medium"
                                    :class="{ 'bg-gray-50 cursor-not-allowed opacity-60': isLoading }"
                                    :disabled="isLoading"
                                    @keydown="if (['-', '+', 'e', 'E'].includes($event.key)) $event.preventDefault();"
                                />
                            </div>
                            <p v-if="errors.amount" class="text-red-400">{{ errors.amount[0] }}</p>
                        </div>

                        <!-- Date and Time -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2"> Date <span class="text-red-500">*</span> </label>
                                <input
                                    id="date"
                                    v-model="form.date"
                                    type="date"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    :class="{ 'bg-gray-50 cursor-not-allowed opacity-60': isLoading }"
                                    :disabled="isLoading"
                                />
                            </div>

                            <div>
                                <label for="time" class="block text-sm font-medium text-gray-700 mb-2"> Time <span class="text-red-500">*</span> </label>
                                <input
                                    id="time"
                                    v-model="form.time"
                                    type="time"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    :class="{ 'bg-gray-50 cursor-not-allowed opacity-60': isLoading }"
                                    :disabled="isLoading"
                                />
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col-reverse md:flex-row md:items-center md:justify-end gap-3 pt-6 border-t border-gray-200">
                            <NuxtLink to="/transactions" class="w-full md:w-auto px-6 py-3 text-center border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium"> Cancel </NuxtLink>
                            <button
                                type="submit"
                                :disabled="isLoading"
                                class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center space-x-2"
                                :class="{ 'bg-gray-50 cursor-not-allowed opacity-60': isLoading }"
                            >
                                <span v-if="!isLoading">Create </span>
                                <span v-else class="flex items-center space-x-2">
                                    <span>Creating...</span>
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import VSelect from "vue-select";

    useHead({
        title: "Create Transaction - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const now = new Date();
    const isFetchingAccounts = ref(false);
    const isFetchingCategories = ref(false);
    const isLoading = ref(false);
    const accounts = ref([]);
    const categories = ref([]);
    const { success, error } = useToast();
    const { getTransactionAccounts, getTransactionCategories, createTransaction, errors } = useTransactions();

    const form = reactive({
        type: null,
        name: "",
        amount: 0,
        date: now.toISOString().split("T")[0],
        time: now.toTimeString().split(" ")[0].substring(0, 5),
        account_id: null,
        category_id: null,
        related_account_id: null,
        description: null,
    });

    const transferredAccounts = computed(() => accounts.value.filter((a) => a.id !== form.account_id));

    const fetchAccounts = async () => {
        isFetchingAccounts.value = true;
        try {
            const response = await getTransactionAccounts();
            accounts.value = response.accounts || [];
        } catch (err) {
            console.error("Error fetching accounts:", err);
        } finally {
            isFetchingAccounts.value = false;
        }
    };

    const fetchCategories = async () => {
        isFetchingCategories.value = true;
        try {
            const response = await getTransactionCategories();
            categories.value = response.categories || [];
        } catch (err) {
            console.error("Error fetching categories:", err);
        } finally {
            isFetchingCategories.value = false;
        }
    };

    watch(
        () => form.type,
        (newType) => {
            if (newType !== "transfer" && newType !== "" && categories.value.length === 0) {
                fetchCategories();
            }
            if (newType === "transfer") {
                form.category_id = null;
            } else {
                form.related_account_id = null;
            }
        }
    );

    const filteredCategories = computed(() => {
        return categories.value.filter((category) => category.type === form.type).sort((a, b) => a.name.localeCompare(b.name));
    });

    onMounted(() => {
        fetchAccounts();
    });

    const handleSubmit = async () => {
        isLoading.value = true;
        const transaction_date = `${form.date} ${form.time}:00`;
        const { date, time, ...formData } = form;
        const data = { ...formData, transaction_date };

        try {
            await createTransaction(data);
            success("Transaction created successfully.");
            navigateTo("/transactions");
        } catch (err) {
            console.log(err);
            error("Transaction fail to create.");
        } finally {
            isLoading.value = false;
        }
    };
</script>

<style scoped>
    :deep(.vue-select-custom .vs__dropdown-toggle) {
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
    }

    :deep(.vue-select-custom .vs__search) {
        outline: none;
    }

    :deep(.vue-select-custom .vs__search:focus) {
        outline: none;
    }

    :deep(.vue-select-custom.vs--open .vs__dropdown-toggle) {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
    }

    :deep(.vue-select-custom .vs__dropdown-menu) {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin-top: 0.25rem;
    }

    :deep(.vue-select-custom .vs__dropdown-option--highlight) {
        background-color: #6366f1;
        color: white;
    }

    :deep(.vue-select-custom .vs__selected) {
        margin: 0;
    }
</style>
