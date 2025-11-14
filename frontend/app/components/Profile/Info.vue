<template>
    <div class="flex-1">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-1">{{ user.name }}</h1>
            <p class="text-sm text-gray-500">{{ user.email }}</p>
        </div>

        <!-- Meta Information -->
        <div class="flex flex-wrap items-center gap-6 mb-6 text-sm text-gray-600">
            <div class="flex items-center gap-2">
                <Icon name="heroicons:calendar" class="w-4 h-4 text-gray-400" />
                <span>Joined {{ formatMonthYearOnly(user.created_at) }}</span>
            </div>
            <div v-if="isVerified" class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <span>{{ isVerified }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
            <NuxtLink to="/settings" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                <Icon name="heroicons:pencil-square" class="w-4 h-4" />
                Edit Profile
            </NuxtLink>
            <button class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors" @click="shareProfile">
                <Icon name="heroicons:share" class="w-4 h-4" />
                Share Profile
            </button>
        </div>
    </div>
</template>
<script setup>
    const { user } = useSanctumAuth();
    const { formatMonthYearOnly } = useUtils();

    const isVerified = computed(() => (user?.value?.isVerified ? "Verified Account" : ""));

    const shareProfile = async () => {
        console.log("Profile shared");
    };
</script>
