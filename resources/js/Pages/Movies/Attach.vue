<template>
    <div>
        <AdminLayout title="Dashboard">
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Movie Attach
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
                    <section class="container mx-auto font-mono">
                        <div
                            class="w-full mb-8 sm:max-w-md p-6 overflow-hidden bg-white rounded-lg shadow-lg"
                        >
                            <div class="flex space-x-2 mb-3">
                                <div
                                    v-for="trailer in trailers"
                                    :key="trailer.id"
                                >
                                    <Link
                                        class="inline-flex items-center justify-center py-2 px-4 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-red-700 transition duration-150 ease-in-out disabled:opacity-50"
                                        method="delete"
                                        as="button"
                                        type="button"
                                        :href="
                                            route(
                                                'admin.trailer_urls.destroy',
                                                trailer.id
                                            )
                                        "
                                    >
                                        <span class="mr-1">{{
                                            trailer.name
                                        }}</span>
                                        <svg
                                            class="w-6 h-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                            <form @submit.prevent="createTrailer">
                                <div class="mb-4">
                                    <label
                                        for="name"
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
                                        for="embed"
                                        class="block text-sm font-medium text-gray-700"
                                        >Embed</label
                                    >
                                    <textarea
                                        v-model="form.embed_html"
                                        type="text"
                                        autocomplete="given-name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    ></textarea>

                                    <div
                                        class="text-red-500"
                                        v-if="form.errors.embed_html"
                                    >
                                        {{ form.errors.embed_html }}
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded float-right"
                                >
                                    Add Trailer
                                </button>
                            </form>
                        </div>

                        <div
                            class="w-full mb-8 sm:max-w-md p-6 bg-white rounded-lg shadow-lg"
                        >
                            <div class="flex">
                                <div
                                    class="m-2 p-1 text-xs"
                                    v-for="mc in movieCasts"
                                    :key="mc.id"
                                >
                                    {{ mc.name }}
                                </div>
                            </div>
                            <form @submit.prevent="addCasts">
                                <multiselect
                                    v-model="castsForm.casts"
                                    :options="casts"
                                    :multiple="true"
                                    :close-on-select="false"
                                    :clear-on-select="false"
                                    :preserve-search="true"
                                    placeholder="Add casts"
                                    label="name"
                                    track-by="name"
                                ></multiselect>
                                <div class="mt-3">
                                    <JetButton>Add Casts</JetButton>
                                </div>
                            </form>
                        </div>

                        <div
                            class="w-full mb-8 sm:max-w-md p-6 bg-white rounded-lg shadow-lg"
                        >
                            <div class="flex">
                                <div
                                    class="m-2 p-1 text-xs"
                                    v-for="mt in movieTags"
                                    :key="mt.id"
                                >
                                    {{ mt.tag_name }}
                                </div>
                            </div>
                            <form @submit.prevent="addTags">
                                <multiselect
                                    v-model="tagsForm.tags"
                                    :options="tags"
                                    :multiple="true"
                                    :close-on-select="false"
                                    :clear-on-select="false"
                                    :preserve-search="true"
                                    placeholder="Add tags"
                                    label="tag_name"
                                    track-by="tag_name"
                                ></multiselect>
                                <div class="mt-3">
                                    <JetButton>Add Tags</JetButton>
                                </div>
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
import { defineProps, ref } from "vue";
import Multiselect from "vue-multiselect";
import JetButton from "@/Jetstream/Button.vue";

const props = defineProps({
    movie: Object,
    trailers: Array,
    casts: Array,
    tags: Array,
    movieCasts: Array,
    movieTags: Array,
});

const form = useForm({
    name: "",
    embed_html: "",
});

const castsForm = useForm({
    casts: props.movieCasts,
});

const tagsForm = useForm({
    tags: props.movieTags,
});

function createTrailer() {
    form.post(`/admin/movies/${props.movie.id}/attach`, {
        onSuccess: () => form.reset(),
    });
}

function addCasts() {
    castsForm.post(`/admin/movies/${props.movie.id}/add-casts`, {
        preserveState: true,
        preserveScroll: true,
    });
}

function addTags() {
    tagsForm.post(`/admin/movies/${props.movie.id}/add-tags`, {
        preserveState: true,
        preserveScroll: true,
    });
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
