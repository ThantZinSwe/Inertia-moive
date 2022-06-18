<template>
    <div>
        <AdminLayout title="Dashboard">
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Episode Edit
                </h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="w-full flex mb-4 p-2">
                        <Link
                            :href="
                                route('admin.episodes.index', [
                                    tvShow.id,
                                    season.id,
                                ])
                            "
                            class="bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded"
                        >
                            Index Episode
                        </Link>
                    </div>
                    <section class="flex content-center">
                        <div
                            class="w-full mb-8 sm:max-w-md p-6 overflow-hidden bg-white rounded-lg shadow-lg"
                        >
                            <form @submit.prevent="updateEpisode">
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
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import { defineProps } from "vue";

const props = defineProps({
    tvShow: Object,
    season: Object,
    episode: Object,
});

const form = useForm({
    name: props.episode.name,
    overview: props.episode.overview,
    is_public: props.episode.is_public ? true : false,
});

function updateEpisode() {
    form.put(
        `/admin/tv-shows/${props.tvShow.id}/seasons/${props.season.id}/episodes/${props.episode.id}`
    );
}
</script>
