export const useAuth = () => {
    const errors = ref({});

    const { login } = useSanctumAuth();
    const sanctumFetch = useSanctumFetch();

    async function doLogin(form) {
        try {
            await login(form);
        } catch (err) {
            if (err.statusCode == 422) {
                errors.value = err.data.errors;
            }
            throw err;
        }
    }

    async function doRegister(form) {
        try {
            await sanctumFetch("/register", {
                method: "POST",
                body: form,
            });
        } catch (err) {
            if (err.statusCode == 422) {
                errors.value = err.data.errors;
            }
            throw err;
        }
    }

    return {
        errors,
        doRegister,
        doLogin,
    };
};
