<template>
    <div class="min-h-screen bg-gray-50">
        <AppNavbar />

        <AppSidebar />

        <!-- Main Content - DYNAMIC LEFT MARGIN -->
        <main :class="['pt-16 transition-all duration-300', sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-64']">
            <div class="p-6">
                <!-- Breadcrumbs -->
                <Breadcrumbs />

                <!-- Page Content -->
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
    import { ref, onMounted, onUnmounted } from "vue";

    const sidebarCollapsed = ref(false);

    const handleSidebarCollapse = (event) => {
        sidebarCollapsed.value = event.detail?.collapsed || false;
    };

    onMounted(() => {
        window.addEventListener("sidebar-collapse-state", handleSidebarCollapse);
    });

    onUnmounted(() => {
        window.removeEventListener("sidebar-collapse-state", handleSidebarCollapse);
    });
</script>
