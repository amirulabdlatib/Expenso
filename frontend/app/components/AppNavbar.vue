<template>
    <nav class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-200 z-50">
        <div class="h-full px-4 flex items-center justify-between">
            <!-- Left: Logo + Menu Toggle + Collapse Toggle (Desktop) -->
            <div class="flex items-center space-x-4">
                <!-- Logo -->
                <NuxtLink to="/dashboard" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">E</span>
                    </div>
                    <span v-if="!isCollapsed" class="text-xl font-bold text-gray-900 hidden sm:block">Expenso</span>
                </NuxtLink>

                <!-- Hamburger Menu (Mobile only) -->
                <button class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" @click="toggleSidebar">
                    <Icon name="heroicons:bars-3" class="w-6 h-6 text-gray-600" />
                </button>

                <!-- Collapse Toggle (Desktop only) -->
                <button class="hidden lg:block p-2 rounded-lg hover:bg-gray-100 transition-colors" @click="toggleCollapse">
                    <Icon :name="isCollapsed ? 'heroicons:chevron-right' : 'heroicons:chevron-left'" class="w-5 h-5 text-gray-600 transition-transform" />
                </button>
            </div>

            <!-- Center: Search Bar (Hidden on mobile) -->
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <div class="relative w-full">
                    <input type="text" placeholder="Search transactions..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center space-x-2">
                <!-- Quick Add Button -->
                <div class="relative">
                    <button class="hidden sm:flex items-center space-x-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm" @click="toggleQuickAdd">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span class="font-medium">Add</span>
                    </button>

                    <!-- Mobile Add Button -->
                    <button class="sm:hidden p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors" @click="toggleQuickAdd">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>

                    <!-- Quick Add Dropdown -->
                    <div v-if="showQuickAdd" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3" @click="closeAllDropdowns">
                            <Icon name="heroicons:arrow-trending-down" class="w-5 h-5 text-green-500" />
                            <NuxtLink to="/transactions/create" class="text-gray-700">Add Transaction</NuxtLink>
                        </button>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3" @click="closeAllDropdowns">
                            <Icon name="heroicons:arrow-trending-up" class="w-5 h-5 text-red-500" />
                            <NuxtLink to="/loans/create" class="text-gray-700">Add Loan</NuxtLink>
                        </button>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3" @click="closeAllDropdowns">
                            <Icon name="heroicons:arrow-path" class="w-5 h-5 text-blue-500" />
                            <NuxtLink to="/accounts/create" class="text-gray-700">Add Account</NuxtLink>
                        </button>
                        <hr class="my-2 border-gray-200" />
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3" @click="closeAllDropdowns">
                            <Icon name="heroicons:building-library" class="w-5 h-5 text-indigo-500" />
                            <NuxtLink to="/budgets/create" class="text-gray-700">Add Budget</NuxtLink>
                        </button>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3" @click="closeAllDropdowns">
                            <Icon name="heroicons:flag" class="w-5 h-5 text-purple-500" />
                            <NuxtLink to="/categories/create" class="text-gray-700">Add Categories</NuxtLink>
                        </button>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="relative">
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors" @click="toggleNotifications">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full" />
                    </button>

                    <!-- Notifications Dropdown -->
                    <div v-if="showNotifications" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Notifications</h3>
                            <button class="text-sm text-indigo-600 hover:text-indigo-700" @click="closeAllDropdowns">Mark all read</button>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <div class="p-4 hover:bg-gray-50 transition-colors border-b border-gray-100 cursor-pointer" @click="closeAllDropdowns">
                                <div class="flex items-start space-x-3">
                                    <Icon name="heroicons:exclamation-triangle" class="w-6 h-6 text-yellow-500" />
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Budget Alert</p>
                                        <p class="text-sm text-gray-600 mt-1">You've spent 90% of your food budget</p>
                                        <p class="text-xs text-gray-400 mt-1">2 mins ago</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-gray-50 transition-colors border-b border-gray-100 cursor-pointer" @click="closeAllDropdowns">
                                <div class="flex items-start space-x-3">
                                    <Icon name="heroicons:trophy" class="w-6 h-6 text-green-500" />
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Goal Milestone</p>
                                        <p class="text-sm text-gray-600 mt-1">You've reached 50% of your vacation savings!</p>
                                        <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 text-center border-t border-gray-200">
                            <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium" @click="closeAllDropdowns">
                                View All Notifications
                                <Icon name="heroicons:arrow-right" class="w-4 h-4 inline ml-1" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="relative">
                    <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors" @click="toggleUserMenu">
                        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ nickName }}</span>
                        </div>
                    </button>

                    <!-- User Menu Dropdown -->
                    <div v-if="showUserMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm font-semibold text-gray-900">{{ capitalizedName }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ user?.email }}</p>
                        </div>
                        <NuxtLink to="/dashboard" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors" @click="closeAllDropdowns">
                            <Icon name="heroicons:home" class="w-5 h-5" />
                            <span>Dashboard</span>
                        </NuxtLink>
                        <NuxtLink to="/profile" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors" @click="closeAllDropdowns">
                            <Icon name="heroicons:user" class="w-5 h-5" />
                            <span>View Profile</span>
                        </NuxtLink>
                        <NuxtLink to="/settings" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors" @click="closeAllDropdowns">
                            <Icon name="heroicons:cog-6-tooth" class="w-5 h-5" />
                            <span>Settings</span>
                        </NuxtLink>
                        <hr class="my-2 border-gray-200" />
                        <button class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors" @click="doLogout">
                            <Icon name="heroicons:arrow-right-on-rectangle" class="w-5 h-5" />
                            <span>Logout</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
    const { user } = useSanctumAuth();
    const { capitalizedName, nickName } = useNameDisplay();
    const showQuickAdd = ref(false);
    const showNotifications = ref(false);
    const showUserMenu = ref(false);
    const isCollapsed = ref(false);

    const closeAllDropdowns = () => {
        showQuickAdd.value = false;
        showNotifications.value = false;
        showUserMenu.value = false;
    };

    const toggleSidebar = () => {
        const event = new CustomEvent("toggle-sidebar");
        window.dispatchEvent(event);
    };

    const toggleCollapse = () => {
        const event = new CustomEvent("collapse-sidebar");
        window.dispatchEvent(event);
    };

    const toggleQuickAdd = () => {
        showQuickAdd.value = !showQuickAdd.value;
        showNotifications.value = false;
        showUserMenu.value = false;
    };

    const toggleNotifications = () => {
        showNotifications.value = !showNotifications.value;
        showQuickAdd.value = false;
        showUserMenu.value = false;
    };

    const toggleUserMenu = () => {
        showUserMenu.value = !showUserMenu.value;
        showQuickAdd.value = false;
        showNotifications.value = false;
    };

    const doLogout = async () => {
        const { success, error } = useToast();
        const { logout: logoutAction } = useSanctumAuth();

        try {
            await logoutAction();
            success("Logged out successfully!");
        } catch (err) {
            console.log(err);
            error("Something wrong happen!");
        }
    };

    const handleClickOutside = (event) => {
        if (!event.target.closest(".relative")) {
            closeAllDropdowns();
        }
    };

    const handleCollapseState = (event) => {
        isCollapsed.value = event.detail?.collapsed || false;
    };

    onMounted(() => {
        document.addEventListener("click", handleClickOutside);
        window.addEventListener("sidebar-collapse-state", handleCollapseState);
    });

    onUnmounted(() => {
        document.removeEventListener("click", handleClickOutside);
        window.removeEventListener("sidebar-collapse-state", handleCollapseState);
    });
</script>
