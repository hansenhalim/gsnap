<template>
    <Head title="Create" />

    <FullscreenLayout>
        <div class="flex min-h-screen items-center justify-center text-white">
            <Splide
                :options="{
                    type: 'loop',
                    width: '23rem',
                    fixedHeight: '50rem',
                    drag: false,
                }"
                @splide:move="onSplideMove"
            >
                <SplideSlide v-for="frame in frames" :key="frame.id">
                    <img
                        class="m-auto h-full pb-10"
                        :src="frame.media[0].original_url"
                        :alt="frame.media[0].file_name"
                    />
                </SplideSlide>
            </Splide>
            <button
                @click="submitFrameSelection"
                class="fixed bottom-10 right-10 rounded-md bg-red-500 py-2 px-3 text-sm font-semibold text-white shadow-lg shadow-red-500/50 focus:outline-none"
            >
                Submit
            </button>
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/inertia-vue3";
import { Splide, SplideSlide } from "@splidejs/vue-splide";
import "@splidejs/vue-splide/css";

const props = defineProps({
    order: Object,
    frames: Object,
});

let selected = 0;

const onSplideMove = (splice, index) => (selected = index);

const submitFrameSelection = () => {
    Inertia.post(route("photo-papers.store"), {
        order_id: props.order.id,
        frame_id: props.frames[selected].id,
    });
};
</script>
