export const useUtils = () => {
    function capitalizeWord(text) {
        if (!text) return "";
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    function formatRelativeDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();

        // Reset time part for date comparison
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);

        const inputDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());

        // Format time part
        const timeString = date
            .toLocaleTimeString("en-MY", {
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            })
            .toLowerCase()
            .replace(" ", "");

        // Check if date is today, yesterday, or older
        if (inputDate.getTime() === today.getTime()) {
            return `Today, ${timeString}`;
        } else if (inputDate.getTime() === yesterday.getTime()) {
            return `Yesterday, ${timeString}`;
        } else {
            // For older dates, use the full date
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

    return {
        capitalizeWord,
        formatDate,
        formatTime,
        formatDateTime,
        formatMonthYear,
        formatRelativeDate,
    };
};
