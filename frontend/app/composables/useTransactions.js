export const useTransactions = () => {
    const errors = ref({});
    const sanctumClient = useSanctumClient();

    async function getTransactionAccounts() {
        try {
            let response = await sanctumClient("api/lookup/accountss");
            return response;
        } catch (error) {
            errors.value = error;
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
