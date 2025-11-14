export const useActivity = () => {
    const getActivityIcon = (type) => {
        const icons = {
            expense: "heroicons:arrow-trending-down",
            income: "heroicons:arrow-trending-up",
            budget: "heroicons:chart-bar",
            goal: "heroicons:flag",
        };
        return icons[type] || "heroicons:document-text";
    };

    const getActivityColor = (type) => {
        const colors = {
            expense: "bg-red-500",
            income: "bg-green-500",
            budget: "bg-indigo-500",
            goal: "bg-purple-500",
        };
        return colors[type] || "bg-gray-500";
    };

    return {
        getActivityColor,
        getActivityIcon,
    };
};
