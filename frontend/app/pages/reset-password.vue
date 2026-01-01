<template>
    <div class="min-h-screen bg-linear-to-br from-indigo-50 via-white to-gray-50 flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h2>
                <p class="text-gray-600">Enter a new password for your account</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form class="space-y-6" @submit.prevent="handleReset">
                    <!-- Email (readonly) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Email</label>
                        <input type="email" :value="form.email" disabled class="w-full bg-gray-100 cursor-not-allowed py-3 px-4 rounded-lg border border-gray-300" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">New Password</label>
                        <input type="password" v-model="form.password" required class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" />
                        <span v-if="errors?.password" class="text-red-400 text-sm mt-1 block">
                            {{ errors.password[0] }}
                        </span>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Confirm Password</label>
                        <input type="password" v-model="form.password_confirmation" required class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" />
                    </div>

                    <!-- Button -->
                    <button type="submit" :disabled="isLoading" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105 disabled:opacity-50 disabled:transform-none">
                        <span v-if="!isLoading">Reset Password</span>
                        <span v-else>Processing...</span>
                    </button>
                </form>
            </div>

            <div class="text-center mt-6">
                <NuxtLink to="/login" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium"> Back to login </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup>
    definePageMeta({
        layout: "public",
        middleware: ["sanctum:guest"],
    });

    const route = useRoute();
    const { success, error } = useToast();
    const client = useSanctumClient();

    const form = reactive({
        token: route.query.token,
        email: route.query.email,
        password: "",
        password_confirmation: "",
    });

    const errors = ref({});
    const isLoading = ref(false);

    const handleReset = async () => {
        isLoading.value = true;
        errors.value = {};

        try {
            await client("/reset-password", {
                method: "POST",
                body: form,
            });

            success("Password reset successfully!");
            navigateTo("/login");
        } catch (err) {
            if (err.data?.errors) errors.value = err.data.errors;
            error("Failed to reset password.");
        } finally {
            isLoading.value = false;
        }
    };
</script>
