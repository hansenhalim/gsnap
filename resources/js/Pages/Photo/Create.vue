<template>
    <FullscreenLayout>
        <div
            class="flex min-h-screen w-screen items-center justify-center text-white"
        >
            <div class="flex flex-col items-center">
                <div class="flex w-[1600px] items-center space-x-6">
                    <div class="relative w-1/2 border">
                        <video
                            id="webcam"
                            class="relative w-full"
                            muted
                            autoplay
                        />
                        <div
                            v-if="isCountdownDisplayed"
                            class="absolute top-0 left-0 flex h-full w-full items-center justify-center bg-black/50"
                        >
                            <div class="text-9xl">{{ countdown }}</div>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="grid w-fit grid-flow-col grid-rows-3 gap-4">
                            <div
                                v-for="photo in photoPaper.photos"
                                :key="photo.id"
                                class="flex"
                            >
                                <img
                                    :src="photo.final_url"
                                    class="h-48 w-72 border"
                                />
                                <button
                                    @click="destroyPhoto(photo)"
                                    onclick="this.disabled=true"
                                >
                                    <XCircleIcon class="h-14 w-14" />
                                </button>
                            </div>
                            <div
                                v-for="n in Math.max(
                                    0,
                                    photoPaper.frame.slot_count -
                                        photoPaper.photos.length
                                )"
                                :key="n"
                                class="h-48 w-72 bg-gray-700"
                            />
                        </div>
                    </div>
                </div>
                <div class="mt-16 grid h-12 grid-cols-3">
                    <div></div>
                    <div class="relative flex items-center justify-center">
                        <div
                            v-if="captureEnabled"
                            class="absolute m-2 h-16 w-16 animate-ping rounded-full bg-red-500"
                        ></div>
                        <button
                            :disabled="!captureEnabled"
                            @click="startCapturing"
                            class="absolute rounded-full border-4 bg-red-500 p-4 hover:bg-red-300 active:bg-red-300 disabled:bg-gray-500"
                        >
                            <CameraIcon class="h-10 w-10" />
                        </button>
                    </div>
                    <Link
                        v-if="!(captureQuota > 0)"
                        :href="route('photo-papers.edit', photoPaper)"
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
import { computed, onMounted, ref } from "vue";

const props = defineProps({
    photoPaper: Object,
    triggerUrl: String,
    bearerToken: String,
    uploadUrl: String,
    timerSeconds: Number,
});

const captureEnabled = ref(true);
const isCountdownDisplayed = ref(false);
const countdown = ref(props.timerSeconds);

const captureQuota = computed(
    () => props.photoPaper.frame.slot_count - props.photoPaper.photos.length
);

var countdownInterval;

const startCapturing = () => {
    captureEnabled.value = false;
    isCountdownDisplayed.value = true;

    countdownInterval = setInterval(() => {
        countdown.value--;
        if (countdown.value <= 0) sendCaptureCommand();
    }, 1000);
};

function sendCaptureCommand() {
    resetTimer();

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
        .then(() =>
            Inertia.reload({
                onSuccess: () => {
                    if (captureQuota.value > 0) startCapturing();
                },
            })
        )
        .catch((err) => console.error(err));
}

const destroyPhoto = (photo) => {
    resetTimer();

    Inertia.delete(route("photos.destroy", photo), {
        onBefore: () => true,
        onSuccess: startCapturing,
    });
};

const resetTimer = () => {
    clearInterval(countdownInterval);
    countdown.value = props.timerSeconds;
    isCountdownDisplayed.value = false;
};

onMounted(() => {
    const video = document.getElementById("webcam");

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
