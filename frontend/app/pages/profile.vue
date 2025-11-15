<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Error Message -->
            <div v-if="error" class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg flex items-center justify-between">
                <div class="flex items-center">
                    <Icon name="heroicons:exclamation-circle" class="w-5 h-5 mr-2" />
                    <span>{{ error?.statusMessage || "An Error has occurred!" }}</span>
                </div>
                <button :disabled="status === 'pending'" class="text-red-700 hover:text-red-900 disabled:opacity-50 flex items-center gap-1" @click="refresh">
                    <Icon name="heroicons:arrow-path" :class="['w-5 h-5', status === 'pending' ? 'animate-spin' : '']" />
                    {{ status === "pending" ? "Retrying..." : "Retry" }}
                </button>
            </div>

            <!-- Loading State -->
            <div v-else-if="status === 'pending'" class="mb-6 p-4 bg-blue-50 text-blue-700 rounded-lg flex items-center">
                <Icon name="heroicons:arrow-path" class="w-5 h-5 mr-2 animate-spin" />
                <span>Loading profile data...</span>
            </div>

            <div v-else>
                <!-- Profile Header-->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6">
                    <div class="flex flex-col md:flex-row items-start space-y-6 md:space-y-0 md:space-x-8">
                        <ProfilePictureNickName />
                        <ProfileInfo />
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column -->
                    <div class="lg:col-span-1 space-y-6">
                        <ProfileStatsCard :total-income="Number(data?.total_income ?? 0)" :total-expenses="Number(data?.total_expenses ?? 0)" :total-net-balance="Number(data?.net_balance ?? 0)" />
                        <ProfilePreferences />
                    </div>

                    <!-- Right Column -->
                    <div class="lg:col-span-2 space-y-6">
                        <ProfileActivityOverview :total-accounts="Number(data?.total_accounts ?? 0)" :total-transactions="Number(data?.total_transactions ?? 0)" />
                        <ProfileRecentActivity />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Profile - Expenso",
    });
    const { data, status, error, refresh } = await useSanctumFetch("api/profile");
</script>
