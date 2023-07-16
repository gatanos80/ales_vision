<script  lang="ts">
import { defineComponent, reactive, ref } from 'vue'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

import axios from 'axios';

export default defineComponent({
    name: "show",
    setup() {


        return {}
    },
    data() {
        return {
            location: "",
            list: reactive([
                {
                    id: 0 as number,
                    code: "",
                    title: "",
                    uuid: ""
                }
            ])
        }
    },
    components: {
        PrimaryButton: PrimaryButton,
        AuthenticatedLayout: AuthenticatedLayout,
        Link: Link
    },
    mounted() {
        console.log("ciao");
        this.fetch_list()
        this.location = window.location.origin
    },
    methods: {
        fetch_list() {
            axios.get('/getvideolist')
                .then(res => {
                    console.log(res.data);
                    this.list = res.data.data

                })
                .catch(err => {
                    console.log(err);
                })
        }
    },
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="container mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">



                    <table class="border-collapse table-auto w-full text-sm">
                        <thead>
                            <th width=100 class="text-center">Code</th>
                            <th class="text-center">Title</th>
                            <th class="text-center">Link</th>
                            <th  width=100 class="text-right"></th>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in list" :key="item.id">
                                <td class="text-center">{{ item.code }}</td>
                                <td class="text-center">{{ item.title }}</td>
                                <td class="text-center">{{ location }}/vision/{{ item.uuid }}?idproj=[ID_PROGETTO]&idusr=[ID_USER]</td>
                                <td class="text-right flex">
                                    <Link class="rounded-md bg-sky-800 p-2 m-2" :href="route('admin.editVideo', [item.id])">
                                        <svg class="w-4 h-4 text-white dark:text-white stroke-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                            <path
                                                d="M12.687 14.408a3.01 3.01 0 0 1-1.533.821l-3.566.713a3 3 0 0 1-3.53-3.53l.713-3.566a3.01 3.01 0 0 1 .821-1.533L10.905 2H2.167A2.169 2.169 0 0 0 0 4.167v11.666A2.169 2.169 0 0 0 2.167 18h11.666A2.169 2.169 0 0 0 16 15.833V11.1l-3.313 3.308Zm5.53-9.065.546-.546a2.518 2.518 0 0 0 0-3.56 2.576 2.576 0 0 0-3.559 0l-.547.547 3.56 3.56Z" />
                                            <path
                                                d="M13.243 3.2 7.359 9.081a.5.5 0 0 0-.136.256L6.51 12.9a.5.5 0 0 0 .59.59l3.566-.713a.5.5 0 0 0 .255-.136L16.8 6.757 13.243 3.2Z" />
                                        </svg>
                                    </Link>
                                    <button  class="rounded-md bg-sky-800 p-2 m-2">
                                        <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5h6M9 8h6m-6 3h6M4.996 5h.01m-.01 3h.01m-.01 3h.01M2 1h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex items-center justify-end m-4">

                    <Link :href="route('admin.addVideo')"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Add Video
                    </Link>
                </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


