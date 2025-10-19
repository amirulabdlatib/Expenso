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

    const currencies = [{ code: "MYR", name: "Malaysian Ringgit" }];

    // Color mapping for each account type
    const accountColors = {
        "Bank Account": {
            gradient: "from-indigo-500 to-indigo-600",
            icon: "bg-indigo-100",
            iconColor: "text-indigo-600",
            border: "border-indigo-600",
        },
        "Credit Card": {
            gradient: "from-purple-500 to-purple-600",
            icon: "bg-purple-100",
            iconColor: "text-purple-600",
            border: "border-purple-600",
        },
        Cash: {
            gradient: "from-green-500 to-green-600",
            icon: "bg-green-100",
            iconColor: "text-green-600",
            border: "border-green-600",
        },
        "E-Wallet": {
            gradient: "from-blue-500 to-blue-600",
            icon: "bg-blue-100",
            iconColor: "text-blue-600",
            border: "border-blue-600",
        },
        Savings: {
            gradient: "from-emerald-500 to-emerald-600",
            icon: "bg-emerald-100",
            iconColor: "text-emerald-600",
            border: "border-emerald-600",
        },
        Investment: {
            gradient: "from-orange-500 to-orange-600",
            icon: "bg-orange-100",
            iconColor: "text-orange-600",
            border: "border-orange-600",
        },
        "Emergency Fund": {
            gradient: "from-red-500 to-red-600",
            icon: "bg-red-100",
            iconColor: "text-red-600",
            border: "border-red-600",
        },
    };

    // Helper function to get colors for a specific account type
    const getAccountColors = (accountType) => {
        return (
            accountColors[accountType] || {
                gradient: "from-gray-500 to-gray-600",
                icon: "bg-gray-100",
                iconColor: "text-gray-600",
                border: "border-gray-600",
            }
        );
    };

    return {
        accountTypes,
        accountIcons,
        currencies,
        accountColors,
        getAccountColors,
    };
};
