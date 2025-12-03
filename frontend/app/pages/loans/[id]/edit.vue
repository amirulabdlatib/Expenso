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
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Loan Type Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Loan Type</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Borrowed Option -->
                        <button
                            type="button"
                            @click="form.type = 'borrowed'"
                            :class="['relative p-6 border-2 rounded-lg transition-all', form.type === 'borrowed' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300 bg-white']">
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div :class="['w-16 h-16 rounded-full flex items-center justify-center', form.type === 'borrowed' ? 'bg-red-100' : 'bg-gray-100']">
                                    <Icon name="heroicons:arrow-trending-down" :class="['w-8 h-8', form.type === 'borrowed' ? 'text-red-600' : 'text-gray-400']" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">I Borrowed</h3>
                                    <p class="text-sm text-gray-600 mt-1">Money I need to repay</p>
                                </div>
                                <div v-if="form.type === 'borrowed'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                        <Icon name="heroicons:check" class="w-4 h-4 text-white" />
                                    </div>
                                </div>
                            </div>
                        </button>

                        <!-- Lent Option -->
                        <button
                            type="button"
                            @click="form.type = 'lent'"
                            :class="['relative p-6 border-2 rounded-lg transition-all', form.type === 'lent' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300 bg-white']">
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

                    <p v-if="errors.type" class="mt-2 text-sm text-red-600">{{ errors.type }}</p>
                </div>

                <!-- Loan Details Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Loan Details</h2>

                    <div class="space-y-4">
                        <!-- Loan Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Loan Name <span class="text-red-500">*</span> </label>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="e.g., Personal Loan - Bank XYZ, Loan to John"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                :class="{ 'border-red-500': errors.name }" />
                            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                        </div>

                        <!-- Total Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Total Amount <span class="text-red-500">*</span> </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">MYR</span>
                                <MoneyInput v-model="form.initialPayment" :is-loading="isSubmitting" />
                            </div>
                            <p v-if="errors.totalAmount" class="mt-1 text-sm text-red-600">{{ errors.totalAmount }}</p>
                            <p v-else class="mt-1 text-xs text-gray-500">Enter the total loan amount</p>
                        </div>

                        <!-- Initial Payment (Optional) -->
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                Initial Payment (Optional)
                                <LoanInitialAmountTooltip />
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">MYR</span>
                                <input
                                    :value="initialPayment.displayValue.value"
                                    @input="initialPayment.handleInput"
                                    @keydown="initialPayment.handleKeydown"
                                    type="text"
                                    inputmode="numeric"
                                    placeholder="0.00"
                                    class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    :class="{ 'border-red-500': errors.initialPayment }" />
                            </div>
                            <p v-if="errors.initialPayment" class="mt-1 text-sm text-red-600">{{ errors.initialPayment }}</p>
                            <p v-else class="mt-1 text-xs text-gray-500">Any amount already paid or received</p>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Start Date <span class="text-red-500">*</span> </label>
                            <input
                                v-model="form.startDate"
                                type="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                :class="{ 'border-red-500': errors.startDate }" />
                            <p v-if="errors.startDate" class="mt-1 text-sm text-red-600">{{ errors.startDate }}</p>
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
        title: "Edit Loan - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const errors = ref({});
    const isSubmitting = ref(false);

    const { success, error: toastError } = useToast();
    const { createBankingInput } = useBankingInput();

    const form = reactive({
        type: "",
        name: "",
        startDate: new Date().toISOString().split("T")[0],
        description: "",
    });

    // Create banking inputs
    const totalAmount = createBankingInput(0);
    const initialPayment = createBankingInput(0);

    const handleSubmit = async () => {
        isSubmitting.value = true;

        try {
            await new Promise((resolve) => setTimeout(resolve, 1500));

            console.log("Creating loan:", {
                ...form,
                totalAmount: totalAmount.decimalValue.value,
                initialPayment: initialPayment.decimalValue.value,
            });

            success("Loan created successfully!");
        } catch (err) {
            console.error("Error creating loan:", err);
            toastError("Failed to create loan. Please try again.");
        } finally {
            isSubmitting.value = false;
        }
    };
</script>
