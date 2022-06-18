<template>
    <div>
        <AdminLayout title="Dashboard">
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Genres Edit
                </h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="w-full flex mb-4 p-2">
                        <Link
                            :href="route('admin.genres.index')"
                            class="bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded"
                        >
                            Index Genres
                        </Link>
                    </div>
                    <section class="flex content-center">
                        <div
                            class="w-full mb-8 sm:max-w-md p-6 overflow-hidden bg-white rounded-lg shadow-lg"
                        >
                            <form @submit.prevent="updateGenre">
                                <div class="mb-4">
                                    <label
                                        for="title"
                                        class="block text-sm font-medium text-gray-700"
                                        >Title</label
                                    >
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        autocomplete="given-name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />

                                    <div
                                        class="text-red-500"
                                        v-if="form.errors.title"
                                    >
                                        {{ form.errors.title }}
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
    genre: Object,
});

const form = useForm({
    title: props.genre.title,
});

function updateGenre() {
    form.put(`/admin/genres/${props.genre.id}`);
}
</script>
