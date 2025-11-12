<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v20" />
                    <path d="m5 9 7-7 7 7" />
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-1">Monthly Income</p>
        <p class="text-2xl font-bold text-gray-900">RM {{ monthlyIncome }}</p>
        <p class="text-sm text-gray-500 mt-2">{{ currentMonthYear }}</p>
    </div>
</template>

<script setup>
    const { formatMonthYear } = useUtils();
    const currentMonthYear = formatMonthYear();

    defineProps({
        monthlyIncome: {
            type: Number,
            default: 0,
        },
    });
</script>
