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
        { value: "red", class: "bg-red-500", bgClass: "bg-red-100", textClass: "text-red-600" },
        { value: "orange", class: "bg-orange-500", bgClass: "bg-orange-100", textClass: "text-orange-600" },
        { value: "amber", class: "bg-amber-500", bgClass: "bg-amber-100", textClass: "text-amber-600" },
        { value: "yellow", class: "bg-yellow-500", bgClass: "bg-yellow-100", textClass: "text-yellow-600" },
        { value: "lime", class: "bg-lime-500", bgClass: "bg-lime-100", textClass: "text-lime-600" },
        { value: "green", class: "bg-green-500", bgClass: "bg-green-100", textClass: "text-green-600" },
        { value: "emerald", class: "bg-emerald-500", bgClass: "bg-emerald-100", textClass: "text-emerald-600" },
        { value: "teal", class: "bg-teal-500", bgClass: "bg-teal-100", textClass: "text-teal-600" },
        { value: "cyan", class: "bg-cyan-500", bgClass: "bg-cyan-100", textClass: "text-cyan-600" },
        { value: "sky", class: "bg-sky-500", bgClass: "bg-sky-100", textClass: "text-sky-600" },
        { value: "blue", class: "bg-blue-500", bgClass: "bg-blue-100", textClass: "text-blue-600" },
        { value: "indigo", class: "bg-indigo-500", bgClass: "bg-indigo-100", textClass: "text-indigo-600" },
        { value: "purple", class: "bg-purple-500", bgClass: "bg-purple-100", textClass: "text-purple-600" },
        { value: "fuchsia", class: "bg-fuchsia-500", bgClass: "bg-fuchsia-100", textClass: "text-fuchsia-600" },
        { value: "pink", class: "bg-pink-500", bgClass: "bg-pink-100", textClass: "text-pink-600" },
        { value: "rose", class: "bg-rose-500", bgClass: "bg-rose-100", textClass: "text-rose-600" },
        { value: "gray", class: "bg-gray-500", bgClass: "bg-gray-100", textClass: "text-gray-600" },
        { value: "slate", class: "bg-slate-500", bgClass: "bg-slate-100", textClass: "text-slate-600" },
        { value: "zinc", class: "bg-zinc-500", bgClass: "bg-zinc-100", textClass: "text-zinc-600" },
    ];

    const getColorClasses = (colorValue) => {
        const color = categoryColors.find((c) => c.value === colorValue);
        return color || categoryColors[0];
    };

    return {
        categoryTypes,
        categoryIcons,
        categoryColors,
        getColorClasses,
    };
};
