<template>
    <div>
        <AuthenticatedLayout>
            <div class="flex">
                <div class="w-full m-5 flex">

                    <a class="m-4 inline-flex items-center px-4 py-2 h-fit	 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        :href="route('admin.exportResultsAll', [video.id])">
                        <svg class="h-5 w-5 text-white-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>&nbsp; Download All
                    </a>
                    <a class="m-4 inline-flex items-center px-4 py-2 h-fit	 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        :href="route('admin.exportResultsAll', {id :video.id, user : query.user, project : query.project})">
                        <svg class="h-5 w-5 text-white-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>&nbsp; Download Filtered
                    </a>
                </div>
                <div class="w-full m-5 flex">
                    <div class="w-2/4 m-5">
                        <InputLabel for="name" value="Users" />
                        <SelectInput @change="fetch_list()" v-model="query.user" :optionsValues="users"></SelectInput>
                    </div>
                    <div class="w-2/4 m-5">
                        <InputLabel for="name" value="Projects" />
                        <SelectInput @change="fetch_list()" v-model="query.project" :optionsValues="projects"></SelectInput>
                    </div>
                </div>
            </div>
            <TVTable :items="list.items" :fields="list.fields" :totalRows="list.totalRows" :perPage="list.perPage"
                :spinnerClass="list.spinnerClass" :rowSelectClass="list.rowSelectClass"
                :hidePagination="list.hidePagination" :hideSummary="list.hideSummary"
                :multipleSortable="list.multipleSortable" :multipleSelection="list.multipleSelection"
                :enableCheck="list.enableCheck" :busy="list.busy" @changePage="getChangePage"></TVTable>



        </AuthenticatedLayout>

    </div>
</template>

<script lang="ts">
import { defineComponent, reactive } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TVTable from '@/Components/table/TVTable.vue'
import axios from 'axios';
import InputLabel from '@/Components/InputLabel.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { stridedSlice, string } from '@tensorflow/tfjs-node';
import { Link } from '@inertiajs/vue3';

interface opt {
    value: string,
    text: string
};

export default defineComponent({
    //name : "videoResults",
    setup() {


        return {}
    },
    components: {
        AuthenticatedLayout,
        SelectInput,
        InputLabel,
        TVTable: TVTable,
        Link
    },
    props: {
        video: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            users: [] as opt[],
            projects: [] as opt[],
            query: reactive({
                id: this.video.id,
                page: 1,
                perPage: 10,
                project: "",
                user: ""

            }),
            list: reactive({
                items: [],
                fields: undefined as Object[] | undefined,
                totalRows: 1,
                perPage: 10,
                spinnerClass: "",
                rowSelectClass: "",
                hidePagination: false,
                hideSummary: false,
                multipleSortable: false,
                multipleSelection: false,
                enableCheck: false,
                busy: false


            })
        }
    },
    mounted() {
        console.log("ciao");
        this.fetch_list();
        this.fetch_users();
        this.fetch_projects()
    },
    methods: {
        fetch_users() {
            axios.post('/getusersvideo', this.query)
                .then(res => {
                    console.log(res.data);
                    this.users = res.data.map((x: any) => { return { value: x.user, text: x.user } })
                    this.users.unshift({ value: "", text: "select an User" })

                })
                .catch(error => {
                    console.log(error);

                })

        },
        fetch_projects() {
            axios.post('/getprojectsvideo', this.query)
                .then(res => {
                    console.log(res.data);
                    this.projects = res.data.map((x: any) => { return { value: x.progetto, text: x.progetto } })
                    this.projects.unshift({ value: "", text: "select a Project" })

                })
                .catch(error => {
                    console.log(error);

                })

        },
        fetch_list() {
            axios.post('/getvideoresults', this.query)
                .then(res => {
                    console.log(res.data);
                    this.list.items = res.data.data
                    var fields = Object.keys(res.data.data[0])
                    this.list.fields = fields.map(x => {
                        if (x != 'id') {
                            return { label: x, sortable: true, thClass: "", thStyle: "", key: x }
                        } else {
                            return false
                        }
                    })
                    this.list.totalRows = res.data.meta.total
                    this.list.perPage = res.data.meta.per_page
                })
                .catch(error => {
                    console.log(error);

                })
        },
        getChangePage(val: any) {
            console.log(val);
            this.query.page = val.page
            this.query.perPage = val.perPage
            this.fetch_list()
        }
    }
})
</script>

<style scoped></style>