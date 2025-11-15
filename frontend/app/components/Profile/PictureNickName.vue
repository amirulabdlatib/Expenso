<template>
    <div class="relative group">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 text-3xl font-semibold border-2 border-gray-200">{{ nickName }}</div>
        <button class="absolute bottom-0 right-0 w-8 h-8 bg-gray-900 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
            <Icon name="heroicons:camera" class="w-4 h-4" />
        </button>
    </div>
</template>

<script setup>
    const { nickName } = useNameDisplay();
</script>
