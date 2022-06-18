<template>
    <div>
        <AdminLayout title="Dashboard">
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Movie Edit
                </h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="w-full flex mb-4 p-2">
                        <Link
                            :href="route('admin.movies.index')"
                            class="bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded"
                        >
                            Index Movie
                        </Link>
                    </div>
                    <section class="flex content-center">
                        <div
                            class="w-full mb-8 sm:max-w-md p-6 overflow-hidden bg-white rounded-lg shadow-lg"
                        >
                            <form @submit.prevent="updateMovie">
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

                                <div class="mb-4">
                                    <label
                                        for="runtime"
                                        class="block text-sm font-medium text-gray-700"
                                        >Runtime</label
                                    >
                                    <input
                                        v-model="form.runtime"
                                        type="text"
                                        autocomplete="given-name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />

                                    <div
                                        class="text-red-500"
                                        v-if="form.errors.runtime"
                                    >
                                        {{ form.errors.runtime }}
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label
                                        for="language"
                                        class="block text-sm font-medium text-gray-700"
                                        >Language</label
                                    >
                                    <input
                                        v-model="form.language"
                                        type="text"
                                        autocomplete="given-name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />

                                    <div
                                        class="text-red-500"
                                        v-if="form.errors.language"
                                    >
                                        {{ form.errors.language }}
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label
                                        for="format"
                                        class="block text-sm font-medium text-gray-700"
                                        >Format</label
                                    >
                                    <input
                                        v-model="form.format"
                                        type="text"
                                        autocomplete="given-name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />

                                    <div
                                        class="text-red-500"
                                        v-if="form.errors.format"
                                    >
                                        {{ form.errors.format }}
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label
                                        for="rating"
                                        class="block text-sm font-medium text-gray-700"
                                        >Rating</label
                                    >
                                    <input
                                        v-model="form.rating"
                                        type="text"
                                        autocomplete="given-name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />

                                    <div
                                        class="text-red-500"
                                        v-if="form.errors.rating"
                                    >
                                        {{ form.errors.rating }}
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

                                <div class="mb-4">
                                    <label
                                        for="backdrop-path"
                                        class="block text-sm font-medium text-gray-700"
                                        >Backdrop</label
                                    >
                                    <input
                                        v-model="form.backdrop_path"
                                        type="text"
                                        autocomplete="given-name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />

                                    <div
                                        class="text-red-500"
                                        v-if="form.errors.backdrop_path"
                                    >
                                        {{ form.errors.backdrop_path }}
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label
                                        for="overview"
                                        class="block text-sm font-medium text-gray-700"
                                        >Overview</label
                                    >
                                    <input
                                        v-model="form.overview"
                                        type="text"
                                        autocomplete="given-name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />

                                    <div
                                        class="text-red-500"
                                        v-if="form.errors.overview"
                                    >
                                        {{ form.errors.overview }}
                                    </div>
                                </div>

                                <div class="block mb-4">
                                    <label class="flex items-center">
                                        <JetCheckbox
                                            v-model:checked="form.is_public"
                                            name="is_public"
                                        />
                                        <span class="ml-2 text-sm text-gray-600"
                                            >Public</span
                                        >
                                    </label>
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
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import { defineProps } from "vue";

const props = defineProps({
    movie: Object,
});

const form = useForm({
    title: props.movie.title,
    runtime: props.movie.runtime,
    language: props.movie.lang,
    format: props.movie.video_format,
    rating: props.movie.rating,
    poster_path: props.movie.poster_path,
    backdrop_path: props.movie.backdrop_path,
    overview: props.movie.overview,
    is_public: props.movie.is_public ? true : false,
});

function updateMovie() {
    form.put(`/admin/movies/${props.movie.id}`);
}
</script>
