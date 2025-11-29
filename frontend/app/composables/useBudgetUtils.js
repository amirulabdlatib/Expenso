export const useBudgetUtils = () => {
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    const years = Array.from({ length: 10 }, (_, i) => new Date().getFullYear() - 5 + i);

    const getDailyAverage = (budget, selectedMonth, selectedYear) => {
        const today = new Date();
        const isCurrentMonth = selectedMonth === today.getMonth() + 1 && selectedYear === today.getFullYear();

        // If viewing current month, use today's date. Otherwise, use last day of selected month
        const currentDay = isCurrentMonth ? today.getDate() : new Date(selectedYear, selectedMonth, 0).getDate();

        return budget.spent / currentDay;
    };

    const getProjectedEnd = (budget, selectedMonth, selectedYear) => {
        const today = new Date();
        const isCurrentMonth = selectedMonth === today.getMonth() + 1 && selectedYear === today.getFullYear();

        const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();
        const currentDay = isCurrentMonth ? today.getDate() : daysInMonth;

        const dailyAverage = budget.spent / currentDay;
        return dailyAverage * daysInMonth;
    };

    const getSmartStatus = (budget, selectedMonth, selectedYear) => {
        if (budget.percentage >= 100) return "over-budget";

        const today = new Date();
        const isCurrentMonth = selectedMonth === today.getMonth() + 1 && selectedYear === today.getFullYear();

        if (!isCurrentMonth) {
            return budget.percentage >= 100 ? "over-budget" : "on-track";
        }

        const currentDay = today.getDate();
        const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();
        const expectedPercentage = (currentDay / daysInMonth) * 100;

        if (budget.percentage > expectedPercentage + 10) return "warning";
        return "on-track";
    };

    const getStatusClass = (budget, selectedMonth, selectedYear) => {
        const status = getSmartStatus(budget, selectedMonth, selectedYear);
        if (status === "over-budget") return "bg-red-100 text-red-600";
        if (status === "warning") return "bg-yellow-100 text-yellow-600";
        return "bg-green-100 text-green-600";
    };

    const getStatusText = (budget, selectedMonth, selectedYear) => {
        const status = getSmartStatus(budget, selectedMonth, selectedYear);
        if (status === "over-budget") return "Over Budget";
        if (status === "warning") return "Warning";
        return "On Track";
    };

    const getProgressColor = (budget, selectedMonth, selectedYear) => {
        const status = getSmartStatus(budget, selectedMonth, selectedYear);
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
