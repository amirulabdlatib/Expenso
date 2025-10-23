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

    async function deleteCategory(id) {
        try {
            await sanctumClient(`api/categories/${id}`, {
                method: "DELETE",
            });
        } catch (err) {
            console.log(err);
            throw err;
        }
    }

    return {
        errors,
        createCategory,
        deleteCategory,
    };
};
