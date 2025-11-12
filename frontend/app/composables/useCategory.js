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

    async function updateCategory(form, id) {
        try {
            const response = await sanctumClient(`/api/categories/${id}`, {
                method: "PUT",
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
            await sanctumClient(`/api/categories/${id}`, {
                method: "DELETE",
            });
        } catch (err) {
            if (err.statusCode === 422) {
                const errorMessage = err?.data?.message;
                const customError = new Error(errorMessage);
                customError.statusCode = 422;
                throw customError;
            }
            throw err;
        }
    }

    return {
        errors,
        createCategory,
        updateCategory,
        deleteCategory,
    };
};
