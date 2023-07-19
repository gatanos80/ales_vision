<template>
    <div>
        <Modal v-if="show_modal" :show="show_modal" :maxWidth="'lg'" :closeable="false">
            <div class="p-10">
            <div class="flex flex-col items-center justify-center  mt-4">

                <vimeoPlayerComponent :video-id="form.id_video" :videoUrl="form.url_video" :controls="true" @playing="getplaying">
                </vimeoPlayerComponent>
                <div class="flex items-center justify-end mt-4">
                </div>

                    <PrimaryButton @click="show_modal = false" class="ml-4" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        Close
                    </PrimaryButton>
                </div>
            </div>
        </Modal>

        <Head title="Add Video" />
        <AuthenticatedLayout>
            <div class="w-full px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

                <form @submit.prevent="submit">
                    <div class="mt-4">
                        <InputLabel for="code" value="Code" />
                        <TextInput id="code" type="text" class="mt-1 block w-full" v-model="form.code"  autofocus required
                            autocomplete="code" />
                        <InputError class="mt-2" :message="form.errors.code" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="title" value="Title" />
                        <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title"  autofocus required
                            autocomplete="title" />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="id_video" value="Id Vimeo" />
                        <TextInput  @change="url_checked=false" id="id_video" type="text" class="mt-1 block w-full" v-model="form.id_video" required
                            autofocus autocomplete="id_video" />
                        <InputError class="mt-2" :message="form.errors.id_video" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="url" value="URL Vimeo" />
                        <TextInput @change="url_checked=false" id="url" type="text" class="mt-1 block w-full" v-model="form.url_video" required
                            autocomplete="url" />
                        <InputError class="mt-2" :message="form.errors.url_video" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="return_link" value="Return URL" />
                        <TextInput  id="return_link" type="text" class="mt-1 block w-full" v-model="form.return_link" required
                            autocomplete="return_link" />
                            <small>Use [ID_PROGETTO],[ID_USER] to identify project and user variables, eg : https://www2.fieldwork2000.it/ssi8/test123?uid=[ID_USER]&prj=[ID_PROGETTO]</small>
                            <small> these fields will be populated with the task execution input values</small>

                        <InputError class="mt-2" :message="form.errors.return_link" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="no_detection_label" value="No Detection Label" />
                        <TextInput  id="no_detection_label" type="text" class="mt-1 block w-full" v-model="form.no_detection_label" required
                            autocomplete="no_detection_label" />
                        <InputError class="mt-2" :message="form.errors.no_detection_label" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="start_label" value="Start Button Label" />
                        <TextInput  id="start_label" type="text" class="mt-1 block w-full" v-model="form.start_label" required
                            autocomplete="start_label" />
                        <InputError class="mt-2" :message="form.errors.start_label" />
                    </div>                   
                    <div class="flex items-center justify-end mt-4">

                        <PrimaryButton v-if="url_checked" class="ml-4" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Save
                        </PrimaryButton>
                        <PrimaryButton v-else @click.prevent="check_video" class="ml-4"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Check Video
                        </PrimaryButton>
                    </div>
                </form>

            </div>
        </AuthenticatedLayout>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import vimeoPlayerComponent from '@/Components/players/vimeoPlayerComponent.vue';

export default defineComponent({
    setup() {


        return {}

    },
    components: {
        Head,
        AuthenticatedLayout,
        PrimaryButton,
        TextInput,
        InputError,
        InputLabel,
        Modal,
        vimeoPlayerComponent
    },
    props : {
        video : {
            type : Object,
            default: null
        }
    },
    data() {
        return {
            show_modal: false,
            url_checked: false,
            form: useForm({
                id: null,
                code: '' as string,
                title: '',
                id_video: '',
                url_video: '',
                return_link: "",
                no_detection_label: "",
                start_label : "",
                type: 1
            })
        }
    },
    mounted() {
        if(this.video){
            this.form.id =this.video.id
            this.form.code =this.video.code
            this.form.title =this.video.title
            this.form.id_video =this.video.id_video
            this.form.url_video =this.video.url_video
            this.form.return_link =this.video.return_link
            this.form.no_detection_label =this.video.no_detection_label
            this.form.start_label =this.video.start_label
            ///this.form.code = this.video
            this.url_checked= true
        }
    },
    methods: {
        submit() {
            this.form.post(route('admin.saveVideo'), {
                onFinish: () => {
                    //this.form.reset('password', 'password_confirmation');
                },
            });
        },
        check_video() {
            console.log("check");
            this.show_modal = true
        },
        getplaying(val: any) {
            this.url_checked = true
        }
    }
})
</script>

<style scoped></style>