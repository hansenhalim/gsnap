<template>
    <FullscreenLayout>
        <div
            class="flex min-h-screen w-screen items-center justify-center text-white"
        >
            <div class="flex flex-col items-center">
                <div class="flex w-[1440px] space-x-4">
                    <video id="webcam" class="w-1/2" muted autoplay />
                    <div class="flex w-1/2 flex-col justify-center space-y-4">
                        <div
                            v-for="photo in photoPaper.photos"
                            :key="photo.id"
                            class="flex space-x-2"
                        >
                            <img :src="photo.final_url" class="h-36 w-52" />
                            <Link
                                :href="route('photos.destroy', photo)"
                                method="delete"
                                as="button"
                            >
                                <XCircleIcon class="h-14 w-14"
                            /></Link>
                        </div>
                        <div
                            v-for="n in photoPaper.frame.slot_count -
                            photoPaper.photos.length"
                            :key="n"
                            class="h-36 w-52 space-x-2 bg-gray-700"
                        />
                    </div>
                </div>
                <div class="mt-16 grid h-12 grid-cols-3">
                    <div></div>
                    <div class="relative flex items-center justify-center">
                        <div
                            v-if="captureLeft > 0"
                            class="absolute m-2 h-16 w-16 animate-ping rounded-full bg-red-500"
                        ></div>
                        <button
                            :disabled="!(captureLeft > 0)"
                            @click="sendCaptureCommand"
                            class="absolute rounded-full border-4 bg-red-500 p-4 hover:bg-red-300 active:bg-red-300 disabled:bg-gray-500"
                        >
                            <CameraIcon class="h-10 w-10" />
                        </button>
                    </div>
                    <Link
                        v-if="!(captureLeft > 0)"
                        :href="route('photo-papers.update', photoPaper)"
                        method="put"
                        as="button"
                        class="flex items-center rounded-full border py-2 px-4 text-xl hover:bg-white hover:text-black active:bg-white active:text-black"
                    >
                        Continue
                        <ArrowRightIcon class="ml-1 h-8 w-8" />
                    </Link>
                </div>
            </div>
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import {
    ArrowRightIcon,
    CameraIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";
import { computed, onMounted } from "vue";

const props = defineProps({
    photoPaper: Object,
    triggerUrl: String,
    bearerToken: String,
    uploadUrl: String,
});

const captureLeft = computed(
    () => props.photoPaper.frame.slot_count - props.photoPaper.photos.length
);

const sendCaptureCommand = () => {
    const options = {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            bearer_token: props.bearerToken,
            upload_url: props.uploadUrl,
            photo_paper_id: props.photoPaper.id,
        }),
    };

    fetch(props.triggerUrl, options)
        .then(() => Inertia.reload())
        .catch((err) => console.error(err));
};

onMounted(() => {
    //Selector for your <video> element
    const video = document.getElementById("webcam");

    //Core
    window.navigator.mediaDevices
        .getUserMedia({ video: true })
        .then((stream) => {
            video.srcObject = stream;
            video.onloadedmetadata = (e) => {
                video.play();
            };
        });
});
</script>
