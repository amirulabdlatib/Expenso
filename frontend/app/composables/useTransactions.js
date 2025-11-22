export const useTransactions = () => {
    const errors = ref({});
    const sanctumClient = useSanctumClient();

    async function getTransactionAccounts() {
        try {
            let response = await sanctumClient("api/lookup/accounts");
            return response;
        } catch (error) {
            console.error("Account fetch error:", error);
            throw error;
        }
    }

    async function getTransactionCategories() {
        try {
            let response = await sanctumClient("api/lookup/categories");
            return response;
        } catch (error) {
            console.error("Category fetch error:", error);
            throw error;
        }
    }

    async function getTransactionForEdit(id) {
        try {
            const response = await sanctumClient(`/api/transactions/${id}/edit`);
            return response;
        } catch (err) {
            errors.value = err.data.errors;
            throw err;
        }
    }

    async function createTransaction(form) {
        try {
            const response = await sanctumClient("/api/transactions", {
                method: "POST",
                body: form,
            });
            return response;
        } catch (err) {
            if (err.statusCode == 422) {
                errors.value = err.data.errors;
            }
            throw err;
        }
    }

    async function updateTransaction(form, id) {
        try {
            if (form instanceof FormData) {
                form.append("_method", "PUT");
                const response = await sanctumClient(`/api/transactions/${id}`, {
                    method: "POST",
                    body: form,
                });
                return response;
            }

            // Otherwise use regular PUT
            const response = await sanctumClient(`/api/transactions/${id}`, {
                method: "PUT",
                body: form,
            });
            return response;
        } catch (err) {
            if (err.statusCode == 422) {
                errors.value = err.data.errors;
            }
            throw err;
        }
    }

    async function deleteTransaction(id) {
        try {
            const response = await sanctumClient(`/api/transactions/${id}`, {
                method: "DELETE",
            });

            return response;
        } catch (err) {
            errors.value = err.data.errors;
            throw err;
        }
    }

    async function loadReceipt(transactionId) {
        try {
            const response = await sanctumClient(`/api/transactions/${transactionId}/receipt`, {
                responseType: "blob",
            });

            // Get the blob from response
            const blob = response;

            // Determine file type from blob
            const mimeType = blob.type;
            let fileName = "receipt";

            if (mimeType.includes("image")) {
                fileName += mimeType.includes("png") ? ".png" : ".jpg";
            } else if (mimeType.includes("pdf")) {
                fileName += ".pdf";
            }

            // Create a File object
            const file = new File([blob], fileName, { type: mimeType });

            // Create preview URL for images
            const preview = mimeType.startsWith("image/") ? URL.createObjectURL(blob) : null;

            return { file, preview };
        } catch (err) {
            console.error("Error loading receipt:", err);
            throw err; // Re-throw to allow components to handle the error
        }
    }

    return {
        errors,
        getTransactionAccounts,
        getTransactionCategories,
        createTransaction,
        deleteTransaction,
        getTransactionForEdit,
        updateTransaction,
        loadReceipt,
    };
};
