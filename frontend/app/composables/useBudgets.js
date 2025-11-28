export const useBudgets = () => {
    const sanctumClient = useSanctumClient();
    const errors = ref({});

    return {
        errors,
    };
};
