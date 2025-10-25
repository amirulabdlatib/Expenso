<template>
    <div class="min-h-screen bg-gray-50 pt-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-3 mb-2">
                    <NuxtLink to="/transactions" class="p-2 rounded-lg hover:bg-white transition-colors">
                        <Icon name="heroicons:arrow-left" class="w-5 h-5 text-gray-600" />
                    </NuxtLink>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Transaction</h1>
                </div>
                <p class="text-gray-600 ml-14">Update your transaction details</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <form class="space-y-6" @submit.prevent="handleSubmit">
                    <!-- Account -->
                    <div>
                        <label for="account" class="block text-sm font-medium text-gray-700 mb-2"> Account <span class="text-red-500">*</span> </label>
                        <select id="account" v-model.number="form.account_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                            <option value="" disabled>Select an account</option>
                            <option value="1">Cash</option>
                            <option value="2">Bank Account</option>
                            <option value="3">Credit Card</option>
                        </select>
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
                            required
                        />
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
                        />
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

                            <button
                                type="button"
                                class="relative p-4 border-2 rounded-lg transition-all hover:border-blue-500"
                                :class="form.type === 'transfer' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'"
                                @click="form.type = 'transfer'"
                            >
                                <Icon name="heroicons:arrow-path" class="w-6 h-6 mx-auto mb-2" :class="form.type === 'transfer' ? 'text-blue-500' : 'text-gray-400'" />
                                <span class="text-sm font-medium block" :class="form.type === 'transfer' ? 'text-blue-700' : 'text-gray-700'">Transfer</span>
                            </button>
                        </div>
                    </div>

                    <!-- Related Account (for transfers) -->
                    <div v-if="form.type === 'transfer'">
                        <label for="related_account" class="block text-sm font-medium text-gray-700 mb-2"> To Account <span class="text-red-500">*</span> </label>
                        <select
                            id="related_account"
                            v-model.number="form.related_account_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            :required="form.type === 'transfer'"
                        >
                            <option value="" disabled>Select destination account</option>
                            <option value="1">Cash</option>
                            <option value="2">Bank Account</option>
                            <option value="3">Credit Card</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2"> </label>
                        <select id="category" v-model.number="form.category_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                            <option value="" disabled>Select a category</option>
                            <option value="1">🍔 Food & Dining</option>
                            <option value="2">🚗 Transportation</option>
                            <option value="3">🏠 Housing</option>
                            <option value="4">🎮 Entertainment</option>
                            <option value="5">💼 Salary</option>
                        </select>
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
                                required
                                @keydown="if (['-', '+', 'e', 'E'].includes($event.key)) $event.preventDefault();"
                            />
                        </div>
                    </div>

                    <!-- Date and Time -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2"> Date <span class="text-red-500">*</span> </label>
                            <input id="date" v-model="form.date" type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required />
                        </div>

                        <div>
                            <label for="time" class="block text-sm font-medium text-gray-700 mb-2"> Time <span class="text-red-500">*</span> </label>
                            <input id="time" v-model="form.time" type="time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col-reverse md:flex-row md:items-center md:justify-end gap-3 pt-6 border-t border-gray-200">
                        <NuxtLink to="/transactions" class="w-full md:w-auto px-6 py-3 text-center border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium"> Cancel </NuxtLink>
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center space-x-2">
                            <span>Create </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Create Transaction - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const now = new Date();
    const form = reactive({
        type: "expense",
        name: "",
        amount: 0,
        date: now.toISOString().split("T")[0], // YYYY-MM-DD
        time: now.toTimeString().split(" ")[0].substring(0, 5), // HH:MM
        account_id: "",
        category_id: "",
        related_account_id: "",
        description: "",
    });

    const handleSubmit = () => {
        const datetime = `${form.date} ${form.time}:00`;
        const { date, time, ...formData } = form;
        const data = { ...formData, datetime };

        console.log("Form submitted:", data);

        // TODO: API request will go here
        // For now, just log the form data
    };
</script>
