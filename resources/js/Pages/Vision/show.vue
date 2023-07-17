<template>
    <div id="vision" class="container mx-auto flex flex-wrap flex-col items-center	s	">
        <video class="video" id="video" width="720" height="560" autoplay muted></video>

        <vimeoPlayerComponent :video-id="video.id_video" :videoUrl="video.url_video"
            :startVideo="playVideo" :controls="false" @play="getplay" @playing="getplaying" @pause="getpause"
            @ended="getended" @campiona="campiona"></vimeoPlayerComponent>
        <PrimaryButton v-if="status.faceDetected && !isPlaying && !isEnded" @click.native="playVideo = true;"
            :disabled="false">Start
        </PrimaryButton>

        <PrimaryButton v-if="isEnded && !isPlaying" @click.native="playVideo = true;" :disabled="false">Restart
        </PrimaryButton>

    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from 'vue'
import * as faceapi from 'face-api.js';
import vimeoPlayerComponent from '@/Components/players/vimeoPlayerComponent.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { expressions } from "@/types/vision"
import axios from 'axios';
import { string } from '@tensorflow/tfjs-node';

export default defineComponent({
    name: "show",
    setup() {


        return {}
    },
    components: {
        vimeoPlayerComponent: vimeoPlayerComponent,
        PrimaryButton: PrimaryButton
    },
    props : {
        video : {
            type: Object,
            default : null
        },
        user_id : {
            type : String,
            required : true
        },
        id_proj : {
            type : String,
            required : true
        }
    },
    data() {
        return {
            detection: {
                index: 0 as number,
                age: 0 as number,
                gender: "" as string,
                expressions: {
                    neutral: 0,
                    happy: 0,
                    sad: 0,
                    angry: 0,
                    fearful: 0,
                    disgusted: 0,
                    surprised: 0,
                } as expressions
            },
            init: "",
            height: 500,
            status: reactive({
                faceDetected: false
            }),
            playVideo: false,
            isPlaying: false,
            isEnded: false,
            campionaInterval: undefined as ReturnType<typeof setInterval> | undefined,
            enableStartTimeout: undefined as ReturnType<typeof setTimeout> | undefined,
            startTimeoutSetted: false

        }
    },
    async mounted() {
        console.log(this.init);
        const video = document.getElementById('video') as HTMLVideoElement
        console.log({ video });

        //const detection = await faceapi.detectSingleFace(video)
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
            faceapi.nets.faceExpressionNet.loadFromUri('/models'),
            faceapi.nets.ageGenderNet.loadFromUri('/models')

        ]).then(startVideo)
        let myNav : any;
        myNav= navigator
        const navig: any = myNav.getUserMedia || myNav.webkitGetUserMedia || myNav.mozGetUserMedia;
        function startVideo() {
            if (myNav.getUserMedia) {
                myNav.getUserMedia(
                    { video: {} },
                    (stream : any)  => video.srcObject = stream,
                    (err : any) => console.error(err)
                )
            }
        }

        video.addEventListener('play', () => {
            var self = this
            const vision = document.getElementById('vision') as HTMLElement
            this.campionaInterval = setInterval(async () => {
                const detections = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions().withAgeAndGender()
                console.log(detections);
                if (this.isPlaying) {
                    self.detection.index++
                    if (detections) {
                        self.detection.age = detections.age
                        self.detection.gender = detections.gender
                        self.detection.expressions = detections.expressions
                    }
                }
                if (detections) {
                    if (!self.startTimeoutSetted) {
                        this.enableStartTimeout = setTimeout(() => {
                            self.status.faceDetected = detections != undefined
                        }, 1000)
                        self.startTimeoutSetted = true
                    }

                } else {
                    clearTimeout(this.enableStartTimeout)
                    self.status.faceDetected = false
                    self.startTimeoutSetted = false

                }


            }, 100)

        })
    },
    methods: {
        getplay(ev: any) {
            console.log("play", ev);

        },
        getplaying(ev: any) {
            console.log("getplaying", ev);
            this.isPlaying = true
            this.playVideo = false

        },
        getpause(ev: any) {
            console.log("getpause", ev);
            this.isPlaying = false

        },
        getended(ev: any) {
            console.log("getended", ev);
            this.isPlaying = false
            this.isEnded = true
            clearInterval(this.campionaInterval)

        },
        campiona(ev: any) {
            console.log(ev);
            if(!this.isEnded){
            console.log("campiona", this.detection);
            var campionamento_to_send = Object.assign({}, this.detection)
            this.detection = {
                index: 0,
                age: 0,
                gender: "",
                expressions: {
                    neutral: 0,
                    happy: 0,
                    sad: 0,
                    angry: 0,
                    fearful: 0,
                    disgusted: 0,
                    surprised: 0,

                },
            }
            axios.post('/vision', { 
                    id_video : this.video.id, 
                    user_id : this.user_id, 
                    id_proj: this.id_proj, 
                    id_service_video : this.video.id_video,
                    time : ev,
                    detection : campionamento_to_send })
                .then(res=>{
                    console.log(res.data);
                    
                })
                .catch(error =>{
                    console.log(error);
                    
                })
            }
        }
    }

})
</script>

<style scoped>
.video {
    visibility: collapse;
    position: absolute;
}
</style>