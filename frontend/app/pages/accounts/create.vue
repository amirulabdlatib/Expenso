<template>
    <div class="min-h-screen bg-gray-50 p-4 md:p-6">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center space-x-4 mb-4">
                    <NuxtLink to="/accounts" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <Icon name="heroicons:arrow-left" class="w-5 h-5 text-gray-600" />
                    </NuxtLink>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Create New Account</h1>
                        <p class="text-gray-600 text-sm md:text-base mt-1">Add a new financial account to track your money</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6">
                <form class="space-y-6" @submit.prevent="handleSubmit">
                    <!-- Account Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2"> Account Name <span class="text-red-500">*</span> </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="e.g., Maybank Savings, CIMB Credit Card"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            :disabled="isLoading"
                        />
                        <p v-if="errors.name" class="text-red-400">{{ errors.name[0] }}</p>
                    </div>

                    <!-- Account Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3"> Account Type <span class="text-red-500">*</span> </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <button
                                v-for="type in accountTypes"
                                :key="type.value"
                                type="button"
                                :disabled="isLoading"
                                :class="[
                                    'flex flex-col items-center justify-center p-4 rounded-lg border-2 transition-all',
                                    form.type === type.value ? 'border-indigo-500 bg-indigo-50 shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50',
                                    isLoading ? 'opacity-50 cursor-not-allowed' : '',
                                ]"
                                @click="form.type = type.value"
                            >
                                <Icon :name="type.icon" class="w-8 h-8 mb-2" :class="form.type === type.value ? 'text-indigo-600' : 'text-gray-600'" />
                                <span class="text-xs md:text-sm font-medium text-center" :class="form.type === type.value ? 'text-indigo-900' : 'text-gray-700'">
                                    {{ type.label }}
                                </span>
                            </button>
                        </div>
                        <p v-if="errors.type" class="text-red-400 mt-2">{{ errors.type[0] }}</p>
                    </div>

                    <!-- Icon Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3"> Account Icon <span class="text-red-500">*</span> </label>
                        <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                            <button
                                v-for="iconOption in accountIcons"
                                :key="iconOption"
                                type="button"
                                :disabled="isLoading"
                                :class="[
                                    'flex items-center justify-center p-3 md:p-4 rounded-lg border-2 transition-all',
                                    form.icon === iconOption ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50',
                                    isLoading ? 'opacity-50 cursor-not-allowed' : '',
                                ]"
                                @click="form.icon = iconOption"
                            >
                                <Icon :name="iconOption" class="w-6 h-6" :class="form.icon === iconOption ? 'text-indigo-600' : 'text-gray-600'" />
                            </button>
                        </div>
                        <p v-if="errors.icon" class="text-red-400">{{ errors.icon[0] }}</p>
                    </div>

                    <!-- Initial Balance -->
                    <div>
                        <label for="balance" class="block text-sm font-medium text-gray-700 mb-2"> Initial Balance <span class="text-red-500">*</span> </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">
                                {{ form.currency }}
                            </span>
                            <input
                                id="balance"
                                v-model="form.balance"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                :disabled="isLoading"
                            />
                        </div>
                        <p v-if="errors.balance" class="text-red-400">{{ errors.balance[0] }}</p>
                    </div>

                    <!-- Currency -->
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2"> Currency <span class="text-red-500">*</span> </label>
                        <select id="currency" v-model="form.currency" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" :disabled="isLoading">
                            <option v-for="curr in currencies" :key="curr.code" :value="curr.code">{{ curr.code }} - {{ curr.name }}</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <div class="relative">
                                <input v-model="form.is_active" type="checkbox" class="sr-only peer" :disabled="isLoading" />
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 transition-colors" :class="isLoading ? 'opacity-50' : ''" />
                                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5" />
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Account Active</span>
                                <p class="text-xs text-gray-500">Include this account in your financial overview</p>
                            </div>
                        </label>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col-reverse md:flex-row md:items-center md:justify-end gap-3 pt-6 border-t border-gray-200">
                        <NuxtLink
                            to="/accounts"
                            class="w-full md:w-auto px-6 py-3 text-center border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium"
                            :class="isLoading ? 'pointer-events-none opacity-50' : ''"
                        >
                            Cancel
                        </NuxtLink>
                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="!isLoading" class="flex items-center space-x-2">
                                <span>Create Account</span>
                            </span>
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
</template>

<script setup>
    useHead({
        title: "Create Account - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const { accountTypes, accountIcons, currencies } = useAccountConstants();
    const { createAccount, errors } = useAccount();
    const { success, error } = useToast();
    const accountsStore = useAccountsStore();

    const isLoading = ref(false);

    const form = reactive({
        name: "",
        type: "",
        icon: "",
        balance: 0,
        currency: "MYR",
        is_active: true,
    });

    const handleSubmit = async () => {
        isLoading.value = true;

        try {
            await createAccount(form);
            await accountsStore.getActiveAccountsCount();
            success("Account created successfully.");
            navigateTo("/accounts");
        } catch (err) {
            error("Account creation failed");
            console.log("Account creation failed:", err);
        } finally {
            isLoading.value = false;
        }
    };
</script>
