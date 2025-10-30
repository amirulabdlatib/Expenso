export const useTransactions = () => {
    const errors = ref({});
    const sanctumClient = useSanctumClient();

    async function getTransactionAccounts() {
        try {
            let response = await sanctumClient("api/lookup/accounts");
            return response;
        } catch (error) {
            console.error("Account fetch error:", error);
            throw error;
        }
    }

    async function getTransactionCategories() {
        try {
            let response = await sanctumClient("api/lookup/categories");
            return response;
        } catch (error) {
            console.error("Category fetch error:", error);
            throw error;
        }
    }

    async function getTransactionForEdit(id) {
        try {
            const response = await sanctumClient(`/api/transactions/${id}/edit`);
            return response;
        } catch (err) {
            errors.value = err.data.errors;
            throw err;
        }
    }

    async function createTransaction(form) {
        try {
            const response = await sanctumClient("/api/transactions", {
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

    async function deleteTransaction(id) {
        try {
            const response = await sanctumClient(`/api/transactions/${id}`, {
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
        getTransactionAccounts,
        getTransactionCategories,
        createTransaction,
        deleteTransaction,
        getTransactionForEdit,
    };
};
