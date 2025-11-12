<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <circle cx="12" cy="12" r="6" />
                    <circle cx="12" cy="12" r="2" />
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-1">Savings Goals</p>
        <p class="text-2xl font-bold text-gray-900">RM 15,000</p>
        <div class="flex items-center space-x-2 mt-2">
            <div class="flex-1 h-2 bg-purple-100 rounded-full overflow-hidden">
                <div class="h-full bg-purple-600 rounded-full" style="width: 65%" />
            </div>
            <span class="text-sm text-purple-600 font-semibold">65%</span>
        </div>
    </div>
</template>
