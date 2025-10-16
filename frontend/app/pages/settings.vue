<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
                <p class="text-gray-600 mt-2">Manage your account preferences and application settings</p>
            </div>

            <!-- Settings Sections -->
            <div class="space-y-6">
                <!-- Profile Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:user-circle" class="w-6 h-6 text-indigo-600" />
                            <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Profile Picture -->
                        <div class="flex items-center space-x-6">
                            <div class="w-24 h-24 bg-indigo-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">AS</div>
                            <div class="flex-1">
                                <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">Change Photo</button>
                                <button class="ml-3 px-4 py-2 text-red-600 text-sm font-medium hover:bg-red-50 rounded-lg transition-colors">Remove</button>
                                <p class="text-xs text-gray-500 mt-2">JPG, GIF or PNG. Max size of 2MB</p>
                            </div>
                        </div>

                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input v-model="profile.firstName" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input v-model="profile.lastName" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                            </div>
                        </div>

                        <!-- Email & Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input v-model="profile.email" type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input v-model="profile.phone" type="tel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Save Changes</button>
                        </div>
                    </div>
                </div>

                <!-- Currency & Language -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:globe-alt" class="w-6 h-6 text-indigo-600" />
                            <h2 class="text-xl font-semibold text-gray-900">Regional Settings</h2>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                                <select v-model="settings.currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="MYR">Malaysian Ringgit (MYR)</option>
                                    <option value="USD">US Dollar (USD)</option>
                                    <option value="EUR">Euro (EUR)</option>
                                    <option value="GBP">British Pound (GBP)</option>
                                    <option value="SGD">Singapore Dollar (SGD)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                                <select v-model="settings.language" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="en">English</option>
                                    <option value="ms">Bahasa Malaysia</option>
                                    <option value="zh">中文</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                                <select v-model="settings.dateFormat" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                                    <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                                    <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                <select v-model="settings.timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="Asia/Kuala_Lumpur">Malaysia (GMT+8)</option>
                                    <option value="Asia/Singapore">Singapore (GMT+8)</option>
                                    <option value="Asia/Bangkok">Thailand (GMT+7)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Save Changes</button>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:bell" class="w-6 h-6 text-indigo-600" />
                            <h2 class="text-xl font-semibold text-gray-900">Notifications</h2>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-3">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Email Notifications</p>
                                <p class="text-xs text-gray-500 mt-1">Receive email about your account activity</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="notifications.email" type="checkbox" class="sr-only peer" />
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between py-3 border-t border-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Budget Alerts</p>
                                <p class="text-xs text-gray-500 mt-1">Get notified when you reach 80% of your budget</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="notifications.budgetAlerts" type="checkbox" class="sr-only peer" />
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between py-3 border-t border-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Goal Milestones</p>
                                <p class="text-xs text-gray-500 mt-1">Celebrate when you reach savings milestones</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="notifications.goalMilestones" type="checkbox" class="sr-only peer" />
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between py-3 border-t border-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Transaction Reminders</p>
                                <p class="text-xs text-gray-500 mt-1">Daily reminders to log your transactions</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="notifications.transactionReminders" type="checkbox" class="sr-only peer" />
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between py-3 border-t border-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Weekly Reports</p>
                                <p class="text-xs text-gray-500 mt-1">Receive weekly summary of your finances</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="notifications.weeklyReports" type="checkbox" class="sr-only peer" />
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:shield-check" class="w-6 h-6 text-indigo-600" />
                            <h2 class="text-xl font-semibold text-gray-900">Security</h2>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-3">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Change Password</p>
                                <p class="text-xs text-gray-500 mt-1">Update your password regularly for security</p>
                            </div>
                            <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">Change</button>
                        </div>

                        <div class="flex items-center justify-between py-3 border-t border-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Two-Factor Authentication</p>
                                <p class="text-xs text-gray-500 mt-1">Add an extra layer of security to your account</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="security.twoFactor" type="checkbox" class="sr-only peer" />
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between py-3 border-t border-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Active Sessions</p>
                                <p class="text-xs text-gray-500 mt-1">Manage devices where you're logged in</p>
                            </div>
                            <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">Manage</button>
                        </div>
                    </div>
                </div>

                <!-- Privacy -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:lock-closed" class="w-6 h-6 text-indigo-600" />
                            <h2 class="text-xl font-semibold text-gray-900">Privacy</h2>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-3">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Data Sharing</p>
                                <p class="text-xs text-gray-500 mt-1">Allow anonymous usage data to improve the app</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="privacy.dataSharing" type="checkbox" class="sr-only peer" />
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between py-3 border-t border-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Download Your Data</p>
                                <p class="text-xs text-gray-500 mt-1">Export all your financial data</p>
                            </div>
                            <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium flex items-center space-x-2">
                                <Icon name="heroicons:arrow-down-tray" class="w-4 h-4" />
                                <span>Export</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-white rounded-lg shadow-sm border border-red-200 overflow-hidden">
                    <div class="p-6 border-b border-red-200 bg-red-50">
                        <div class="flex items-center space-x-3">
                            <Icon name="heroicons:exclamation-triangle" class="w-6 h-6 text-red-600" />
                            <h2 class="text-xl font-semibold text-red-900">Danger Zone</h2>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-3">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Delete All Data</p>
                                <p class="text-xs text-gray-500 mt-1">Permanently delete all transactions, budgets, and goals</p>
                            </div>
                            <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">Delete Data</button>
                        </div>

                        <div class="flex items-center justify-between py-3 border-t border-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Delete Account</p>
                                <p class="text-xs text-gray-500 mt-1">Permanently delete your account and all associated data</p>
                            </div>
                            <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">Delete Account</button>
                        </div>
                    </div>
                </div>

                <!-- App Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Expenso Version</p>
                            <p class="text-xs text-gray-500 mt-1">v1.0.0 - Latest</p>
                        </div>
                        <div class="flex items-center space-x-4 text-sm">
                            <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Privacy Policy</a>
                            <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Terms of Service</a>
                            <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Help Center</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Settings - Expenso",
    });

    // Profile Data
    const profile = ref({
        firstName: "Ahmad",
        lastName: "Syafiq",
        email: "ahmad@email.com",
        phone: "+60123456789",
    });

    // Settings Data
    const settings = ref({
        currency: "MYR",
        language: "en",
        dateFormat: "DD/MM/YYYY",
        timezone: "Asia/Kuala_Lumpur",
    });

    // Notifications Data
    const notifications = ref({
        email: true,
        budgetAlerts: true,
        goalMilestones: true,
        transactionReminders: false,
        weeklyReports: true,
    });

    // Security Data
    const security = ref({
        twoFactor: false,
    });

    // Privacy Data
    const privacy = ref({
        dataSharing: true,
    });
</script>
