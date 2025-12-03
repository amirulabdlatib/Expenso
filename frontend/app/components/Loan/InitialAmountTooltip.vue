<template>
    <!-- Tooltip Wrapper -->
    <div class="relative group">
        <Icon name="heroicons:information-circle" class="w-4 h-4 text-gray-400 cursor-pointer" />

        <!-- Tooltip Box -->
        <div class="absolute left-6 top-1/2 -translate-y-1/2 w-56 px-3 py-2 rounded-md bg-gray-800 text-white text-xs shadow-lg opacity-0 group-hover:opacity-100 group-hover:visible invisible transition-opacity duration-200 z-20">
            This amount is money already paid earlier which was not tracked in the app.
        </div>
    </div>
</template>
