<template>
    <Head title="Create" />

    <FullscreenLayout>
        <div
            class="flex min-h-screen w-screen flex-col items-center justify-center text-white"
        >
            <div v-show="!preview" class="text-3xl">
                Silahkan pilih filter yang anda inginkan
            </div>
            <Splide
                v-show="!preview"
                class="mt-4"
                :options="thumbsOptions"
                @splide:click="preview = true"
                ref="thumbs"
            >
                <SplideSlide
                    v-for="filteredPhoto in filteredPhotos"
                    :key="filteredPhoto.filter"
                >
                    <img
                        :src="filteredPhoto.original_url"
                        :alt="filteredPhoto.file_name"
                    />
                </SplideSlide>
            </Splide>

            <div class="fixed flex w-screen justify-center">
                <div
                    class="flex w-fit flex-col items-center space-y-6"
                    v-show="preview"
                >
                    <Splide
                        :options="mainOptions"
                        @splide:move="onSplideMove"
                        ref="main"
                    >
                        <SplideSlide
                            v-for="filteredPhoto in filteredPhotos"
                            :key="filteredPhoto.filter"
                        >
                            <img
                                class="w-[60rem]"
                                :src="filteredPhoto.original_url"
                                :alt="filteredPhoto.file_name"
                            />
                        </SplideSlide>
                    </Splide>
                    <div
                        v-show="!isOnProgress"
                        class="flex w-full justify-evenly"
                    >
                        <div class="h-20 w-20">
                            <button
                                @click="preview = false"
                                class="rounded-full border-4 bg-red-500 p-4 hover:bg-red-300 active:bg-red-300"
                            >
                                <XMarkIcon class="h-10 w-10" />
                            </button>
                        </div>
                        <div class="h-20 w-20">
                            <button
                                @click="submitFilterSelection"
                                class="absolute rounded-full border-4 bg-green-500 p-4 hover:bg-green-300 active:bg-green-300"
                            >
                                <CheckIcon class="h-10 w-10" />
                            </button>
                        </div>
                    </div>
                    <div v-show="isOnProgress" class="h-20 text-lg text-white">
                        Processing... Please wait
                    </div>
                </div>
            </div>
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import { CheckIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/inertia-vue3";
import { Splide, SplideSlide } from "@splidejs/vue-splide";
import "@splidejs/vue-splide/css";
import { onMounted, ref } from "vue";

const props = defineProps({
    photoPaper: Object,
    filteredPhotos: Object,
});

const isOnProgress = ref(false);

let selected = 0;

const onSplideMove = (splice, index) => (selected = index);

const submitFilterSelection = () => {
    Inertia.put(
        route("photo-papers.update", props.photoPaper),
        {
            filter: props.filteredPhotos[selected].filter,
        },
        {
            onBefore: () => (isOnProgress.value = true),
        }
    );
};

const preview = ref(false);

const main = ref();
const thumbs = ref();

const mainOptions = {
    type: "fade",
    width: "45rem",
    drag: false,
    arrows: false,
    pagination: false,
    speed: -1,
};

const thumbsOptions = {
    gap: "1.5rem",
    fixedWidth: 450,
    fixedHeight: 300,
    isNavigation: true,
    arrows: false,
    cover: true,
};

onMounted(() => {
    const thumbsSplide = thumbs.value?.splide;
    if (thumbsSplide) {
        main.value?.sync(thumbsSplide);
    }
});
</script>
