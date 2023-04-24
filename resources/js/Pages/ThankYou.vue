<template>
    <Head title="Show" />

    <FullscreenLayout>
        <div
            class="flex min-h-screen w-screen items-center justify-center text-white"
        >
            <p
                class="rounded-lg bg-gradient-to-l from-cyan-500 to-blue-500 py-4 px-8 text-4xl font-extrabold"
            >
                Thanks! Redirecting in {{ countdown }}s
            </p>
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/inertia-vue3";
import { onMounted, ref } from "vue";

const props = defineProps({
    timerSeconds: Number,
});

const countdown = ref(props.timerSeconds);

onMounted(() => {
    setInterval(() => countdown.value--, 1000);

    setTimeout(
        () => window.location.replace(route("orders.create")),
        countdown.value * 1000
    );
});
</script>
