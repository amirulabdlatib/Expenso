<template>
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
        <div class="flex items-start space-x-4">
            <Icon name="heroicons:exclamation-triangle" class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" />
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-yellow-900 mb-2">No Accounts Found</h3>
                <p class="text-yellow-800 mb-4">You need to create at least one account before you can add transactions.</p>
                <NuxtLink to="/accounts/create" class="inline-flex items-center space-x-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-medium">
                    <Icon name="heroicons:plus" class="w-5 h-5" />
                    <span>Create Account</span>
                </NuxtLink>
            </div>
        </div>
    </div>
</template>
