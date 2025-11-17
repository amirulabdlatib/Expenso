<template>
    <!-- Security -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <Icon name="heroicons:shield-check" class="w-6 h-6 text-indigo-600" />
                <h2 class="text-xl font-semibold text-gray-900">Security</h2>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between py-3 border-t border-gray-100">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Two-Factor Authentication</p>
                    <p class="text-xs text-gray-500 mt-1">Add an extra layer of security to your account</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input v-model="security.twoFactor" type="checkbox" class="sr-only peer" />
                    <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600" />
                </label>
            </div>
        </div>
    </div>
</template>

<script setup>
    const security = ref({
        twoFactor: false,
    });
</script>
