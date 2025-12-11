<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
            <div class="text-center">
                <div
                    class="mx-auto flex items-center justify-center h-20 w-20 rounded-full"
                    :class="{
                        'bg-green-100': status === 'success' || status === 'verified',
                        'bg-yellow-100': status === 'expired',
                    }">
                    <Icon v-if="status === 'success'" name="heroicons:check-circle" class="h-12 w-12 text-green-600" aria-hidden="true" />
                    <Icon v-else-if="status === 'expired'" name="heroicons:exclamation-triangle" class="h-12 w-12 text-yellow-500" aria-hidden="true" />
                    <Icon v-else-if="status === 'verified'" name="heroicons:envelope-open" class="h-12 w-12 text-blue-500" aria-hidden="true" />
                </div>

                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    <span v-if="status === 'success'">Email Verified!</span>
                    <span v-else-if="status === 'expired'">Link Expired</span>
                    <span v-else-if="status === 'verified'">Already Verified</span>
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    <span v-if="status === 'success'">Your email has been successfully verified. You can now access all features.</span>
                    <span v-else-if="status === 'expired'">This verification link has expired. Please request a new one.</span>
                    <span v-else-if="status === 'verified'">Your email was already verified. You're all set!</span>
                </p>
            </div>

            <div class="mt-8 flex justify-center">
                <NuxtLink
                    to="/dashboard"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    Go to Dasboard
                </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup>
    const route = useRoute();
    const status = route.query.status;

    // Set page metadata
    definePageMeta({
        layout: "public",
        middleware: "verify-result",
    });

    // Set page title
    useHead({
        title: status === "success" ? "Email Verified - Expenso" : status === "expired" ? "Link Expired - Expenso" : "Already Verified - Expenso",
    });
</script>
