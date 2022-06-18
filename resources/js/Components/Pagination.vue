<template>
    <template v-for="(link, key) in links" :key="key">
        <div
            v-if="link.url == null"
            v-html="link.label"
            class="mr-1 mb-1 px-4 py-3 text-gray-400 tex-sm leading-4 border rounded"
        ></div>
        <Link
            v-else
            :href="link.url"
            v-html="link.label"
            :class="{
                'bg-indigo-200': link.active,
            }"
            class="mr-1 mb-1 px-4 py-3 tex-sm leading-4 border rounded focus:text-indigo-500 focus:border-indigo-500 hover:bg-white"
        ></Link>
    </template>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";

defineProps({
    links: Array,
});
</script>
