<template>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2"> Name</label>
                    <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input v-model="form.email" type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>

            <!-- Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input v-model="form.password" type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-100 text-gray-600 cursor-not-allowed" readonly />
                </div>
            </div>

            <div class="flex justify-end">
                <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Save Changes</button>
            </div>
        </div>
    </div>
</template>

<script setup>
    const user = useSanctumUser();

    const form = reactive({
        name: user?.value.name,
        email: user?.value.email,
        password: null,
    });

    console.log(user.value);
</script>
