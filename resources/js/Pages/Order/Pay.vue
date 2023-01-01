<template>
    <Head title="Checkout" />

    <FullscreenLayout>
        <div class="flex min-h-screen items-center justify-center">
            <button @click="pay" class="bg-white p-4 shadow">Pay</button>
        </div>
    </FullscreenLayout>
</template>

<script setup>
import FullscreenLayout from "@/Layouts/FullscreenLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/inertia-vue3";
import { onMounted } from "vue";

const props = defineProps({
    clientKey: String,
    scriptSrc: String,
    snapToken: String,
    order: Object,
});

onMounted(() => {
    let snapScript = document.createElement("script");
    snapScript.setAttribute("src", props.scriptSrc);
    snapScript.setAttribute("data-client-key", props.clientKey);
    document.body.appendChild(snapScript);
});

const pay = () => {
    snap.pay(props.snapToken, {
        onSuccess: function (result) {
            // console.log(result);
            Inertia.visit(route("orders.show", [props.order.id]));
        },
        onPending: function (result) {
            // console.log(result);
        },
        onError: function (result) {
            // console.log(result);
        },
    });
};
</script>
