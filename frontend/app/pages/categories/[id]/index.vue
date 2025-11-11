<template>
    <div class="container mx-auto px-4 py-6">
        <!-- Loading State -->
        <div v-if="pending" class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">Error loading category: Category {{ error.statusMessage }}</p>
                </div>
            </div>
        </div>

        <!-- Category Details -->
        <div v-else-if="data" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Category Header -->
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div :class="['p-3 rounded-lg', getColorClasses(data?.color).bgClass]">
                            <Icon :name="data?.icon || 'heroicons:cube'" :class="['w-6 h-6', getColorClasses(data?.color).textClass]" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">{{ data.name }}</h1>
                            <p class="text-sm text-gray-500">
                                {{ data.type === "income" ? "Income" : "Expense" }} Category
                                <span v-if="data.parent" class="block text-gray-400">
                                    Parent: {{ data.parent.name }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <button :disabled="pending" class="p-2 text-gray-500 hover:text-indigo-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" title="Refresh data" @click="refresh()">
                        <Icon :name="pending ? 'svg-spinners:ring-resize' : 'heroicons:arrow-path'" class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Subcategories -->
            <div class="px-6 py-5">
                <div v-if="data.children?.length" class="space-y-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Subcategories</h3>
                    <NuxtLink v-for="subcategory in data.children" :key="subcategory.id" :to="`/categories/${subcategory.id}`" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100">
                        <div :class="['p-2 rounded-md', getColorClasses(subcategory?.color).bgClass]">
                            <Icon :name="subcategory?.icon || 'heroicons:cube'" :class="['w-5 h-5', getColorClasses(subcategory?.color).textClass]" />
                        </div>
                        <span class="ml-3 font-medium text-gray-900">{{ subcategory.name }}</span>
                    </NuxtLink>
                </div>

                <div v-else-if="data.parent" class="text-center py-4 text-gray-500">
                    <NuxtLink :to="`/categories/${data.parent.id}`" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100">
                        <div :class="['p-2 rounded-md', getColorClasses(data.parent?.color).bgClass]">
                            <Icon :name="data.parent?.icon || 'heroicons:arrow-uturn-left'" :class="['w-5 h-5', getColorClasses(data.parent?.color).textClass]" />
                        </div>
                        <span class="ml-3 font-medium text-gray-900">Back to {{ data.parent.name }}</span>
                    </NuxtLink>
                </div>
                <div v-else class="text-center py-4 text-gray-500">No subcategories found</div>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Category Details - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const client = useSanctumClient();
    const route = useRoute();
    const { getColorClasses } = useCategoryConstant();

    const { data: categoryData, pending, error, refresh } = await useAsyncData(`category-${route.params.id}`, async () => {
        const response = await client(`/api/categories/${route.params.id}`);
        return response.category;
    });

    // Create a reactive reference to the category data
    const data = computed(() => categoryData.value);
</script>
