import tailwindcss from "@tailwindcss/vite";

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: "2025-07-15",
    devtools: { enabled: true },
    modules: ["nuxt-auth-sanctum", "@nuxtjs/google-fonts", "@nuxt/icon", "@nuxt/eslint", "@pinia/nuxt"],
    css: ["~/assets/css/main.css", "vue-select/dist/vue-select.css"],

    vite: {
        plugins: [tailwindcss()],
    },

    sanctum: {
        baseUrl: process.env.NUXT_BACKEND_API_URL,
        redirect: {
            onLogin: "/dashboard",
            onLogout: "/",
            onAuthOnly: "/login",
            onGuestOnly: "/dashboard",
        },
    },

    googleFonts: {
        families: {
            "DM Sans": [400, 500, 600, 700],
        },
        display: "swap",
    },
});
