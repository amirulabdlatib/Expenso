export const useAuth = () => {
    const errors = ref({});

    const { login } = useSanctumAuth();
    const sanctumFetch = useSanctumFetch();

    /**
     * Attempt to authenticate a user with the provided credentials.
     *
     * If the authentication request returns a 422 validation error, this function
     * stores the server-provided `errors` object into the `errors` ref before
     * rethrowing the error.
     *
     * @param {Object} form - Login form data (credentials) to submit.
     * @throws {Error} The error thrown by the underlying authentication call; when
     *                 `error.statusCode === 422`, `errors.value` will have been
     *                 populated with `error.data.errors` prior to rethrowing.
     */
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

    /**
     * Send registration data to the backend and capture server-side validation errors.
     *
     * Sends `form` to the /register endpoint and, if the server responds with validation
     * errors (status code 422), stores those errors in the `errors` reactive reference.
     * 
     * @param {Object} form - Registration form fields to submit as the request body.
     * @throws {*} Re-throws the original error received from the request.
     */
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