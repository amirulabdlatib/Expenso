<template>
    <!-- Overlay for mobile -->
    <div v-if="isOpen && isMobile" class="fixed inset-0 bg-gray-300 bg-opacity-50 z-30 lg:hidden" style="top: 4rem" @click="closeSidebar" />

    <!-- Sidebar - FIXED ON LEFT -->
    <aside
        :class="['fixed top-16 left-0 h-[calc(100vh-4rem)] bg-white border-r border-gray-200 z-40 transition-all duration-300', isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0', isCollapsed && !isMobile ? 'w-20' : 'w-64']">
        <div class="h-full flex flex-col">
            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1">
                <!-- Dashboard -->
                <NuxtLink
                    to="/dashboard"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors group"
                    active-class="bg-indigo-50 text-indigo-600"
                    :title="isCollapsed && !isMobile ? 'Dashboard' : ''"
                    @click="handleLinkClick">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect width="7" height="9" x="3" y="3" rx="1" />
                        <rect width="7" height="5" x="14" y="3" rx="1" />
                        <rect width="7" height="9" x="14" y="12" rx="1" />
                        <rect width="7" height="5" x="3" y="16" rx="1" />
                    </svg>
                    <span v-if="!isCollapsed || isMobile" class="font-medium">Dashboard</span>
                </NuxtLink>

                <!-- Transactions -->
                <NuxtLink
                    to="/transactions"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors group"
                    active-class="bg-indigo-50 text-indigo-600"
                    :title="isCollapsed && !isMobile ? 'Transactions' : ''"
                    @click="handleLinkClick">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" x2="8" y1="13" y2="13" />
                        <line x1="16" x2="8" y1="17" y2="17" />
                        <line x1="10" x2="8" y1="9" y2="9" />
                    </svg>
                    <span v-if="!isCollapsed || isMobile" class="font-medium">Transactions</span>
                </NuxtLink>

                <!-- Accounts -->
                <NuxtLink
                    to="/accounts"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors group"
                    active-class="bg-indigo-50 text-indigo-600"
                    :title="isCollapsed && !isMobile ? 'Accounts' : ''"
                    @click="handleLinkClick">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                        <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                        <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
                    </svg>
                    <span v-if="!isCollapsed || isMobile" class="font-medium">Accounts</span>
                    <span v-if="(!isCollapsed || isMobile) && accountsStore.activeAccountsCount > 0" class="ml-auto text-xs bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full">
                        {{ accountsStore.activeAccountsCount }}
                    </span>
                </NuxtLink>

                <!-- Analytics -->
                <NuxtLink
                    to="/analytics"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors group"
                    active-class="bg-indigo-50 text-indigo-600"
                    :title="isCollapsed && !isMobile ? 'Analytics' : ''"
                    @click="handleLinkClick">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" x2="12" y1="20" y2="10" />
                        <line x1="18" x2="18" y1="20" y2="4" />
                        <line x1="6" x2="6" y1="20" y2="16" />
                    </svg>
                    <span v-if="!isCollapsed || isMobile" class="font-medium">Analytics</span>
                </NuxtLink>

                <!-- Budgets -->
                <NuxtLink
                    to="/budgets"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors group"
                    active-class="bg-indigo-50 text-indigo-600"
                    :title="isCollapsed && !isMobile ? 'Budgets' : ''"
                    @click="handleLinkClick">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2h0V5z" />
                        <path d="M2 9v1c0 1.1.9 2 2 2h1" />
                        <path d="M16 11h0" />
                    </svg>
                    <span v-if="!isCollapsed || isMobile" class="font-medium">Budgets</span>
                    <span v-if="!isCollapsed || isMobile" class="ml-auto text-xs bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full">⚠️ 2</span>
                </NuxtLink>

                <!-- Savings Goals -->
                <NuxtLink
                    to="/goals"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors group"
                    active-class="bg-indigo-50 text-indigo-600"
                    :title="isCollapsed && !isMobile ? 'Savings Goals' : ''"
                    @click="handleLinkClick">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <circle cx="12" cy="12" r="6" />
                        <circle cx="12" cy="12" r="2" />
                    </svg>
                    <span v-if="!isCollapsed || isMobile" class="font-medium">Savings Goals</span>
                </NuxtLink>

                <!-- Categories -->
                <NuxtLink
                    to="/categories"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors group"
                    active-class="bg-indigo-50 text-indigo-600"
                    :title="isCollapsed && !isMobile ? 'Categories' : ''"
                    @click="handleLinkClick">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z" />
                        <path d="M7 7h.01" />
                    </svg>
                    <span v-if="!isCollapsed || isMobile" class="font-medium">Categories</span>
                </NuxtLink>
            </nav>

            <!-- Bottom Actions -->
            <div class="border-t border-gray-200 p-3 space-y-1">
                <!-- Settings -->
                <NuxtLink
                    to="/settings"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors group"
                    active-class="bg-gray-100"
                    :title="isCollapsed && !isMobile ? 'Settings' : ''"
                    @click="handleLinkClick">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <span v-if="!isCollapsed || isMobile" class="font-medium">Settings</span>
                </NuxtLink>

                <!-- Help -->
                <NuxtLink
                    to="/help"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors group"
                    active-class="bg-gray-100"
                    :title="isCollapsed && !isMobile ? 'Help' : ''"
                    @click="handleLinkClick">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <path d="M12 17h.01" />
                    </svg>
                    <span v-if="!isCollapsed || isMobile" class="font-medium">Help</span>
                </NuxtLink>
            </div>
        </div>
    </aside>
</template>

<script setup>
    const isOpen = ref(true);
    const isCollapsed = ref(false);
    const isMobile = ref(false);

    const accountsStore = useAccountsStore();

    const handleLinkClick = () => {
        // Close sidebar on mobile when clicking any link
        if (isMobile.value) {
            closeSidebar();
        }
    };

    const toggleCollapse = () => {
        if (!isMobile.value) {
            isCollapsed.value = !isCollapsed.value;
        }
    };

    const closeSidebar = () => {
        if (isMobile.value) {
            isOpen.value = false;
        }
    };

    const checkMobile = () => {
        isMobile.value = window.innerWidth < 1024;
        if (isMobile.value) {
            isOpen.value = false;
            isCollapsed.value = false; // Always expanded on mobile
        } else {
            isOpen.value = true;
        }
    };

    const handleToggleSidebar = () => {
        isOpen.value = !isOpen.value;
    };

    const handleCollapseSidebar = () => {
        if (!isMobile.value) {
            toggleCollapse();
        }
    };

    // Emit collapse state to layout
    watch(isCollapsed, (newValue) => {
        const event = new CustomEvent("sidebar-collapse-state", {
            detail: { collapsed: newValue },
        });
        window.dispatchEvent(event);
    });

    onMounted(async () => {
        void accountsStore.getActiveAccountsCount().catch((e) => console.debug("Sidebar count fetch failed:", e));

        checkMobile();
        window.addEventListener("resize", checkMobile);
        window.addEventListener("toggle-sidebar", handleToggleSidebar);
        window.addEventListener("collapse-sidebar", handleCollapseSidebar);
    });

    onUnmounted(() => {
        window.removeEventListener("resize", checkMobile);
        window.removeEventListener("toggle-sidebar", handleToggleSidebar);
        window.removeEventListener("collapse-sidebar", handleCollapseSidebar);
    });
</script>
