<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
                <!-- Backdrop - Lighter version -->
                <div class="fixed inset-0 bg-gray-300 bg-opacity-40 transition-opacity" @click="handleClose"></div>

                <!-- Modal Container -->
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
                        <!-- Modal Content -->
                        <div class="p-6">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                                <Icon name="heroicons:exclamation-triangle" class="w-6 h-6 text-red-600" />
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Delete warning</h3>
                            <p class="text-gray-600 text-center mb-6">Are you sure you want to delete this account? This action cannot be undone.</p>

                            <!-- Modal Actions -->
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button
                                    class="flex-1 px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    @click="handleClose"
                                >
                                    Cancel
                                </button>
                                <button class="flex-1 px-4 py-3 text-white bg-red-600 rounded-lg hover:bg-red-700 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" @click="handleConfirm">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
    defineProps({
        show: Boolean,
    });

    const emit = defineEmits(["close", "confirm"]);

    const handleClose = () => {
        emit("close");
    };

    const handleConfirm = () => {
        emit("confirm");
    };
</script>

<style scoped>
    .modal-enter-active,
    .modal-leave-active {
        transition: opacity 0.3s ease;
    }

    .modal-enter-from,
    .modal-leave-to {
        opacity: 0;
    }

    .modal-enter-active .relative,
    .modal-leave-active .relative {
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .modal-enter-from .relative,
    .modal-leave-to .relative {
        transform: scale(0.95);
        opacity: 0;
    }
</style>
