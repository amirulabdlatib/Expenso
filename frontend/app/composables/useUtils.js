export const useUtils = () => {
    function capitalizeWord(text) {
        if (!text) return "";
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString("en-MY", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    }
    function formatMonthYear(dateString = null) {
        const date = dateString ? new Date(dateString) : new Date();
        return date.toLocaleDateString("en-MY", {
            month: "long",
            year: "numeric",
        });
    }

    function formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString("en-MY", {
            hour: "2-digit",
            minute: "2-digit",
        });
    }

    const formatDateTime = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleString("en-MY", {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    return {
        capitalizeWord,
        formatDate,
        formatTime,
        formatDateTime,
        formatMonthYear,
    };
};
