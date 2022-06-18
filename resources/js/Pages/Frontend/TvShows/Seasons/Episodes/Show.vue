<template>
    <Head :title="`${episode.name} Episode`" />
    <div>
        <FrontLayout>
            <main class="my-2" v-if="episode">
                <section
                    class="bg-gradient-to-r from-indigo-700 to-transparent"
                >
                    <div class="max-w-6xl mx-auto m-4 p-2">
                        <div class="flex">
                            <div class="w-3/12">
                                <div class="w-full">
                                    <img
                                        class="w-full h-full rounded"
                                        :src="`https://www.themoviedb.org/t/p/w220_and_h330_face/${season.poster_path}`"
                                    />
                                </div>
                            </div>
                            <div class="w-8/12">
                                <div class="m-4 p-6">
                                    <h1
                                        class="flex text-white font-bold text-4xl"
                                    >
                                        {{ episode.name }}
                                    </h1>
                                    <div class="flex p-3 text-white space-x-4">
                                        <span
                                            >Season :
                                            {{ season.season_number }}</span
                                        >
                                    </div>
                                    <div class="flex pl-3 text-white space-x-4">
                                        <span
                                            >Episode :
                                            {{ episode.episode_number }}</span
                                        >
                                    </div>
                                    <div class="p-8 text-white">
                                        <p>{{ episode.overview }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section
                    class="max-w-6xl mx-auto bg-gray-200 dark:bg-gray-900 p-2 rounded"
                >
                    <div class="flex justify-between">
                        <div class="w-7/12">
                            <h1 class="flex text-white font-bold text-xl">
                                Latest Episodes
                            </h1>
                            <div
                                class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4"
                            >
                                <MovieCard
                                    v-for="latest in latests"
                                    :key="latest.id"
                                >
                                    <template #image>
                                        <Link
                                            :href="`/episodes/${latest.slug}`"
                                        >
                                            <img
                                                class=""
                                                :src="`https://www.themoviedb.org/t/p/w220_and_h330_face/${season.poster_path}`"
                                            />
                                        </Link>
                                    </template>
                                    <Link :href="`/episodes/${latest.slug}`">
                                        <span class="text-white">{{
                                            latest.name
                                        }}</span>
                                    </Link>
                                </MovieCard>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </FrontLayout>
    </div>
</template>

<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
import MovieCard from "@/Components/MovieCard.vue";
import { ref } from "vue";

defineProps({
    latests: Array,
    season: Object,
    episode: Object,
});
</script>
