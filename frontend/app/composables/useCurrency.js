export const useCurrency = () => {
    const formatCurrency = (amount, currency = "MYR") => {
        return new Intl.NumberFormat("en-MY", {
            style: "currency",
            currency: currency,
        }).format(amount);
    };

    return {
        formatCurrency,
    };
};
