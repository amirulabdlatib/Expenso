<template>
    <!-- Danger Zone -->
    <div class="bg-white rounded-lg shadow-sm border border-red-200 overflow-hidden">
        <div class="p-6 border-b border-red-200 bg-red-50">
            <div class="flex items-center space-x-3">
                <Icon name="heroicons:exclamation-triangle" class="w-6 h-6 text-red-600" />
                <h2 class="text-xl font-semibold text-red-900">Danger Zone</h2>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between py-3 border-t border-gray-100">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Delete Account</p>
                    <p class="text-xs text-gray-500 mt-1">Permanently delete your account and all associated data</p>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium" :disabled="isDeleting" @click="confirmDelete">
                    <span v-if="!isDeleting">Delete Account</span>
                    <span v-else>Deleting...</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
    const { deleteProfile, errors } = useProfile();
    const { success, error: toastError } = useToast();

    const isDeleting = ref(false);

    const confirmDelete = () => {
        if (confirm("Are you sure you want to delete your account? This action cannot be undone. All your data will be permanently removed.")) {
            deleteAccount();
        }
    };

    const deleteAccount = async () => {
        isDeleting.value = true;
        try {
            await deleteProfile();
            success("Your account has been deleted successfully.");
            await navigateTo("/", { replace: true, external: true });
        } catch (err) {
            errors.value = err.data?.errors || {};
            toastError("Failed to delete account. Please try again.");
        } finally {
            isDeleting.value = false;
        }
    };
</script>
