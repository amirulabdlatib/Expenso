<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
                <p class="text-gray-600 mt-2">Manage your account preferences and application settings</p>
            </div>

            <!-- Settings Sections -->
            <div class="space-y-6">
                <SettingsProfile />
                <SettingsSecurity />
                <SettingsDangerZone />
                <SettingsAppInfo />
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Settings - Expenso",
    });
</script>
