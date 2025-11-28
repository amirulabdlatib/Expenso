export const useBudget = () => {
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

    const createBudget = async (form) => {
        try {
            const response = await sanctumClient("/api/budgets", {
                method: "POST",
                body: form,
            });
            return response;
        } catch (err) {
            if (err.statusCode == 422) {
                errors.value = err.data.errors;
            }
            throw err;
        }
    };

    return {
        getBudgetCategories,
        createBudget,
        errors,
    };
};
