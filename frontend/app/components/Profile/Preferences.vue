<template>
    <!-- Preferences -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Preferences</h2>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Currency</span>
                <span class="font-semibold text-gray-900">{{ profile.currency }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Language</span>
                <span class="font-semibold text-gray-900">{{ profile.language }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Theme</span>
                <span class="font-semibold text-gray-900">{{ profile.theme }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Notifications</span>
                <span class="font-semibold text-gray-900">{{ profile.notifications ? "Enabled" : "Disabled" }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
    const profile = ref({
        currency: "MYR",
        language: "English",
        theme: "Light",
        notifications: true,
    });
</script>
