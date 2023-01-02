<template>
    <Head title="Show" />

    <FullscreenLayout>
        <div class="flex min-h-screen items-center justify-center text-white">
            <div class="flex flex-col items-center space-y-12">
                <img class="w-48" :src="photoPaper.final_url" />
                <div class="h-20 w-20">
                    <div
                        class="absolute m-2 h-16 w-16 animate-ping rounded-full bg-red-500"
                    ></div>
                    <button
                        @click="sendToPrinter"
                        class="absolute rounded-full border-4 bg-red-500 p-4"
                    >
                        <PrinterIcon class="h-10 w-10" />
                    </button>
                </div>
            </div>
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import { PrinterIcon } from "@heroicons/vue/24/outline";
import { Head } from "@inertiajs/inertia-vue3";
import printJS from "print-js";

const props = defineProps({
    photoPaper: Object,
});

const sendToPrinter = () => {
    printJS(props.photoPaper.final_url, "image");
    return false;
};
</script>
