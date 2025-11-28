export const useBudgets = () => {
    const sanctumClient = useSanctumClient();
    const errors = ref({});

    const getBudgetCategories = async (month = null, year = null) => {
        try {
            const response = await sanctumClient("/api/lookup/parent/categories", {
                params: { month, year },
            });
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
