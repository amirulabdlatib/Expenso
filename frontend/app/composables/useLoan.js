export const useLoan = () => {
    const errors = ref({});
    const sanctumClient = useSanctumClient();

    const createLoan = async () => {
        try {
            const response = await sanctumClient("/api");
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
