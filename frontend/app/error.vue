<script setup lang="ts">
    import type { NuxtError } from "#app";

    const props = defineProps({
        error: {
            type: Object as () => NuxtError,
            required: true,
        },
    });

    const errorMessages: Record<number, { title: string; description: string }> = {
        404: {
            title: "Page Not Found",
            description: "The page you're looking for doesn't exist or has been moved.",
        },
        500: {
            title: "Server Error",
            description: "Something went wrong on our end. We're working to fix it.",
        },
        403: {
            title: "Access Denied",
            description: "You don't have permission to access this resource.",
        },
    };

    const getErrorInfo = () => {
        const code = props.error?.statusCode || 500;
        return (
            errorMessages[code] || {
                title: "Oops! Something Went Wrong",
                description: "An unexpected error occurred. Please try again later.",
            }
        );
    };

    const errorCode = computed(() => props.error?.statusCode || 500);

    useHead({
        title: `Error ${errorCode.value} - Expenso`,
        meta: [{ name: "robots", content: "noindex" }],
    });

    const errorInfo = getErrorInfo();
</script>

<template>
    <div class="min-h-screen bg-linear-to-br from-slate-50 to-blue-50 flex items-center justify-center px-4">
        <div class="max-w-2xl w-full">
            <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-2xl mb-6">
                    <span class="text-3xl font-bold text-blue-600">
                        {{ props.error?.statusCode || "500" }}
                    </span>
                </div>

                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    {{ errorInfo.title }}
                </h1>

                <p class="text-lg text-slate-600 mb-8 max-w-md mx-auto">
                    {{ errorInfo.description }}
                </p>

                <div v-if="props.error?.message" class="bg-slate-50 rounded-xl p-4 mb-8 text-left">
                    <p class="text-sm font-mono text-slate-700">
                        {{ props.error.message }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <NuxtLink to="/" class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/30 w-full sm:w-auto">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>
                        Go Back Home
                    </NuxtLink>

                    <button class="inline-flex items-center justify-center px-8 py-3 bg-white text-slate-700 font-semibold rounded-xl border-2 border-slate-200 hover:bg-slate-50 transition-colors w-full sm:w-auto" @click="$router.back()">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Go Back
                    </button>
                </div>
            </div>

            <!-- Additional Help Text -->
            <p class="text-center text-slate-500 mt-8">
                Need help?
                <NuxtLink to="/contact" class="text-blue-600 hover:text-blue-700 font-medium ml-1"> Contact Support </NuxtLink>
            </p>
        </div>

        <!-- Decorative Elements -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-blue-200 rounded-full blur-3xl opacity-20"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-200 rounded-full blur-3xl opacity-20"></div>
        </div>
    </div>
</template>
