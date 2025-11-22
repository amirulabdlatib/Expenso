<template>
    <div>
        <!-- Drop Zone -->
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-indigo-400 transition" @drop.prevent="onDrop" @dragover.prevent @click="triggerFileSelect">
            <p class="text-gray-500">Drag & drop a file here, or click to upload</p>

            <input ref="fileInput" type="file" class="hidden" @change="onSelect" />
        </div>

        <!-- Preview -->
        <div v-if="model" class="mt-4">
            <img v-if="model.preview" :src="model.preview" class="w-32 h-32 rounded-lg object-cover" />
            <p class="text-gray-600 mt-2">{{ model.file?.name }}</p>
        </div>
    </div>
</template>

<script setup>
    const model = defineModel({
        type: [Object, null],
        default: null,
    });

    const fileInput = ref(null);

    function triggerFileSelect() {
        fileInput.value.click();
    }

    function setFile(file) {
        const preview = file.type?.startsWith("image/") ? URL.createObjectURL(file) : null;

        model.value = { file, preview };
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
</script>
