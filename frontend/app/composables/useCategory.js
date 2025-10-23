export const useCategory = () => {
    const sanctumClient = useSanctumClient();
    const errors = ref({});

    async function createCategory(form) {
        try {
            const response = await sanctumClient("/api/categories", {
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
    }

    return {
        errors,
        createCategory,
    };
};
