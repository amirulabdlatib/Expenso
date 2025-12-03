<template>
    <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">
            {{ props.currency }}
        </span>

        <input
            :value="bankInput.displayValue.value"
            @input="bankInput.handleInput"
            @keydown="bankInput.handleKeydown"
            type="text"
            inputmode="numeric"
            :placeholder="props.placeholder"
            :disabled="isLoading"
            class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
    </div>
</template>

<script setup>
    const model = defineModel({ type: Number, default: 0 });
    const props = defineProps({
        currency: { type: String, default: "MYR" },
        placeholder: { type: String, default: "0.00" },
        isLoading: { type: Boolean, default: false },
    });

    const { createBankingInput } = useBankingInput();
    const bankInput = createBankingInput(model.value);

    // When composable changes → update parent
    watch(
        () => bankInput.decimalValue.value,
        (val) => (model.value = val)
    );
</script>
