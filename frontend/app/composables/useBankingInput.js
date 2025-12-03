export const useBankingInput = () => {
    const createBankingInput = (initialValue = 0) => {
        const cents = ref(Math.round(initialValue * 100));

        const formatWithThousands = (value) => {
            const [whole, decimal] = value.split(".");
            const formattedWhole = whole.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return `${formattedWhole}.${decimal}`;
        };

        const displayValue = computed(() => {
            const rawValue = (cents.value / 100).toFixed(2);
            return formatWithThousands(rawValue);
        });

        const decimalValue = computed(() => {
            return cents.value / 100;
        });

        const handleInput = (event) => {
            const value = event.target.value.replace(/\D/g, "");
            cents.value = parseInt(value || "0");
        };

        const handleKeydown = (event) => {
            if (["Backspace", "Delete", "Tab", "Escape", "Enter", "ArrowLeft", "ArrowRight"].includes(event.key)) {
                return;
            }
            if (event.ctrlKey || event.metaKey) {
                return;
            }
            if (!/^\d$/.test(event.key)) {
                event.preventDefault();
            }
        };

        return {
            displayValue,
            decimalValue,
            handleInput,
            handleKeydown,
        };
    };

    return {
        createBankingInput,
    };
};
