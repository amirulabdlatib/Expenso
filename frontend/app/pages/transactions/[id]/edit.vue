<template>
    <div class="min-h-screen bg-gray-50 pt-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <EditTransactionHeader />

            <div v-if="isFetchingData" class="flex flex-col items-center justify-center min-h-[400px]">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600" />
                <p class="mt-4 text-gray-600">Loading details...</p>
            </div>

            <div v-else-if="isError" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <Icon name="heroicons:exclamation-circle" class="w-5 h-5 text-red-600 mt-0.5 mr-3 shrink-0" />
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-red-800 mb-1">Error Loading Transaction</h3>
                        <p v-if="errors.statusCode == 403" class="text-sm text-red-700">You are not allowed to edit this transaction details</p>
                        <p v-else class="text-sm text-red-700">{{ errors.statusMessage }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <form class="space-y-6" @submit.prevent="handleUpdate(route.params.id)">
                    <!-- Account -->
                    <div>
                        <label for="account" class="block text-sm font-medium text-gray-700 mb-2"> Account <span class="text-red-500">*</span> </label>
                        <ClientOnly>
                            <VSelect v-model="form.account_id" :options="accounts" :reduce="(account) => account.id" label="name" placeholder="Select an account" class="vue-select-custom" :clearable="true" :disabled="isLoading">
                                <template #no-options>No accounts found</template>
                            </VSelect>
                        </ClientOnly>
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
                            <button type="button" class="relative p-4 border-2 rounded-lg transition-all hover:border-red-500" :class="form.type === 'expense' ? 'border-red-500 bg-red-50' : 'border-gray-200'" @click="form.type = 'expense'">
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
                        </div>
                        <p v-if="errors.type" class="text-red-400">{{ errors.type[0] }}</p>
                    </div>

                    <!-- Categories dropdown -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2"> Category <span class="text-red-500">*</span> </label>

                        <!-- Loading Categories -->
                        <div v-if="isFetchingCategories" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">Loading categories...</div>

                        <!-- Category and Subcategory Selects -->
                        <div v-else class="space-y-4">
                            <!-- Parent Category Select -->
                            <ClientOnly>
                                <VSelect
                                    v-model="selectedParentCategory"
                                    :options="parentCategories"
                                    :reduce="(category) => category"
                                    label="name"
                                    placeholder="Select a category"
                                    class="vue-select-custom"
                                    :clearable="true"
                                    :disabled="isLoading"
                                    @update:model-value="onParentCategoryChange"
                                >
                                    <template #no-options>No categories found</template>
                                </VSelect>
                            </ClientOnly>

                            <!-- Subcategory Select (only shown if parent has children) -->
                            <div v-if="selectedParentCategory && hasSubcategories">
                                <label for="subcategory" class="block text-sm font-medium text-gray-700 mb-2"> Subcategory <span class="text-red-500">*</span> </label>
                                <VSelect
                                    v-model="form.category_id"
                                    :options="availableSubcategories"
                                    :reduce="(category) => category.id"
                                    label="name"
                                    placeholder="Select a subcategory"
                                    class="vue-select-custom"
                                    :clearable="true"
                                    :disabled="isLoading"
                                    :required="true"
                                >
                                    <template #no-options>No subcategories found</template>
                                </VSelect>
                            </div>
                        </div>

                        <p v-if="errors.category_id" class="text-red-400 mt-2">{{ errors.category_id[0] }}</p>
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
                                :max="maxLimit"
                                placeholder="0.00"
                                class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-lg font-medium"
                                :class="{ 'bg-gray-50 cursor-not-allowed opacity-60': isLoading }"
                                :disabled="isLoading"
                                @keydown="if (['-', '+', 'e', 'E'].includes($event.key)) $event.preventDefault();"
                            />
                        </div>
                        <p v-show="showCurrentBalance" class="text-gray-400 font-light">Current Balance: MYR {{ getCurrentAccount?.current_balance }}</p>
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

                    <!-- Receipt Upload/Display -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Receipt (Optional)</label>
                        <FileUpload v-model="receiptData" />
                        <p v-if="errors.receipt_file" class="text-red-400 mt-2">{{ errors.receipt_file[0] }}</p>
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
                            <span v-if="!isLoading">Update </span>
                            <span v-else class="flex items-center space-x-2">
                                <span>Updating...</span>
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
</template>

<script setup>
    import VSelect from "vue-select";

    useHead({
        title: "Edit Transaction - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const now = new Date();
    const isLoading = ref(false);
    const isFetchingData = ref(false);
    const isError = ref(false);
    const accounts = ref([]);
    const categories = ref([]);
    const route = useRoute();
    const { getTransactionForEdit, updateTransaction, loadReceipt, errors } = useTransactions();
    const { success, error: toastError } = useToast();
    const selectedParentCategory = ref(null);
    const isFetchingCategories = ref(false);
    const receiptData = ref(null);

    const form = reactive({
        type: null,
        name: null,
        amount: 0,
        date: now.toISOString().split("T")[0],
        time: now.toTimeString().split(" ")[0].substring(0, 5),
        account_id: null,
        category_id: null,
        description: null,
    });

    onMounted(async () => {
        isFetchingData.value = true;
        try {
            const data = await getTransactionForEdit(route.params.id);
            categories.value = data.categories;
            accounts.value = data.accounts;
            await populateForm(data);
            isError.value = false;
        } catch (err) {
            console.error("Error fetching transaction:", err);
            if (err.statusCode !== 422) {
                errors.value = err;
                isError.value = true;
            }
        } finally {
            isFetchingData.value = false;
        }
    });

    const populateForm = async (data) => {
        const transactionDate = new Date(data.transaction.transaction_date);
        const category = data.transaction.category;

        form.type = category.type;
        form.name = data.transaction.name;
        form.amount = data.transaction.amount;
        form.date = transactionDate.toISOString().split("T")[0];
        form.time = transactionDate.toTimeString().split(" ")[0].substring(0, 5);
        form.account_id = data.transaction.account.id;
        form.description = data.transaction.description;

        const categoryInList = categories.value.find((c) => c.id === category.id);

        if (categoryInList.parent_id) {
            const parentCategory = categories.value.find((c) => c.id === categoryInList.parent_id);
            selectedParentCategory.value = parentCategory;
        } else {
            selectedParentCategory.value = categoryInList;
        }
        form.category_id = categoryInList.id;
        if (data.transaction.receipt) {
            await loadTransactionReceipt(data.transaction.id);
        }
    };

    async function loadTransactionReceipt(transactionId) {
        try {
            const receipt = await loadReceipt(transactionId);
            receipt.isExisting = true;
            receiptData.value = receipt;
        } catch (error) {
            console.error("Failed to load receipt:", error);
        }
    }

    const onParentCategoryChange = (parentCategory) => {
        form.category_id = null;
        if (parentCategory && !hasSubcategories.value) {
            form.category_id = parentCategory.id;
        }
    };

    watch(
        () => form.type,
        (newType, oldType) => {
            if (oldType !== null) {
                selectedParentCategory.value = null;
                form.category_id = null;
            }
        }
    );

    const parentCategories = computed(() => {
        return categories.value.filter((category) => category.type === form.type && !category.parent_id);
    });

    const hasSubcategories = computed(() => {
        return selectedParentCategory.value ? categories.value.some((cat) => cat.parent_id === selectedParentCategory.value.id) : false;
    });

    const availableSubcategories = computed(() => {
        return selectedParentCategory.value ? categories.value.filter((category) => category.parent_id === selectedParentCategory.value.id) : [];
    });

    const getCurrentAccount = computed(() => {
        return accounts.value.find((account) => account.id === form.account_id);
    });

    const showCurrentBalance = computed(() => {
        if (form.type == "expense" && getCurrentAccount.value) {
            return true;
        }
        return false;
    });

    const maxLimit = computed(() => {
        if (form.type === "income") {
            return undefined;
        }
        return getCurrentAccount?.value?.current_balance ? parseFloat(getCurrentAccount?.value.current_balance) + form.amount : 0;
    });

    const handleUpdate = async (id) => {
        if (selectedParentCategory.value && hasSubcategories.value && !form.category_id) {
            alert("Please select a subcategory");
            return;
        }

        isLoading.value = true;
        const transaction_date = `${form.date} ${form.time}:00`;

        const updateFormData = new FormData();

        updateFormData.append("type", form.type);
        updateFormData.append("name", form.name);
        updateFormData.append("amount", form.amount);
        updateFormData.append("transaction_date", transaction_date);
        updateFormData.append("account_id", form.account_id);

        if (form.description) {
            updateFormData.append("description", form.description);
        }

        if (form.category_id) {
            updateFormData.append("category_id", form.category_id);
        }

        // Handle receipt logic
        if (!receiptData.value) {
            // Receipt was removed
            updateFormData.append("remove_receipt", "1");
        } else if (receiptData.value?.file && !receiptData.value.isExisting) {
            // New receipt file uploaded
            updateFormData.append("receipt_file", receiptData.value.file);
        }
        // If receiptData.value exists with isExisting=true, do nothing (keep existing)

        try {
            await updateTransaction(updateFormData, id);
            isError.value = false;
            success("Transaction updated.");
            navigateTo("/transactions");
        } catch (err) {
            toastError("Transaction fail to update.");
            console.log(err);
        } finally {
            isLoading.value = false;
        }
    };
</script>
