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

                        <!-- Subcategory Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                    <Icon name="heroicons:folder-minus" class="w-5 h-5 text-indigo-600" />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Make this a subcategory</p>
                                    <p class="text-xs text-gray-500">Nest this under an existing parent category</p>
                                </div>
                            </div>
                            <button
                                type="button"
                                :disabled="isLoading"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                :class="isSubcategory ? 'bg-indigo-600' : 'bg-gray-200'"
                                @click="isSubcategory = !isSubcategory">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="isSubcategory ? 'translate-x-5' : 'translate-x-0'" />
                            </button>
                        </div>

                        <!-- Parent Category Selection (shown only when isSubcategory is true) -->
                        <div v-if="isSubcategory" class="space-y-3">
                            <label class="block text-sm font-medium text-gray-700"> Parent Category <span class="text-red-500">*</span> </label>

                            <!-- Loading State -->
                            <div v-if="categoriesLoading" class="flex items-center justify-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                            </div>

                            <!-- Parent Categories Grid -->
                            <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button
                                    v-for="category in parentCategories"
                                    :key="category.id"
                                    type="button"
                                    :disabled="isLoading"
                                    class="relative p-4 border-2 rounded-lg transition-all duration-200 text-left"
                                    :class="[form.parent_id === category.id ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50', isLoading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']"
                                    @click="form.parent_id = category.id">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center" :class="getColorClasses(category.color).bgClass">
                                            <Icon :name="category.icon" class="w-5 h-5" :class="getColorClasses(category.color).textClass" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900">{{ category.name }}</p>
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full mt-1" :class="category.type === 'expense' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'">
                                                {{ capitalizeWord(category.type) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div v-if="form.parent_id === category.id" class="absolute top-3 right-3 w-5 h-5 bg-indigo-600 rounded-full flex items-center justify-center">
                                        <Icon name="heroicons:check" class="w-3 h-3 text-white" />
                                    </div>
                                </button>
                            </div>

                            <!-- Empty State -->
                            <div v-if="!categoriesLoading && parentCategories.length === 0" class="text-center py-8 px-4 border-2 border-dashed border-gray-300 rounded-lg">
                                <Icon name="heroicons:folder-open" class="w-12 h-12 text-gray-400 mx-auto mb-2" />
                                <p class="text-sm text-gray-600">No parent categories available</p>
                                <p class="text-xs text-gray-500 mt-1">Create a main category first</p>
                            </div>

                            <p v-if="errors.parent_id" class="mt-1 text-sm text-red-500">{{ errors.parent_id[0] }}</p>
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
                            <li>• Use subcategories to organize related expenses under a main category</li>
                            <li>• Subcategories inherit the type (expense/income) from their parent</li>
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
    const { categoryTypes, categoryIcons, categoryColors, getColorClasses } = useCategoryConstant();
    const { success, error } = useToast();
    const client = useSanctumClient();

    const isLoading = ref(false);
    const isSubcategory = ref(false);

    const form = reactive({
        name: "",
        type: "",
        icon: "",
        color: "",
        parent_id: null,
    });

    const { data: categoriesData, status: categoriesStatus } = await useAsyncData("parent-categories", () => client("api/categories"));

    const parentCategories = computed(() => {
        return categoriesData.value?.categories?.filter((cat) => !cat.parent_id) || [];
    });

    const categoriesLoading = computed(() => categoriesStatus.value === "pending");

    watch(isSubcategory, (newValue) => {
        if (!newValue) {
            form.parent_id = null;
        }
    });

    function capitalizeWord(text) {
        if (!text) return "";
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

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
