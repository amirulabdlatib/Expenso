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
                <button @click="toggleSidebar" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12h18" />
                        <path d="M3 6h18" />
                        <path d="M3 18h18" />
                    </svg>
                </button>

                <!-- Collapse Toggle (Desktop only) -->
                <button @click="toggleCollapse" class="hidden lg:block p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path v-if="!isCollapsed" d="m15 18-6-6 6-6" />
                        <path v-else d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </div>

            <!-- Center: Search Bar (Hidden on mobile) -->
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <div class="relative w-full">
                    <input type="text" placeholder="Search transactions..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center space-x-2">
                <!-- Quick Add Button -->
                <div class="relative">
                    <button @click="toggleQuickAdd" class="hidden sm:flex items-center space-x-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        <span class="font-medium">Add</span>
                    </button>

                    <!-- Mobile Add Button -->
                    <button @click="toggleQuickAdd" class="sm:hidden p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                    </button>

                    <!-- Quick Add Dropdown -->
                    <div v-if="showQuickAdd" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3">
                            <span class="text-2xl">💸</span>
                            <span class="text-gray-700">Add Expense</span>
                        </button>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3">
                            <span class="text-2xl">💰</span>
                            <span class="text-gray-700">Add Income</span>
                        </button>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3">
                            <span class="text-2xl">🔄</span>
                            <span class="text-gray-700">Transfer Money</span>
                        </button>
                        <hr class="my-2 border-gray-200" />
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3">
                            <span class="text-2xl">🏦</span>
                            <span class="text-gray-700">Add Account</span>
                        </button>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3">
                            <span class="text-2xl">🎯</span>
                            <span class="text-gray-700">Create Goal</span>
                        </button>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="relative">
                    <button @click="toggleNotifications" class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div v-if="showNotifications" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Notifications</h3>
                            <button class="text-sm text-indigo-600 hover:text-indigo-700">Mark all read</button>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <div class="p-4 hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <div class="flex items-start space-x-3">
                                    <span class="text-2xl">⚠️</span>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Budget Alert</p>
                                        <p class="text-sm text-gray-600 mt-1">You've spent 90% of your food budget</p>
                                        <p class="text-xs text-gray-400 mt-1">2 mins ago</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <div class="flex items-start space-x-3">
                                    <span class="text-2xl">🎯</span>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Goal Milestone</p>
                                        <p class="text-sm text-gray-600 mt-1">You've reached 50% of your vacation savings!</p>
                                        <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 text-center border-t border-gray-200">
                            <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All Notifications →</button>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="relative">
                    <button @click="toggleUserMenu" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">AS</span>
                        </div>
                        <span class="hidden md:block text-sm font-medium text-gray-700">Ahmad</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="hidden md:block w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <!-- User Menu Dropdown -->
                    <div v-if="showUserMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm font-semibold text-gray-900">Ahmad Syafiq</p>
                            <p class="text-xs text-gray-500 mt-1">ahmad@email.com</p>
                        </div>
                        <NuxtLink to="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"> 👁️ View Profile </NuxtLink>
                        <NuxtLink to="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"> ⚙️ Settings </NuxtLink>
                        <NuxtLink to="/appearance" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"> 🎨 Appearance </NuxtLink>
                        <hr class="my-2 border-gray-200" />
                        <button @click="logout" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">🚪 Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
    import { ref, onMounted, onUnmounted } from "vue";

    const showQuickAdd = ref(false);
    const showNotifications = ref(false);
    const showUserMenu = ref(false);
    const isCollapsed = ref(false);

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

    const logout = () => {
        console.log("Logging out...");
        navigateTo("/");
    };

    const handleClickOutside = (event) => {
        if (!event.target.closest(".relative")) {
            showQuickAdd.value = false;
            showNotifications.value = false;
            showUserMenu.value = false;
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
