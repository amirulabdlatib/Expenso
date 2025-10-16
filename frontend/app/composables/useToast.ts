// composables/useToast.ts
export const useToast = () => {
    const toasts = useState<
        Array<{
            id: number;
            message: string;
            type: "success" | "error" | "info" | "warning";
        }>
    >("toasts", () => []);

    let toastId = 0;

    const showToast = (message: string, type: "success" | "error" | "info" | "warning" = "info", duration: number = 5000) => {
        const id = toastId++;
        toasts.value.push({ id, message, type });

        if (duration > 0) {
            setTimeout(() => {
                removeToast(id);
            }, duration);
        }
    };

    const removeToast = (id: number) => {
        toasts.value = toasts.value.filter((toast) => toast.id !== id);
    };

    return {
        toasts: readonly(toasts),
        showToast,
        removeToast,
        success: (message: string, duration?: number) => showToast(message, "success", duration),
        error: (message: string, duration?: number) => showToast(message, "error", duration),
        info: (message: string, duration?: number) => showToast(message, "info", duration),
        warning: (message: string, duration?: number) => showToast(message, "warning", duration),
    };
};
