<template>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ capitalizedName }}! Here's your financial overview.</p>
    </div>
</template>

<script setup>
    const { capitalizedName } = useNameDisplay();
</script>
