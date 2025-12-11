export default defineNuxtRouteMiddleware((to, from) => {
    const { user } = useSanctumAuth();

    // Check if user is authenticated
    if (!user.value) {
        return navigateTo('/login');
    }

    // Check if user's email is verified
    if (!user.value.email_verified_at) {
        return abortNavigation({
            statusCode: 403,
            statusMessage: 'Your email address is not verified. Please verify your email to access this page.',
        });
    }
});
