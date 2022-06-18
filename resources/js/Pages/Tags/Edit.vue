<template>
    <div>
        <AdminLayout title="Dashboard">
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tags Edit
                </h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="w-full flex mb-4 p-2">
                        <Link
                            :href="route('admin.tags.index')"
                            class="bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded"
                        >
                            Index Tag
                        </Link>
                    </div>
                    <section class="flex content-center">
                        <form @submit.prevent="updateTag">
                            <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <div class="w-full space-y-2">
                                        <div class="">
                                            <label
                                                for="first-name"
                                                class="block text-sm font-medium text-gray-700"
                                                >Tag name</label
                                            >
                                            <input
                                                v-model="form.tagName"
                                                type="text"
                                                autocomplete="given-name"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />

                                            <div class="text-red-500">
                                                {{ errors.tagName }}
                                            </div>
                                        </div>

                                        <button
                                            type="submit"
                                            class="bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded"
                                        >
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </AdminLayout>
    </div>
</template>

<script>
import AdminLayout from "../../Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: { AdminLayout, Link },
    props: ["errors", "tag"],
    data() {
        return {
            form: {
                tagName: this.tag.tag_name,
            },
        };
    },
    methods: {
        updateTag() {
            this.$inertia.put(`/admin/tags/${this.tag.id}`, this.form);
        },
    },
};
</script>
