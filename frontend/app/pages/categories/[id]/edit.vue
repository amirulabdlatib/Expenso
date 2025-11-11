<template>
    <div>edit page</div>
</template>
<script setup>
    useHead({
        title: "Account Details - Expenso",
    });
    definePageMeta({
        middleware: ["sanctum:auth"],
    });
</script>
