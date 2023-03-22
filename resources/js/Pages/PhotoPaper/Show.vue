<template>
    <Head title="Show" />

    <FullscreenLayout>
        <div
            class="flex min-h-screen w-screen items-center justify-center text-white"
        >
            <div class="flex flex-col items-center space-y-12">
                <img class="h-[40rem]" :src="photoPaper.final_url" />
                <div class="mt-16 grid h-12 grid-cols-3">
                    <button
                        onclick="window.history.back()"
                        class="flex items-center rounded-full border py-2 px-4 text-xl hover:bg-white hover:text-black active:bg-white active:text-black"
                    >
                        <ArrowLeftIcon class="mr-1 h-8 w-8" />
                        Return
                    </button>
                    <div class="relative flex items-center justify-center">
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
                    <div></div>
                </div>
            </div>
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import { ArrowLeftIcon, PrinterIcon } from "@heroicons/vue/24/outline";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/inertia-vue3";
import printJS from "print-js";

const props = defineProps({
    photoPaper: Object,
});

const sendToPrinter = () => {
    printJS(props.photoPaper.final_url, "image");
    Inertia.visit(route("thank-you"));
};
</script>
