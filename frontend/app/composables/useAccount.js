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
            console.log(err);
            if (err.statusCode == 422) {
                errors.value = err.data.errors;
            }
            throw err;
        }
    }

    return {
        errors,
        createAccount,
    };
};
