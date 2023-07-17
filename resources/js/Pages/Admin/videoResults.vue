<template>
    <div>
        <AuthenticatedLayout>
            <div class="flex">
            <div class="w-2/4 m-5" >
            <InputLabel  for="name" value="Users" />
            <SelectInput @change="fetch_list()"  v-model="query.user" :optionsValues="users"></SelectInput>
        </div>
        <div class="w-2/4 m-5">
            <InputLabel  for="name" value="Projects" />
            <SelectInput @change="fetch_list()" v-model="query.project" :optionsValues="projects"></SelectInput>
        </div>
    </div>
            <TVTable 
                :items="list.items" 
                :fields="list.fields"
                :totalRows="list.totalRows"
                :perPage="list.perPage"
                :spinnerClass="list.spinnerClass"
                :rowSelectClass="list.rowSelectClass"
                :hidePagination="list.hidePagination"
                :hideSummary="list.hideSummary"
                :multipleSortable="list.multipleSortable"
                :multipleSelection="list.multipleSelection"
                :enableCheck="list.enableCheck"
                :busy="list.busy"
                @changePage="getChangePage"
                ></TVTable>



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

interface opt {
  value: string,
  text: string
};

export default defineComponent({
    //name : "videoResults",
    setup () {
        

        return {}
    },
    components : {
        AuthenticatedLayout,
        SelectInput,
        InputLabel,
        TVTable : TVTable
    }, 
    props : {
        video : {
            type : Object,
            required : true
        }
    },
    data() {
        return {
            users: [] as opt[],
            projects: [] as opt[],
            query : reactive({
                id : this.video.id,
                page : 1,
                perPage : 10,
                project: "",
                user: ""

            }),
            list: reactive({
                items : [],
                fields: undefined as Object[] | undefined,
                totalRows : 1,
                perPage : 10,
                spinnerClass: "",
                rowSelectClass : "",
                hidePagination : false,
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
    methods :{
        fetch_users(){
            axios.post('/getusersvideo', this.query )
            .then(res=>{
                console.log(res.data);
                this.users = res.data.map((x: any) =>{ return {value : x.user, text: x.user}})
                this.users.unshift({value : "", text : "select an User"})
                
            })
            .catch(error=>{
                console.log(error);
                
            })

        },
        fetch_projects(){
            axios.post('/getprojectsvideo', this.query )
            .then(res=>{
                console.log(res.data);
                this.projects = res.data.map((x: any) =>{ return {value : x.progetto, text: x.progetto}})
                this.projects.unshift({value : "", text : "select an User"})
                
            })
            .catch(error=>{
                console.log(error);
                
            })

        },
        fetch_list(){
            axios.post('/getvideoresults', this.query )
            .then(res=>{
                console.log(res.data);
                this.list.items= res.data.data
                var fields = Object.keys(res.data.data[0])
                this.list.fields= fields.map(x=>{
                    if(x!='id'){
                    return {label : x, sortable : true, thClass: "", thStyle: "", key: x}
                 } else {
                    return false
                 } 
                })
                this.list.totalRows = res.data.meta.total
                this.list.perPage = res.data.meta.per_page
            })
            .catch(error=>{
                console.log(error);
                
            })
        },
        getChangePage(val: any){
            console.log(val);
            this.query.page= val.page
            this.query.perPage= val.perPage
            this.fetch_list()
        }
    }
})
</script>

<style scoped>

</style>