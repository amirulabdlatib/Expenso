<template>
    <div class="min-h-screen bg-linear-to-br from-indigo-50 via-white to-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Forgot Password?</h2>
                <p class="text-gray-600">No worries, we'll send you reset instructions</p>
            </div>

            <!-- Forgot Password Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Success Message -->
                <div v-if="emailSent" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600 mt-0.5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="m9 12 2 2 4-4" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-green-800 mb-1">Check your email</h3>
                            <p class="text-sm text-green-700">We've sent password reset instructions to your email address.</p>
                        </div>
                    </div>
                </div>

                <form v-if="!emailSent" class="space-y-6" @submit.prevent="handleForgotPassword">
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-900 mb-2"> Email Address </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="20" height="16" x="2" y="4" rx="2" />
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                </svg>
                            </div>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-colors"
                                placeholder="you@example.com"
                                autocomplete="email" />
                        </div>
                        <span v-if="errors?.email" class="text-red-400 text-sm mt-1 block">{{ errors.email[0] }}</span>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105 font-medium disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center">
                        <span v-if="!isLoading">Send Reset Instructions</span>
                        <span v-else class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            Sending...
                        </span>
                    </button>
                </form>

                <!-- Back to Login Button (shown after email sent) -->
                <div v-if="emailSent">
                    <NuxtLink to="/login" class="w-full inline-flex items-center justify-center bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105 font-medium"> Back to Login </NuxtLink>
                </div>
            </div>

            <!-- Back to Login Link -->
            <div class="mt-6 text-center">
                <NuxtLink to="/login" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                    Back to login
                </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup>
    definePageMeta({
        layout: "public",
        middleware: ["sanctum:guest"],
    });

    useHead({
        title: "Forgot Password - Expenso",
    });

    const { success, error } = useToast();
    const client = useSanctumClient();

    const form = reactive({
        email: "",
    });

    const errors = ref({});
    const isLoading = ref(false);
    const emailSent = ref(false);

    const handleForgotPassword = async () => {
        isLoading.value = true;
        errors.value = {};

        try {
            await client("/forgot-password", {
                method: "POST",
                body: { email: form.email },
            });

            emailSent.value = true;
            success("Password reset instructions sent!");
        } catch (err) {
            console.error("Forgot password error:", err);
            if (err.data?.errors) errors.value = err.data.errors;
            error("Cannot send reset link.");
        } finally {
            isLoading.value = false;
        }
    };
</script>
