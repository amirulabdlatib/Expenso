export const useProfile = () => {
    const sanctumClient = useSanctumClient();
    const errors = ref({});

    const updateProfile = async (form) => {
        try {
            const response = await sanctumClient("/api/profile", {
                method: "PUT",
                body: form,
            });
            return response;
        } catch (err) {
            if (err.statusCode === 422) {
                errors.value = err.data.errors;
            }
            throw err;
        }
    };
    const deleteProfile = async () => {
        try {
            const response = await sanctumClient("/api/profile", {
                method: "DELETE",
            });
            return response;
        } catch (err) {
            errors.value = err.data.errors;
            throw err;
        }
    };

    return {
        errors,
        updateProfile,
        deleteProfile,
    };
};
