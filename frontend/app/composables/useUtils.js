export const useUtils = () => {
    function capitalizeWord(text) {
        if (!text) return "";
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    function formatRelativeDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();

        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);

        const inputDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());

        const timeString = date
            .toLocaleTimeString("en-MY", {
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            })
            .toLowerCase()
            .replace(" ", "");

        if (inputDate.getTime() === today.getTime()) {
            return `Today, ${timeString}`;
        } else if (inputDate.getTime() === yesterday.getTime()) {
            return `Yesterday, ${timeString}`;
        } else {
            const datePart = date
                .toLocaleDateString("en-MY", {
                    month: "short",
                    day: "numeric",
                    year: date.getFullYear() !== now.getFullYear() ? "numeric" : undefined,
                })
                .replace(",", "");
            return `${datePart}, ${timeString}`;
        }
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

    const formatMonthYearOnly = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString("en-MY", {
            month: "long",
            year: "numeric",
        });
    };

    const formatTimeAgo = (timestamp) => {
        const date = new Date(timestamp);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return "Just now";
        if (seconds < 3600) return `${Math.floor(seconds / 60)} minutes ago`;
        if (seconds < 86400) return `${Math.floor(seconds / 3600)} hours ago`;
        if (seconds < 604800) return `${Math.floor(seconds / 86400)} days ago`;
        return date.toLocaleDateString("en-MY");
    };

    return {
        capitalizeWord,
        formatDate,
        formatTime,
        formatDateTime,
        formatMonthYear,
        formatRelativeDate,
        formatMonthYearOnly,
        formatTimeAgo,
    };
};
