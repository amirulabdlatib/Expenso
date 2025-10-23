<template>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-3 mb-3">
                    <NuxtLink to="/categories" class="p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all">
                        <Icon name="heroicons:arrow-left" class="w-5 h-5 text-gray-600" />
                    </NuxtLink>
                    <h1 class="text-3xl font-bold text-gray-900">Create Category</h1>
                </div>
                <p class="text-gray-600 ml-14">Add a new category to organize your expenses and income</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form class="p-6 md:p-8" @submit.prevent="handleSubmit">
                    <div class="space-y-6">
                        <!-- Category Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2"> Category Name <span class="text-red-500">*</span> </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="e.g., Food & Dining, Transportation, Entertainment"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                :disabled="isLoading" />
                            <p v-if="errors.name" class="mt-1 text-sm text-red-500">{{ errors.name[0] }}</p>
                        </div>

                        <!-- Category Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3"> Category Type <span class="text-red-500">*</span> </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button
                                    v-for="type in categoryTypes"
                                    :key="type.value"
                                    type="button"
                                    :disabled="isLoading"
                                    class="relative p-4 border-2 rounded-lg transition-all duration-200 text-left"
                                    :class="[form.type === type.value ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50', isLoading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']"
                                    @click="form.type = type.value">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center" :class="type.bgColor">
                                            <Icon :name="type.icon" class="w-5 h-5" :class="type.iconColor" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900">{{ type.label }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ type.description }}</p>
                                        </div>
                                    </div>
                                    <div v-if="form.type === type.value" class="absolute top-3 right-3 w-5 h-5 bg-indigo-600 rounded-full flex items-center justify-center">
                                        <Icon name="heroicons:check" class="w-3 h-3 text-white" />
                                    </div>
                                </button>
                            </div>
                            <p v-if="errors.type" class="mt-1 text-sm text-red-500">{{ errors.type[0] }}</p>
                        </div>

                        <!-- Category Icon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3"> Choose Icon <span class="text-red-500">*</span> </label>
                            <div class="grid grid-cols-4 md:grid-cols-8 gap-2">
                                <button
                                    v-for="iconOption in categoryIcons"
                                    :key="iconOption"
                                    type="button"
                                    :disabled="isLoading"
                                    class="aspect-square p-3 border-2 rounded-lg transition-all duration-200 hover:scale-105"
                                    :class="[form.icon === iconOption ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50', isLoading ? 'opacity-50 cursor-not-allowed' : '']"
                                    @click="form.icon = iconOption">
                                    <Icon :name="iconOption" class="w-full h-full" :class="form.icon === iconOption ? 'text-indigo-600' : 'text-gray-600'" />
                                </button>
                            </div>
                            <p v-if="errors.icon" class="mt-1 text-sm text-red-500">{{ errors.icon[0] }}</p>
                        </div>

                        <!-- Color Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3"> Color <span class="text-red-500">*</span> </label>
                            <div class="grid grid-cols-8 sm:grid-cols-10 md:grid-cols-12 gap-2">
                                <button
                                    v-for="colorOption in categoryColors"
                                    :key="colorOption.value"
                                    type="button"
                                    :disabled="isLoading"
                                    class="aspect-square rounded-lg transition-all duration-200 hover:scale-110 relative"
                                    :class="[colorOption.class, isLoading ? 'opacity-50 cursor-not-allowed' : '']"
                                    @click="form.color = colorOption.value">
                                    <div v-if="form.color === colorOption.value" class="absolute inset-0 flex items-center justify-center">
                                        <Icon name="heroicons:check" class="w-5 h-5 text-white drop-shadow-lg" />
                                    </div>
                                </button>
                            </div>
                            <p v-if="errors.color" class="mt-1 text-sm text-red-500">{{ errors.color[0] }}</p>
                        </div>
                    </div>
                    <!-- Form Actions -->
                    <div class="flex flex-col-reverse md:flex-row md:items-center md:justify-end gap-3 pt-6 mt-8 border-t border-gray-200">
                        <NuxtLink
                            to="/categories"
                            class="w-full md:w-auto px-6 py-3 text-center border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium"
                            :class="isLoading ? 'pointer-events-none opacity-50' : ''">
                            Cancel
                        </NuxtLink>
                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span v-if="!isLoading" class="flex items-center space-x-2">
                                <span>Create</span>
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

            <!-- Tips Card -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <Icon name="heroicons:light-bulb" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900 mb-1">Tips for Creating Categories</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Keep category names clear and descriptive</li>
                            <li>• Choose icons and colors that help you quickly identify categories</li>
                            <li>• Set budget limits for expense categories to track spending</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Create Category - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const { createCategory, errors } = useCategory();
    const { success, error } = useToast();

    const isLoading = ref(false);

    const form = reactive({
        name: "",
        type: "",
        icon: "",
        color: "",
    });

    const categoryTypes = [
        {
            value: "expense",
            label: "Expense",
            description: "Money going out",
            icon: "heroicons:arrow-trending-down",
            iconColor: "text-red-600",
            bgColor: "bg-red-100",
        },
        {
            value: "income",
            label: "Income",
            description: "Money coming in",
            icon: "heroicons:arrow-trending-up",
            iconColor: "text-green-600",
            bgColor: "bg-green-100",
        },
    ];

    const categoryIcons = [
        "heroicons:shopping-cart",
        "heroicons:shopping-bag",
        "heroicons:currency-dollar",
        "heroicons:home",
        "heroicons:building-office",
        "heroicons:banknotes",
        "heroicons:credit-card",
        "heroicons:gift",
        "heroicons:heart",
        "heroicons:film",
        "heroicons:musical-note",
        "heroicons:tv",
        "heroicons:device-phone-mobile",
        "heroicons:computer-desktop",
        "heroicons:wifi",
        "heroicons:bolt",
        "heroicons:light-bulb",
        "heroicons:wrench-screwdriver",
        "heroicons:truck",
        "heroicons:rocket-launch",
    ];

    const categoryColors = [
        { value: "red", class: "bg-red-500" },
        { value: "orange", class: "bg-orange-500" },
        { value: "amber", class: "bg-amber-500" },
        { value: "yellow", class: "bg-yellow-500" },
        { value: "lime", class: "bg-lime-500" },
        { value: "green", class: "bg-green-500" },
        { value: "emerald", class: "bg-emerald-500" },
        { value: "teal", class: "bg-teal-500" },
        { value: "cyan", class: "bg-cyan-500" },
        { value: "sky", class: "bg-sky-500" },
        { value: "blue", class: "bg-blue-500" },
        { value: "indigo", class: "bg-indigo-500" },
        { value: "violet", class: "bg-violet-500" },
        { value: "purple", class: "bg-purple-500" },
        { value: "fuchsia", class: "bg-fuchsia-500" },
        { value: "pink", class: "bg-pink-500" },
        { value: "rose", class: "bg-rose-500" },
        { value: "gray", class: "bg-gray-500" },
        { value: "slate", class: "bg-slate-500" },
        { value: "zinc", class: "bg-zinc-500" },
    ];

    const handleSubmit = async () => {
        isLoading.value = true;

        try {
            await createCategory(form);
            success("Category created successfully.");
            navigateTo("/categories");
        } catch (err) {
            console.error("Category creation failed:", err);
            error("Category creation failed!");
        } finally {
            isLoading.value = false;
        }
    };
</script>
