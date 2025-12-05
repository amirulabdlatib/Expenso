export const useAccount = () => {
    const errors = ref({});
    const sanctumClient = useSanctumClient();

    async function createAccount(form) {
        try {
            const response = await sanctumClient("/api/accounts", {
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

    async function getAccount(id) {
        try {
            const response = await sanctumClient(`/api/accounts/${id}`);
            return response;
        } catch (err) {
            errors.value = err.data.errors;
            throw err;
        }
    }

    async function updateAccount(form, id) {
        try {
            const response = await sanctumClient(`/api/accounts/${id}`, {
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

    async function deleteAccount(id) {
        try {
            const response = await sanctumClient(`/api/accounts/${id}`, {
                method: "DELETE",
            });

            return response;
        } catch (err) {
            errors.value = err.data.errors;
            throw err;
        }
    }

    return {
        errors,
        createAccount,
        getAccount,
        getActiveAccount,
        updateAccount,
        deleteAccount,
    };
};
