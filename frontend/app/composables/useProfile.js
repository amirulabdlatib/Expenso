export const useProfile = () => {
    const sanctumClient = useSanctumClient();
    const errors = ref({});

    const updateProfile = (form) => {
        try {
            const response = sanctumClient("/api/profile", {
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
    const deleteData = () => {};
    const deleteProfile = () => {};

    return {
        errors,
        updateProfile,
        deleteProfile,
        deleteData,
    };
};
