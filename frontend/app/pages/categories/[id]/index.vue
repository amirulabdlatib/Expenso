<template>
    <div>detail category page</div>
</template>

<script setup>
    useHead({
        title: "Account Details - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const client = useSanctumClient();
    const route = useRoute();

    const { data: category, pending, error, refresh } = await useAsyncData(`category-${route.params.id}`, () => client(`/api/categories/${route.params.id}`));

    console.log(category.value);
</script>
