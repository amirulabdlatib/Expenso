export const useUtils = () => {
    function capitalizeWord(text) {
        if (!text) return "";
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    return {
        capitalizeWord,
    };
};
