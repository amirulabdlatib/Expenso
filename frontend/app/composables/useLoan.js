export const useLoan = () => {
    const errors = ref({});
    const sanctumClient = useSanctumClient();

    async function getLoanAccounts() {
        try {
            let response = await sanctumClient("api/lookup/accounts");
            return response;
        } catch (error) {
            console.error("Account fetch error:", error);
            throw error;
        }
    }

    const createLoan = async (form) => {
        try {
            const response = await sanctumClient("/api/loans", {
                method: "POST",
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

    return {
        errors,
        createLoan,
        getLoanAccounts,
    };
};
