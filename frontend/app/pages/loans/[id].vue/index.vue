<template>
    <div>show loan detail payment and progress</div>
</template>

<script setup>
    useHead({
        title: "Details Loan - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });
</script>
