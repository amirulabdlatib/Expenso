<template>
    <div>edit page</div>
</template>
<script setup>
    useHead({
        title: "Edit Category Details - Expenso",
    });
    definePageMeta({
        middleware: ["sanctum:auth"],
    });
</script>
