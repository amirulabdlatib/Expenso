export const useNameDisplay = () => {
    const { user } = useSanctumAuth();

    const nickName = computed(() => {
        if (!user.value?.name) return "";
        return user.value.name.trim().charAt(0).toUpperCase();
    });

    const capitalizedName = computed(() => {
        const name = user.value?.name;
        if (!name) return "";
        return name.trim().replace(/\b\w/g, (c) => c.toUpperCase());
    });

    return {
        nickName,
        capitalizedName,
    };
};
