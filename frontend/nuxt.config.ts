// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: "2025-07-15",
    devtools: { enabled: true },
    modules: ["nuxt-auth-sanctum"],

    sanctum: {
        baseUrl: process.env.NUXT_BACKEND_API_URL,
    },
});
