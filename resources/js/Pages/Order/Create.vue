<template>
    <Head title="Checkout" />

    <FullscreenLayout>
        <div class="flex min-h-screen items-center justify-center">
            <Link
                :href="route('orders.store')"
                method="post"
                as="button"
                class="rounded-md bg-red-500 py-2 px-3 text-sm font-semibold text-white shadow-lg shadow-red-500/50 focus:outline-none"
                >Checkout</Link
            >
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
</script>
