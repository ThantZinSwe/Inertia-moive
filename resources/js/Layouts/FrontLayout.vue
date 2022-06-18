<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from "@inertiajs/inertia-vue3";
import NavBar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";

defineProps({
    title: String,
});
</script>

<template>
    <div>
        <Head :title="title" />

        <JetBanner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-800">
            <!-- Nav -->
            <NavBar />
            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
        <Footer />
    </div>
</template>
