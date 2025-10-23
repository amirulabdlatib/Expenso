export const useCategoryConstant = () => {
    const categoryTypes = [
        {
            value: "expense",
            label: "Expense",
            description: "Money going out",
            icon: "heroicons:arrow-trending-down",
            iconColor: "text-red-600",
            bgColor: "bg-red-100",
        },
        {
            value: "income",
            label: "Income",
            description: "Money coming in",
            icon: "heroicons:arrow-trending-up",
            iconColor: "text-green-600",
            bgColor: "bg-green-100",
        },
    ];

    const categoryIcons = [
        "heroicons:shopping-cart",
        "heroicons:shopping-bag",
        "heroicons:currency-dollar",
        "heroicons:home",
        "heroicons:building-office",
        "heroicons:banknotes",
        "heroicons:credit-card",
        "heroicons:gift",
        "heroicons:heart",
        "heroicons:film",
        "heroicons:musical-note",
        "heroicons:tv",
        "heroicons:device-phone-mobile",
        "heroicons:computer-desktop",
        "heroicons:wifi",
        "heroicons:bolt",
        "heroicons:light-bulb",
        "heroicons:wrench-screwdriver",
        "heroicons:truck",
        "heroicons:rocket-launch",
    ];

    const categoryColors = [
        { value: "red", class: "bg-red-500" },
        { value: "orange", class: "bg-orange-500" },
        { value: "amber", class: "bg-amber-500" },
        { value: "yellow", class: "bg-yellow-500" },
        { value: "lime", class: "bg-lime-500" },
        { value: "green", class: "bg-green-500" },
        { value: "emerald", class: "bg-emerald-500" },
        { value: "teal", class: "bg-teal-500" },
        { value: "cyan", class: "bg-cyan-500" },
        { value: "sky", class: "bg-sky-500" },
        { value: "blue", class: "bg-blue-500" },
        { value: "indigo", class: "bg-indigo-500" },
        { value: "purple", class: "bg-purple-500" },
        { value: "fuchsia", class: "bg-fuchsia-500" },
        { value: "pink", class: "bg-pink-500" },
        { value: "rose", class: "bg-rose-500" },
        { value: "gray", class: "bg-gray-500" },
        { value: "slate", class: "bg-slate-500" },
        { value: "zinc", class: "bg-zinc-500" },
    ];

    return {
        categoryTypes,
        categoryIcons,
        categoryColors,
    };
};
