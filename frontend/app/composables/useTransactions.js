export const useTransactions = () => {
    const errors = ref({});
    const sanctumClient = useSanctumClient();

    async function getTransactionAccounts() {
        try {
            let response = await sanctumClient("api/lookup/accounts");
            return response;
        } catch (error) {
            errors.value = error.statusCode;
            throw new Error(`Account fetch failed: ${error.message}`);
        }
    }

    async function getTransactionCategories() {}

    return {
        errors,
        getTransactionAccounts,
        getTransactionCategories,
    };
};
