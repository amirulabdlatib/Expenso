<template>
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Your Account</h2>
                <p class="text-gray-600">Start managing your finances today</p>
            </div>

            <!-- Register Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form class="space-y-6" @submit.prevent="handleRegister">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-900 mb-2"> Full Name </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-colors"
                                placeholder="John Doe"
                                autocomplete="name"
                            />
                            <span v-if="errors.name" class="text-red-400">{{ errors.name[0] }}</span>
                        </div>
                    </div>

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
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-colors"
                                placeholder="you@example.com"
                                inputmode="email"
                                autocomplete="email"
                            />
                            <span v-if="errors.email" class="text-red-400">{{ errors.email[0] }}</span>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900 mb-2"> Password </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-colors"
                                placeholder="••••••••"
                                autocomplete="new-password"
                            />
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" @click="showPassword = !showPassword">
                                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 hover:text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 hover:text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                    <line x1="2" x2="22" y1="2" y2="22" />
                                </svg>
                            </button>
                        </div>
                        <span v-if="errors.password" class="text-red-400">{{ errors.password[0] }}</span>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2"> Confirm Password </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </div>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-colors"
                                placeholder="••••••••"
                                autocomplete="new-password"
                            />
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" @click="showConfirmPassword = !showConfirmPassword">
                                <svg v-if="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 hover:text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 hover:text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                    <line x1="2" x2="22" y1="2" y2="22" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" v-model="form.terms" type="checkbox" required class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-600" />
                        </div>
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            I agree to the
                            <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Terms and Conditions</a>
                            and
                            <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105 font-medium disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center"
                    >
                        <span v-if="!isLoading">Create Account</span>
                        <span v-else class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            Creating account...
                        </span>
                    </button>
                </form>
            </div>

            <!-- Sign In Link -->
            <p class="mt-6 text-center text-sm text-gray-600">
                Already have an account?
                <NuxtLink to="/login" class="text-indigo-600 hover:text-indigo-700 font-medium"> Sign in </NuxtLink>
            </p>
        </div>
    </div>
</template>

<script setup>
    definePageMeta({
        layout: "public",
        middleware: ["sanctum:guest"],
    });

    const { doRegister, errors } = useAuth();
    const { refreshIdentity } = useSanctumAuth();
    const { success, error } = useToast();

    const form = reactive({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
    });

    const showPassword = ref(false);
    const showConfirmPassword = ref(false);
    const isLoading = ref(false);

    const handleRegister = async () => {
        if (!form.terms) {
            error("Please accept the terms and conditions");
            return;
        }

        isLoading.value = true;

        try {
            await doRegister(form);
            await refreshIdentity();
            success("Account created successfully! Welcome to Expenso");
            navigateTo("/dashboard");
        } catch (err) {
            console.log(err);
            error("Registration failed. Please try again.");
        } finally {
            isLoading.value = false;
        }
    };
</script>
