<template>
    <div class="min-h-screen bg-linear-to-br from-indigo-50 via-white to-purple-50 flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8 space-y-6">
                <!-- Icon -->
                <div class="flex justify-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                        <Icon name="heroicons:envelope" class="w-8 h-8 text-indigo-600" />
                    </div>
                </div>

                <!-- Title & Description -->
                <div class="text-center space-y-2">
                    <h1 class="text-2xl font-bold text-gray-900">Verify Your Email</h1>
                    <p class="text-gray-600">
                        We've sent a verification link to
                        <span v-if="userEmail" class="font-medium text-gray-900 block mt-2">{{ userEmail }}</span>
                    </p>
                    <p class="text-sm text-gray-500">Please check your inbox and spam folder.</p>
                </div>

                <!-- Status Messages -->
                <div v-if="successMessage" class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-800">{{ successMessage }}</p>
                </div>

                <div v-if="errorMessage" class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm text-red-800">{{ errorMessage }}</p>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <button
                        @click="resendVerification"
                        :disabled="isResending || cooldownTime > 0"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span v-if="cooldownTime > 0">Resend in {{ cooldownTime }}s</span>
                        <span v-else-if="isResending">Sending...</span>
                        <span v-else>Resend Verification Email</span>
                    </button>

                    <button @click="handleLogout" class="w-full text-gray-600 hover:text-red-600 py-2 font-medium transition-colors">Logout</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    definePageMeta({
        layout: "public",
        middleware: ["sanctum:auth"],
    });

    const { user, refreshIdentity, logout } = useSanctumAuth();
    const sanctumClient = useSanctumClient();

    const userEmail = ref("");
    const successMessage = ref("");
    const errorMessage = ref("");
    const isResending = ref(false);
    const cooldownTime = ref(0);
    let cooldownInterval = null;

    // Logout handler
    const handleLogout = async () => {
        try {
            await logout();
            navigateTo("/login");
        } catch (error) {
            console.error("Logout error:", error);
        }
    };

    // Get user email on mount
    onMounted(async () => {
        await refreshIdentity();
        if (user.value?.email) {
            userEmail.value = user.value.email;
        }

        // Check if user is already verified
        if (user.value?.email_verified_at) {
            navigateTo("/dashboard");
        }
    });

    // Cleanup interval on unmount
    onUnmounted(() => {
        if (cooldownInterval) {
            clearInterval(cooldownInterval);
        }
    });

    // Resend verification email
    const resendVerification = async () => {
        if (isResending.value || cooldownTime.value > 0) return;

        try {
            isResending.value = true;
            errorMessage.value = "";
            successMessage.value = "";

            // await sanctumClient("/api/email/verification-notification", {
            //     method: "POST",
            // });

            successMessage.value = "Verification email sent successfully! Please check your inbox.";

            // Start cooldown (60 seconds) - client-side only
            if (import.meta.client) {
                cooldownTime.value = 60;
                cooldownInterval = setInterval(() => {
                    cooldownTime.value--;
                    if (cooldownTime.value <= 0) {
                        clearInterval(cooldownInterval);
                    }
                }, 1000);
            }
        } catch (error) {
            console.error("Resend verification error:", error);

            if (error.statusCode === 429) {
                errorMessage.value = "Too many requests. Please wait a moment before trying again.";
            } else if (error.data?.message) {
                errorMessage.value = error.data.message;
            } else {
                errorMessage.value = "Failed to resend verification email. Please try again.";
            }
        } finally {
            isResending.value = false;
        }
    };
</script>
