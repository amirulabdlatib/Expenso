export const useTransactions = () => {
    const errors = ref(null);
    const sanctumClient = useSanctumClient();

    async function getTransactionAccounts() {
        errors.value = null;
        try {
            let response = await sanctumClient("api/lookup/accounts");
            return response;
        } catch (error) {
            errors.value = error;
            console.error("Account fetch error:", error);
            throw error;
        }
    }

    async function getTransactionCategories() {
        errors.value = null;
        try {
            let response = await sanctumClient("api/lookup/categories");
            return response;
        } catch (error) {
            errors.value = error;
            console.error("Category fetch error:", error);
            throw error;
        }
    }

    return {
        errors,
        getTransactionAccounts,
        getTransactionCategories,
    };
};
