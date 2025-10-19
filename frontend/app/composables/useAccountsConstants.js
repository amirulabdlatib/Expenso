export const useAccountConstants = () => {
    const accountTypes = [
        { label: "Bank Account", value: "Bank Account", icon: "heroicons:building-library" },
        { label: "Credit Card", value: "Credit Card", icon: "heroicons:credit-card" },
        { label: "Cash", value: "Cash", icon: "heroicons:banknotes" },
        { label: "E-Wallet", value: "E-Wallet", icon: "heroicons:device-phone-mobile" },
        { label: "Savings", value: "Savings", icon: "heroicons:shield-check" },
        { label: "Investment", value: "Investment", icon: "heroicons:chart-bar-square" },
        { label: "Emergency Fund", value: "Emergency Fund", icon: "heroicons:lifebuoy" },
    ];

    const accountIcons = [
        "heroicons:building-library",
        "heroicons:credit-card",
        "heroicons:banknotes",
        "heroicons:device-phone-mobile",
        "heroicons:shield-check",
        "heroicons:chart-bar-square",
        "heroicons:lifebuoy",
        "heroicons:wallet",
        "heroicons:currency-dollar",
        "heroicons:building-office",
        "heroicons:receipt-percent",
        "heroicons:document-currency-dollar",
        "heroicons:presentation-chart-line",
        "heroicons:chart-pie",
        "heroicons:calculator",
        "heroicons:academic-cap",
    ];

    const currencies = [
        { code: "MYR", name: "Malaysian Ringgit" },
        // { code: "USD", name: "US Dollar" },
        // { code: "SGD", name: "Singapore Dollar" },
        // { code: "EUR", name: "Euro" },
        // { code: "GBP", name: "British Pound" },
        // { code: "JPY", name: "Japanese Yen" },
    ];

    return {
        accountTypes,
        accountIcons,
        currencies,
    };
};
