<template>
    <div>
        <Modal v-if="show_modal" :show="show_modal" :maxWidth="'lg'" :closeable="false">
            <div class="p-10">
            <div class="flex flex-col items-center justify-center  mt-4">

                <vimeoPlayer :video-id="form.id_video" :videoUrl="form.url_video" :controls="true" @playing="getplaying">
                </vimeoPlayer>
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
                    <div>
                        <InputLabel for="code" value="Code" />
                        <TextInput id="code" type="text" class="mt-1 block w-full" v-model="form.code"  autofocus required
                            autocomplete="code" />
                        <InputError class="mt-2" :message="form.errors.code" />
                    </div>
                    <div>
                        <InputLabel for="title" value="Title" />
                        <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title"  autofocus required
                            autocomplete="title" />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>
                    <div>
                        <InputLabel for="id_video" value="Id" />
                        <TextInput  @change="url_checked=false" id="id_video" type="text" class="mt-1 block w-full" v-model="form.id_video" required
                            autofocus autocomplete="id_video" />
                        <InputError class="mt-2" :message="form.errors.id_video" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="url" value="URL" />
                        <TextInput @change="url_checked=false" id="url" type="text" class="mt-1 block w-full" v-model="form.url_video" required
                            autocomplete="url" />
                        <InputError class="mt-2" :message="form.errors.url_video" />
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
import vimeoPlayer from '@/Components/players/vimeoPlayer.vue';

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
        vimeoPlayer
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