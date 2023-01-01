<template>
    <Head title="Show" />

    <FullscreenLayout>
        <div class="flex min-h-screen items-center justify-center text-white">
            <img class="w-48" :src="photoPaper.final_url" />
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";

defineProps({
    photoPaper: Object,
});
</script>
