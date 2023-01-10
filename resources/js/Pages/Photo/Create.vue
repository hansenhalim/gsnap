<template>
    <FullscreenLayout>
        <div
            class="flex min-h-screen w-screen items-center justify-center text-white"
        >
            <div class="flex items-center space-x-10">
                <div class="w-40">
                    <div
                        v-show="photoPaper.photos.length > 0"
                        class="mt-2 animate-pulse bg-zinc-800 py-2 px-3 text-zinc-400"
                    >
                        Tip: Click on image to change filter
                    </div>
                </div>

                <div class="flex w-28 flex-col items-center">
                    <video id="webcam" muted autoplay></video>
                    <div v-if="captureLeft > 0">
                        <div>Capture left: {{ captureLeft }}</div>
                        <button
                            @click="sendCaptureCommand"
                            class="mt-2 w-full border p-2"
                        >
                            Capture
                        </button>
                    </div>
                    <div v-else>
                        <div>Ready to print</div>
                        <Link
                            :href="route('photo-papers.update', photoPaper)"
                            method="put"
                            as="button"
                            class="mt-2 w-full border p-2"
                        >
                            Print
                        </Link>
                    </div>
                </div>

                <div class="relative grid w-56 gap-2">
                    <div
                        v-for="photo in photoPaper.photos"
                        :key="photo.id"
                        class="flex space-x-4"
                    >
                        <Link
                            method="put"
                            as="button"
                            :href="route('photos.update', photo)"
                        >
                            <img :src="photo.final_url" />
                        </Link>
                        <Link
                            :href="route('photos.destroy', photo)"
                            method="delete"
                            as="button"
                        >
                            <XCircleIcon class="h-10 w-10"
                        /></Link>
                    </div>
                </div>
            </div>
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import { XCircleIcon } from "@heroicons/vue/24/outline";
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
