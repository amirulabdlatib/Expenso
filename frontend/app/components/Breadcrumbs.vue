<template>
    <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
        <NuxtLink to="/dashboard" class="hover:text-indigo-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
        </NuxtLink>

        <template v-for="(crumb, index) in breadcrumbs" :key="index">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m9 18 6-6-6-6" />
            </svg>

            <NuxtLink v-if="index < breadcrumbs.length - 1" :to="crumb.path" class="hover:text-indigo-600 transition-colors">
                {{ crumb.name }}
            </NuxtLink>

            <span v-else class="font-medium text-gray-900">
                {{ crumb.name }}
            </span>
        </template>
    </nav>
</template>

<script setup>
    const route = useRoute();

    const breadcrumbs = computed(() => {
        const pathArray = route.path.split("/").filter(Boolean);
        const crumbs = [];

        pathArray.forEach((path, index) => {
            const routePath = "/" + pathArray.slice(0, index + 1).join("/");
            const name = path.charAt(0).toUpperCase() + path.slice(1);

            crumbs.push({
                name: name,
                path: routePath,
            });
        });

        return crumbs;
    });
</script>
