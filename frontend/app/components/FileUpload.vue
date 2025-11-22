<template>
    <div>
        <!-- Drop Zone -->
        <div v-show="!model" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-indigo-400 transition" @drop.prevent="onDrop" @dragover.prevent @click="triggerFileSelect">
            <p class="text-gray-500">Drag & drop a file here, or click to upload</p>
            <input ref="fileInput" type="file" class="hidden" @change="onSelect" />
        </div>

        <!-- Preview with Remove Button -->
        <div v-if="model" class="mt-4 relative">
            <!-- Remove Button -->
            <button type="button" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors z-10" title="Remove file" @click.stop="removeFile">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>

            <!-- Preview Content (Clickable) -->
            <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50 cursor-pointer hover:border-indigo-400 transition-colors" @click="openLightbox">
                <img v-if="model.preview" :src="model.preview" class="w-32 h-32 rounded-lg object-cover mx-auto" alt="Receipt preview" />
                <div v-else class="w-32 h-32 rounded-lg bg-gray-200 flex items-center justify-center mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-gray-600 mt-2 text-center text-sm">{{ model.file?.name }}</p>
                <p class="text-gray-400 text-center text-xs mt-1">{{ formatFileSize(model.file?.size) }}</p>
                <p class="text-indigo-500 text-center text-xs mt-2 font-medium">Click to view</p>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <Teleport to="body">
            <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
                <div v-if="showLightbox" class="fixed inset-0 bg-gray-400 bg-opacity-90 z-50 flex items-center justify-center p-4" @click="closeLightbox">
                    <!-- Close Button -->
                    <button class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors" title="Close (Esc)" @click="closeLightbox">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- File Info -->
                    <div class="absolute top-4 left-4 text-white">
                        <p class="text-lg font-medium">{{ model?.file?.name }}</p>
                        <p class="text-sm text-gray-300">{{ formatFileSize(model?.file?.size) }}</p>
                    </div>

                    <!-- Content Container -->
                    <div class="max-w-7xl max-h-full flex items-center justify-center" @click.stop>
                        <!-- Image Preview -->
                        <img v-if="model?.preview" :src="model.preview" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl" alt="Receipt full view" />

                        <!-- PDF or Other File Preview -->
                        <div v-else class="bg-white rounded-lg p-8 text-center shadow-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-700 text-lg font-medium mb-2">{{ model?.file?.name }}</p>
                            <p class="text-gray-500 mb-4">{{ formatFileSize(model?.file?.size) }}</p>
                            <a :href="model?.preview || '#'" download class="inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"> Download File </a>
                        </div>
                    </div>

                    <!-- Navigation Hint -->
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-sm">Press <kbd class="px-2 py-1 bg-gray-700 rounded">Esc</kbd> to close</div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
    const model = defineModel({
        type: [Object, null],
        default: null,
    });

    const fileInput = ref(null);
    const showLightbox = ref(false);

    function triggerFileSelect() {
        fileInput.value.click();
    }

    function setFile(file) {
        const preview = file.type?.startsWith("image/") ? URL.createObjectURL(file) : null;
        model.value = { file, preview };
    }

    function removeFile() {
        // Revoke the object URL to free memory
        if (model.value?.preview) {
            URL.revokeObjectURL(model.value.preview);
        }

        // Clear the file input
        if (fileInput.value) {
            fileInput.value.value = "";
        }

        // Clear the model
        model.value = null;

        // Close lightbox if open
        showLightbox.value = false;
    }

    function openLightbox() {
        showLightbox.value = true;
        // Prevent body scroll when lightbox is open
        document.body.style.overflow = "hidden";
    }

    function closeLightbox() {
        showLightbox.value = false;
        // Restore body scroll
        document.body.style.overflow = "";
    }

    function onDrop(e) {
        const dropped = e.dataTransfer.files[0];
        if (!dropped) return;
        setFile(dropped);
    }

    function onSelect(e) {
        const selected = e.target.files[0];
        if (!selected) return;
        setFile(selected);
    }

    function formatFileSize(bytes) {
        if (!bytes) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + " " + sizes[i];
    }

    // Handle Escape key to close lightbox
    function handleKeydown(e) {
        if (e.key === "Escape" && showLightbox.value) {
            closeLightbox();
        }
    }

    onMounted(() => {
        window.addEventListener("keydown", handleKeydown);
    });

    onUnmounted(() => {
        window.removeEventListener("keydown", handleKeydown);

        // Cleanup object URL
        if (model.value?.preview) {
            URL.revokeObjectURL(model.value.preview);
        }

        // Restore body scroll just in case
        document.body.style.overflow = "";
    });
</script>
