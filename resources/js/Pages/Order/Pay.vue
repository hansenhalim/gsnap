<template>
    <Head title="Checkout" />

    <FullscreenLayout>
        <div class="flex min-h-screen items-center justify-center">
            <div
                class="fixed mt-10 h-56 w-56"
                style="
                    z-index: 1000000;
                    box-shadow: 0 0 0 99999px rgba(255, 255, 255, 1);
                "
            >
                <div
                    class="fixed top-0 left-0 flex w-screen flex-col items-center"
                >
                    <div
                        class="border-x-4 border-b-4 border-black px-4 pt-8 pb-2 text-center text-xl font-bold"
                    >
                        PITIBI Payment could be done by
                    </div>
                    <div
                        class="absolute -mt-52 grid h-screen grid-flow-col place-items-center gap-4"
                    >
                        <div
                            class="h-20 w-20 bg-contain bg-center"
                            style="background-image: url('/qris/1.png')"
                        />
                        <div
                            class="h-20 w-20 bg-contain bg-center"
                            style="background-image: url('/qris/2.png')"
                        />
                        <div
                            class="h-20 w-20 bg-contain bg-center"
                            style="background-image: url('/qris/3.png')"
                        />
                        <div
                            class="h-20 w-20 rounded-2xl border bg-contain bg-center"
                            style="background-image: url('/qris/4.png')"
                        />
                        <div
                            class="h-20 w-20 bg-contain bg-center"
                            style="background-image: url('/qris/5.png')"
                        />
                        <div
                            class="h-20 w-20 bg-contain bg-center"
                            style="background-image: url('/qris/6.png')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/inertia-vue3";
import { onUnmounted, onMounted } from "vue";

const props = defineProps({
    clientKey: String,
    scriptSrc: String,
    snapToken: String,
    order: Object,
});

const snapPay = () => {
    snap.pay(props.snapToken, {
        onSuccess: function (result) {
            console.log("success");
            console.log(result);
            Inertia.visit(route("orders.show", props.order.id));
        },
        onPending: function (result) {
            console.log("pending");
            console.log(result);
            window.location.reload();
        },
        onError: function (result) {
            console.log("error");
            console.log(result);
            window.location.reload();
        },
        onClose: function () {
            console.log("onClose");
            window.location.reload();
        },
    });
};

let snapScript = document.createElement("script");
snapScript.setAttribute("src", props.scriptSrc);
snapScript.setAttribute("data-client-key", props.clientKey);
document.body.appendChild(snapScript);

snapScript.addEventListener("load", snapPay);

onMounted(() => snapPay);

const timer = setInterval(() => window.location.reload(), 59 * 60 * 1000);

onUnmounted(() => clearInterval(timer));
</script>
