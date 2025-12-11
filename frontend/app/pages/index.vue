<template>
    <div>
        <!-- Hero Section -->
        <Hero />

        <!-- Features Section -->
        <Features />

        <!-- How It Works Section -->
        <HowItWorks />

        <!-- Testimonials Section -->
        <Testimonials />

        <!-- FAQ Section -->
        <FAQ />

        <!-- Contact Us Section -->
        <ContactUs />

        <!-- CTA Section -->
        <CTA />
    </div>
</template>

<script setup>
    definePageMeta({
        layout: "public",
    });

    useHead({
        title: "Expenso - Track Your Financial",
        meta: [
            {
                name: "description",
                content: "Stop wondering where your money goes. Expenso helps you track every ringgit, set budgets, and reach your savings goals. 100% free, forever.",
            },
        ],
    });
</script>
