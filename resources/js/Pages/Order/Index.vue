<template>
    <AuthenticatedLayout>
        <div class="m-auto mt-16 max-w-7xl">
            <iframe
                v-show="!menuShown"
                id="myScreen"
                :src="route('orders.create')"
                class="w-full"
            ></iframe>
        </div>

        <!-- Fullscreen Button -->
        <div
            class="fixed bottom-0 right-0 flex items-center space-x-6 text-white"
        >
            <div class="animate-pulse text-2xl">
                Enter fullscreen to continue
            </div>
            <button
                v-show="menuShown"
                @click="openFullscreen"
                class="bg-gray-800 p-4 transition hover:-translate-x-1 hover:-translate-y-1 hover:scale-110"
            >
                <ArrowsPointingOutIcon class="h-10 w-10" />
            </button>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { onMounted } from "vue";
import { ArrowsPointingOutIcon } from "@heroicons/vue/24/outline";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const menuShown = ref(true);
const openFullscreen = () => myScreen.requestFullscreen();

onMounted(() => {
    document.addEventListener("fullscreenchange", () => {
        menuShown.value = !(document.fullscreenElement !== null);
    });
});
</script>
