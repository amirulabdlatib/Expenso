<template>
    <div class="fixed top-4 right-4 z-50 space-y-3 max-w-sm w-full px-4 sm:px-0">
        <TransitionGroup name="toast">
            <div v-for="toast in toasts" :key="toast.id" :class="['flex items-start gap-3 p-4 rounded-lg shadow-lg border backdrop-blur-sm', 'transform transition-all duration-300 ease-in-out', toastStyles[toast.type]]">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <svg v-if="toast.type === 'success'" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                    <svg v-else-if="toast.type === 'error'" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m15 9-6 6M9 9l6 6" />
                    </svg>
                    <svg v-else-if="toast.type === 'warning'" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                        <path d="M12 9v4M12 17h.01" />
                    </svg>
                    <svg v-else class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4M12 8h.01" />
                    </svg>
                </div>

                <!-- Message -->
                <p class="flex-1 text-sm font-medium leading-relaxed">
                    {{ toast.message }}
                </p>

                <!-- Close Button -->
                <button @click="removeToast(toast.id)" class="flex-shrink-0 opacity-70 hover:opacity-100 transition-opacity" aria-label="Close">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
    const { toasts, removeToast } = useToast();

    const toastStyles = {
        success: "bg-green-50/95 border-green-200 text-green-800",
        error: "bg-red-50/95 border-red-200 text-red-800",
        warning: "bg-yellow-50/95 border-yellow-200 text-yellow-800",
        info: "bg-indigo-50/95 border-indigo-200 text-indigo-800",
    };
</script>

<style scoped>
    .toast-enter-active,
    .toast-leave-active {
        transition: all 0.3s ease;
    }

    .toast-enter-from {
        opacity: 0;
        transform: translateX(100%);
    }

    .toast-leave-to {
        opacity: 0;
        transform: translateX(100%) scale(0.9);
    }

    .toast-move {
        transition: transform 0.3s ease;
    }
</style>
