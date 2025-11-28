export const useBudgets = () => {
    const sanctumClient = useSanctumClient();
    const errors = ref({});

    const getBudgetCategories = async () => {
        try {
            const response = await sanctumClient("/api/lookup/parent/categories");
            return response.categories;
        } catch (err) {
            errors.value = err.data.errors;
            throw err;
        }
    };

    return {
        getBudgetCategories,
        errors,
    };
};
