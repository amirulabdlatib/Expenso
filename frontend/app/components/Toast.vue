<template>
    <div class="fixed top-20 sm:top-4 right-4 z-[60] space-y-3 max-w-sm w-full px-4 sm:px-0">
        <TransitionGroup name="toast">
            <div v-for="toast in toasts" :key="toast.id" class="flex items-start gap-3 p-4 rounded-xl shadow-lg border border-gray-200 bg-white transform transition-all duration-300 ease-in-out">
                <!-- Icon with colored background -->
                <div :class="['flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center', iconStyles[toast.type].bg]">
                    <svg v-if="toast.type === 'success'" :class="['w-5 h-5', iconStyles[toast.type].color]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                    <svg v-else-if="toast.type === 'error'" :class="['w-5 h-5', iconStyles[toast.type].color]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m15 9-6 6M9 9l6 6" />
                    </svg>
                    <svg v-else-if="toast.type === 'warning'" :class="['w-5 h-5', iconStyles[toast.type].color]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                        <path d="M12 9v4M12 17h.01" />
                    </svg>
                    <svg v-else :class="['w-5 h-5', iconStyles[toast.type].color]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4M12 8h.01" />
                    </svg>
                </div>

                <!-- Message -->
                <p class="flex-1 text-sm font-medium leading-relaxed text-gray-900">
                    {{ toast.message }}
                </p>

                <!-- Close Button -->
                <button class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors" aria-label="Close" @click="removeToast(toast.id)">
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

    const iconStyles = {
        success: {
            bg: "bg-green-100",
            color: "text-green-600",
        },
        error: {
            bg: "bg-red-100",
            color: "text-red-600",
        },
        warning: {
            bg: "bg-yellow-100",
            color: "text-yellow-600",
        },
        info: {
            bg: "bg-indigo-100",
            color: "text-indigo-600",
        },
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
