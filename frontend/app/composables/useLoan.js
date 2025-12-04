export const useLoan = () => {
    const errors = ref({});
    const sanctumClient = useSanctumClient();

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
    };
};
