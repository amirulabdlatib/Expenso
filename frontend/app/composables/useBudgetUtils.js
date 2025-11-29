export const useBudgetUtils = () => {
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    const years = Array.from({ length: 10 }, (_, i) => new Date().getFullYear() - 5 + i);

    const getDailyAverage = (budget) => {
        const today = new Date();
        const currentDay = today.getDate();
        return budget.spent / currentDay;
    };

    const getProjectedEnd = (budget) => {
        const today = new Date();
        const currentDay = today.getDate();
        const daysInMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();
        const dailyAverage = budget.spent / currentDay;
        return dailyAverage * daysInMonth;
    };

    const getSmartStatus = (budget) => {
        if (budget.percentage >= 100) return "over-budget";

        const today = new Date();
        const currentDay = today.getDate();
        const daysInMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();
        const expectedPercentage = (currentDay / daysInMonth) * 100;

        // If spending is more than 10% ahead of schedule
        if (budget.percentage > expectedPercentage + 10) return "warning";

        return "on-track";
    };

    const getStatusClass = (budget) => {
        const status = getSmartStatus(budget);
        if (status === "over-budget") return "bg-red-100 text-red-600";
        if (status === "warning") return "bg-yellow-100 text-yellow-600";
        return "bg-green-100 text-green-600";
    };

    const getStatusText = (budget) => {
        const status = getSmartStatus(budget);
        if (status === "over-budget") return "Over Budget";
        if (status === "warning") return "Warning";
        return "On Track";
    };

    const getProgressColor = (budget) => {
        const status = getSmartStatus(budget);
        if (status === "over-budget") return "bg-red-500";
        if (status === "warning") return "bg-yellow-500";
        return "bg-green-500";
    };

    return {
        getDailyAverage,
        getProjectedEnd,
        getSmartStatus,
        getStatusClass,
        getStatusText,
        getProgressColor,
        months,
        years,
    };
};
