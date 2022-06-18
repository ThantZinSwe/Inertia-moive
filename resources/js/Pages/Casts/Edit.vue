<template>
    <div>
        <AdminLayout title="Dashboard">
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Casts Edit
                </h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="w-full flex mb-4 p-2">
                        <Link
                            :href="route('admin.casts.index')"
                            class="bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded"
                        >
                            Index Casts
                        </Link>
                    </div>
                    <section class="flex content-center">
                        <div
                            class="w-full mb-8 sm:max-w-md p-6 overflow-hidden bg-white rounded-lg shadow-lg"
                        >
                            <form @submit.prevent="updateCast">
                                <div class="mb-4">
                                    <label
                                        for="first-name"
                                        class="block text-sm font-medium text-gray-700"
                                        >Name</label
                                    >
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        autocomplete="given-name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />

                                    <div
                                        class="text-red-500"
                                        v-if="form.errors.name"
                                    >
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label
                                        for="poster-path"
                                        class="block text-sm font-medium text-gray-700"
                                        >Poster Path</label
                                    >
                                    <input
                                        v-model="form.poster_path"
                                        type="text"
                                        autocomplete="given-name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />

                                    <div
                                        class="text-red-500"
                                        v-if="form.errors.poster_path"
                                    >
                                        {{ form.errors.poster_path }}
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded"
                                >
                                    Update
                                </button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </AdminLayout>
    </div>
</template>

<script setup>
import AdminLayout from "../../Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";

const props = defineProps({
    cast: Object,
});
const form = useForm({
    name: props.cast.name,
    poster_path: props.cast.poster_path,
});

function updateCast() {
    form.put(`/admin/casts/${props.cast.id}`);
}
</script>
