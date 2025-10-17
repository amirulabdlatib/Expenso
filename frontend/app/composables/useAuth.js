export const useAuth = () => {
    const errors = ref({});

    const { login } = useSanctumAuth();
    const sanctumClient = useSanctumClient();

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
            await sanctumClient("/register", {
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
