<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-4">
                    <NuxtLink to="/loans" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <Icon name="heroicons:arrow-left" class="w-5 h-5 text-gray-600" />
                    </NuxtLink>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Create New Loan</h1>
                        <p class="text-gray-600 mt-1">Add a new loan to track your borrowing or lending</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form class="space-y-6" @submit.prevent="handleSubmit">
                <!-- Loan Type Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Loan Type</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Borrowed Option -->
                        <button
                            type="button"
                            :class="['relative p-6 border-2 rounded-lg transition-all', form.type === 'borrow' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300 bg-white']"
                            @click="form.type = 'borrow'">
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div :class="['w-16 h-16 rounded-full flex items-center justify-center', form.type === 'borrow' ? 'bg-red-100' : 'bg-gray-100']">
                                    <Icon name="heroicons:arrow-trending-down" :class="['w-8 h-8', form.type === 'borrow' ? 'text-red-600' : 'text-gray-400']" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">I Borrowed</h3>
                                    <p class="text-sm text-gray-600 mt-1">Money I need to repay</p>
                                </div>
                                <div v-if="form.type === 'borrow'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                        <Icon name="heroicons:check" class="w-4 h-4 text-white" />
                                    </div>
                                </div>
                            </div>
                        </button>

                        <!-- Lent Option -->
                        <button
                            type="button"
                            :class="['relative p-6 border-2 rounded-lg transition-all', form.type === 'lent' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300 bg-white']"
                            @click="form.type = 'lent'">
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div :class="['w-16 h-16 rounded-full flex items-center justify-center', form.type === 'lent' ? 'bg-green-100' : 'bg-gray-100']">
                                    <Icon name="heroicons:arrow-trending-up" :class="['w-8 h-8', form.type === 'lent' ? 'text-green-600' : 'text-gray-400']" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">I Lent</h3>
                                    <p class="text-sm text-gray-600 mt-1">Money I expect to receive back</p>
                                </div>
                                <div v-if="form.type === 'lent'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                        <Icon name="heroicons:check" class="w-4 h-4 text-white" />
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>

                    <p v-if="errors.type" class="mt-2 text-sm text-red-600">{{ errors.type[0] }}</p>
                </div>

                <!-- Loan Details Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Loan Details</h2>

                    <div class="space-y-4">
                        <!-- Account Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Account
                                <span class="text-red-500">*</span>
                            </label>
                            <select v-model="form.account_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Select an account</option>
                                <option v-for="account in filteredAccounts" :key="account.id" :value="account.id">{{ account.name }}</option>
                            </select>
                            <p v-if="errors.account_id" class="mt-1 text-sm text-red-600">{{ errors.account_id[0] }}</p>
                            <p v-else class="mt-1 text-xs text-gray-500">
                                {{ form.type === "borrow" ? "Account where borrowed money will be deposited" : "Account from which you lent the money" }}
                            </p>
                        </div>

                        <!-- Loan Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Loan Name <span class="text-red-500">*</span> </label>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="e.g., Personal Loan - Bank XYZ, Loan to John"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
                        </div>

                        <!-- Total Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Total Amount <span class="text-red-500">*</span> </label>
                            <div class="relative">
                                <MoneyInput v-model="form.total_amount" :is-loading="isSubmitting" :max="maxLoanLimit" />
                            </div>
                            <p v-if="errors.total_amount" class="mt-1 text-sm text-red-600">{{ errors.total_amount[0] }}</p>
                            <p v-else class="mt-1 text-xs text-gray-500">
                                Enter the total loan amount <span v-show="form.type == 'lent'">({{ formatCurrency(displayMaxAmount) }})</span>
                            </p>
                        </div>

                        <!-- Initial Payment (Optional) -->
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                Initial Payment (Optional)
                                <LoanInitialAmountTooltip />
                            </label>
                            <div class="relative">
                                <MoneyInput v-model="form.initial_paid_amount" :is-loading="isSubmitting" />
                            </div>
                            <p v-if="errors.initial_paid_amount" class="mt-1 text-sm text-red-600">{{ errors.initial_paid_amount[0] }}</p>
                            <p v-else class="mt-1 text-xs text-gray-500">Any amount already paid or received</p>
                        </div>

                        <!-- Money Transferred Checkbox -->
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <input id="is_money_transferred" v-model="form.is_money_transferred" type="checkbox" class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                            <div class="flex-1">
                                <label for="is_money_transferred" class="text-sm font-medium text-gray-700 cursor-pointer"> Money has already been transferred </label>
                                <p class="text-xs text-gray-500 mt-1">Check this if the money has already moved in/out of your account. Leave unchecked if the transfer hasn't happened yet.</p>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Start Date <span class="text-red-500">*</span> </label>
                            <input
                                v-model="form.start_date"
                                type="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                :class="{ 'border-red-500': errors.start_date }" />
                            <p v-if="errors.start_date" class="mt-1 text-sm text-red-600">{{ errors.start_date[0] }}</p>
                        </div>

                        <!-- Description/Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Description/Notes </label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                placeholder="Add any additional notes or details about this loan..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"></textarea>
                            <p class="mt-1 text-xs text-gray-500">Optional: Add context or repayment terms</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-4">
                    <NuxtLink to="/loans" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"> Cancel </NuxtLink>
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        :class="['px-6 py-2.5 rounded-lg font-medium transition-colors', isSubmitting ? 'bg-gray-400 text-gray-200 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700']">
                        <span v-if="isSubmitting" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating...
                        </span>
                        <span v-else>Create Loan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Create Loan - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const { errors, createLoan, getLoanAccounts } = useLoan();
    const { success, error: toastError } = useToast();
    const { formatCurrency } = useCurrency();
    const isSubmitting = ref(false);

    const accounts = ref([]);

    onMounted(async () => {
        try {
            const response = await getLoanAccounts();
            accounts.value = response.accounts;
        } catch (err) {
            console.error("Error fetching accounts:", err);
            toastError("Failed to load accounts");
        }
    });

    const form = reactive({
        account_id: "",
        type: "",
        name: "",
        total_amount: 0,
        initial_paid_amount: 0.0,
        is_money_transferred: false,
        start_date: new Date().toISOString().split("T")[0],
        description: "",
    });

    const filteredAccounts = computed(() => {
        if (form.type === "lent") {
            return accounts.value.filter((account) => parseFloat(account.current_balance) > 0);
        } else if (form.type === "borrow") {
            return accounts.value;
        }
        return [];
    });

    const maxLoanLimit = computed(() => {
        if (!form.account_id || form.type !== "lent") {
            return undefined;
        }

        const selectedAccount = filteredAccounts.value.find((acc) => acc.id === form.account_id);

        if (!selectedAccount) {
            return undefined;
        }

        return parseFloat(selectedAccount.current_balance);
    });

    const displayMaxAmount = computed(() => {
        return maxLoanLimit.value ?? 0;
    });

    const handleSubmit = async () => {
        isSubmitting.value = true;

        try {
            await createLoan(form);
            success("Loan created successfully!");
            navigateTo("/loans");
        } catch (err) {
            console.error("Error creating loan:", err);
            toastError("Failed to create loan. Please try again.");
        } finally {
            isSubmitting.value = false;
        }
    };
</script>
