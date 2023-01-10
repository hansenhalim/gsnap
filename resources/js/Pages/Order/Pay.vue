<template>
    <Head title="Checkout" />

    <FullscreenLayout>
        <div class="flex min-h-screen items-center justify-center"></div>
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
