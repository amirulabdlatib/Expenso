import { defineStore } from "pinia";

export const useAccountsStore = defineStore("accounts", () => {
    const activeAccountsCount = ref(null);
    const errors = ref(null);
    const sanctumClient = useSanctumClient();

    async function getActiveAccountsCount() {
        try {
            const response = await sanctumClient("/api/getActiveAccountCount");
            activeAccountsCount.value = response;
            return response;
        } catch (err) {
            errors.value = err.data.errors;
            throw err;
        }
    }

    return {
        activeAccountsCount,
        errors,
        getActiveAccountsCount,
    };
});
