<template>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Loading State -->
            <div v-if="categoriesLoading" class="flex flex-col items-center justify-center py-12">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-indigo-600 mb-4"></div>
                <p class="text-gray-600">Loading category data...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="fetchDataError" class="bg-white rounded-xl shadow-sm border border-red-200 p-6 mb-6">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <Icon name="heroicons:exclamation-triangle" class="w-8 h-8 text-red-600" />
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Failed to load category</h2>
                    <p class="text-gray-600 mb-6">We couldn't load the category data. {{ fetchDataError.statusMessage || "Please try again." }}</p>
                    <button :disabled="categoriesLoading" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center space-x-2 disabled:opacity-50" @click="refresh()">
                        <Icon v-if="categoriesLoading" name="heroicons:arrow-path" class="w-4 h-4 animate-spin" />
                        <span>{{ categoriesLoading ? "Refreshing..." : "Try Again" }}</span>
                    </button>
                </div>
            </div>

            <!-- Success State -->
            <div v-else>
                <div class="mb-8">
                    <div class="flex items-center space-x-3 mb-3">
                        <NuxtLink :to="`/categories/${route.params.id}`" class="p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all">
                            <Icon name="heroicons:arrow-left" class="w-5 h-5 text-gray-600" />
                        </NuxtLink>
                        <h1 class="text-xl font-bold text-gray-900">Edit Category</h1>
                    </div>
                    <p class="text-gray-600 ml-14">Update category to organize your expenses and income</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <form class="p-6 md:p-8">
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2"> Category Name <span class="text-red-500">*</span> </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="e.g., Food & Dining, Transportation, Entertainment"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    :disabled="isUpdating" />
                                <p v-if="errors.name" class="mt-1 text-sm text-red-500">{{ errors.name[0] }}</p>
                            </div>

                            <!-- Category Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3"> Category Type <span class="text-red-500">*</span> </label>

                                <div v-if="isSubCategory" class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <Icon name="heroicons:information-circle" class="h-5 w-5 text-blue-400" />
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700">
                                                This is a subcategory. The type is inherited from the parent category:
                                                <span class="font-medium">{{ capitalizeWord(categoriesData.category.type) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <button
                                        v-for="type in categoryTypes"
                                        :key="type.value"
                                        type="button"
                                        :disabled="isUpdating || isSubCategory"
                                        class="relative p-4 border-2 rounded-lg transition-all duration-200 text-left"
                                        :class="[
                                            form.type === type.value ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50',
                                            isUpdating ? 'opacity-50 cursor-not-allowed' : isSubCategory ? 'opacity-70 cursor-not-allowed bg-gray-50' : 'cursor-pointer',
                                        ]"
                                        @click="form.type = type.value">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center" :class="type.bgColor">
                                                <Icon :name="type.icon" class="w-5 h-5" :class="type.iconColor" />
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold" :class="isSubCategory ? 'text-gray-500' : 'text-gray-900'">{{ type.label }}</p>
                                                <p class="text-xs mt-1" :class="isSubCategory ? 'text-gray-400' : 'text-gray-500'">{{ type.description }}</p>
                                            </div>
                                        </div>
                                        <div v-if="form.type === type.value" class="absolute top-3 right-3 w-5 h-5 rounded-full flex items-center justify-center" :class="isSubCategory ? 'bg-gray-400' : 'bg-indigo-600'">
                                            <Icon name="heroicons:check" class="w-3 h-3 text-white" />
                                        </div>
                                        <div v-if="isSubCategory && form.type === type.value" class="absolute inset-0 bg-white/30 rounded-md"></div>
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
                                        :disabled="isUpdating"
                                        class="aspect-square p-3 border-2 rounded-lg transition-all duration-200 hover:scale-105"
                                        :class="[form.icon === iconOption ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50', isUpdating ? 'opacity-50 cursor-not-allowed' : '']"
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
                                        :disabled="isUpdating"
                                        class="aspect-square rounded-lg transition-all duration-200 hover:scale-110 relative"
                                        :class="[colorOption.class, isUpdating ? 'opacity-50 cursor-not-allowed' : '']"
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
                                :class="isUpdating ? 'pointer-events-none opacity-50' : ''">
                                Cancel
                            </NuxtLink>
                            <button
                                type="submit"
                                :disabled="isUpdating"
                                class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="!isUpdating" class="flex items-center space-x-2">
                                    <span>Update</span>
                                </span>
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
    </div>
</template>

<script setup>
    useHead({
        title: "Edit Category - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const { errors } = useCategory();
    const { categoryTypes, categoryIcons, categoryColors } = useCategoryConstant();
    const { capitalizeWord } = useUtils();
    // const { success, error } = useToast();
    const route = useRoute();

    const isUpdating = ref(false);
    const form = reactive({
        name: "",
        type: "",
        icon: "",
        color: "",
        parent_id: null,
    });

    const { data: categoriesData, status: categoriesStatus, error: fetchDataError, refresh } = await useSanctumFetch(`/api/categories/${route.params.id}/edit`);

    watch(
        () => categoriesData.value?.category,
        (category) => {
            if (category) {
                form.name = category.name;
                form.type = category.type;
                form.icon = category.icon;
                form.color = category.color;
                form.parent_id = category.parent_id;
            }
        },
        { immediate: true }
    );

    const categoriesLoading = computed(() => categoriesStatus.value === "pending");
    const isSubCategory = computed(() => categoriesData.value.category.parent_id);

    // const handleSubmit = async () => {
    //     isUpdating.value = true;

    //     try {
    //         await createCategory(form);
    //         success("Category created successfully.");
    //         navigateTo("/categories");
    //     } catch (err) {
    //         console.error("Category creation failed:", err);
    //         error("Category creation failed!");
    //     } finally {
    //         isUpdating.value = false;
    //     }
    // };
</script>
