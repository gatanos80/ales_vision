<template>
    <div id="vision" class="container mx-auto flex flex-wrap flex-col items-center p-5	m-5">
        <video class="video" id="video" width="720" height="560" autoplay muted></video>
        <label v-if="no_webcam" class="text-red-600"><span>Webcam not found</span></label>


        <vimeoPlayerComponent v-if="!resizing" :video-id="video.id_video" :videoUrl="video.url_video"
            :startVideo="playVideo" :height="height" :width="width" :options="vimeoOptions" :controls="false"
            :setTime="setTime" @play="getplay" @playing="getplaying" @pause="getpause" @ended="getended"
            @campiona="campiona" @currentTime="getCurrentTime"></vimeoPlayerComponent>
        <template v-if="webcam_streaming">

            <template v-if="status.faceDetected && !isPlaying && !isEnded">
                <PrimaryButton class="m-5" @click.native="playVideo = true;" :disabled="false">{{ video.start_label ?
                    video.start_label
                    :
                    "Start" }}
                </PrimaryButton>
            </template>
            <template v-if="!status.faceDetected && !isPlaying">
                <label class="block font-medium text-sm text-gray-700 m-5">
                    <span v-if="video.no_detection_label">{{ video.no_detection_label }}</span>
                    <span v-else>face not found</span>
                </label>
            </template>
        </template>
        <template v-else>
            <div class="p-5">

                <label v-if="!no_webcam" class="text-green-600 p-5"><span>Activating the Webcam</span></label>
            </div>
        </template>


        <!--<PrimaryButton v-if="isEnded && !isPlaying" @click.native="playVideo = true;" :disabled="false">Restart
        </PrimaryButton> -->

    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from 'vue'
import * as faceapi from 'face-api.js';
import vimeoPlayerComponent from '@/Components/players/vimeoPlayerComponent.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { expressions } from "@/types/vision"
import axios from 'axios';

export default defineComponent({
    name: "show",
    setup() {


        return {}
    },
    components: {
        vimeoPlayerComponent: vimeoPlayerComponent,
        PrimaryButton: PrimaryButton
    },
    props: {
        video: {
            type: Object,
            default: null
        },
        user_id: {
            type: String,
            required: true
        },
        id_proj: {
            type: String,
            required: true
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
            setTime: 0,

            vimeoOptions: {} as any,
            height: ref(700),
            width: ref(700),
            status: reactive({
                faceDetected: false
            }),
            playVideo: false,
            isPlaying: false,
            isEnded: false,
            campionaInterval: undefined as ReturnType<typeof setInterval> | undefined,
            enableStartTimeout: undefined as ReturnType<typeof setTimeout> | undefined,
            timeoutMaxTimeNoPlayButton: undefined as ReturnType<typeof setTimeout> | undefined,
            startTimeoutSetted: false,
            webcam_streaming: false,
            no_webcam: false,
            next_url: "",
            resizing: false,
            currentTime: 0

        }
    },
    beforeMount() {
        this.height = Math.round(window.innerHeight * 0.8)
        this.width = Math.round(window.innerWidth * 0.8)

    },
    async mounted() {
        var self = this

        const video = document.getElementById('video') as HTMLVideoElement

       /* window.addEventListener("resize", () => {
            self.height = Math.round(window.innerHeight * 0.8)
            self.width = Math.round(window.innerWidth * 0.8)
            console.log("playerResize");

            //this.setTime=val
            var isPlaying = this.isPlaying
            this.playVideo = false
            this.resizing = true;
            this.$nextTick(() => {
                this.resizing = false;
                this.$nextTick(() => {
                    this.setTime = this.currentTime
                    this.playVideo = isPlaying

                })
            })
        });*/


        //const detection = await faceapi.detectSingleFace(video)
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
            faceapi.nets.faceExpressionNet.loadFromUri('/models'),
            faceapi.nets.ageGenderNet.loadFromUri('/models')

        ]).then(startVideo)
        let myNav: any;
        myNav = navigator
        //const navig: any = myNav.getUserMedia || myNav.webkitGetUserMedia || myNav.mozGetUserMedia;
        function startVideo() {
            if (myNav.getUserMedia) {
                myNav.getUserMedia(
                    { video: {} },
                    (stream: any) => {
                        video.srcObject = stream

                    },
                    (err: any) => {
                        console.error(err)
                        self.no_webcam = true
                        setTimeout(() => {
                            window.location.href = self.next_url + '&webcamerror=1'
                        }, 30000)
                    }
                )
            }
        }

        video.addEventListener('play', () => {
            var self = this
            self.timeoutMaxTimeNoPlayButton = setTimeout(() => {
                window.location.href = self.next_url + '&webcamerror=2'

            }, 30000);

            const vision = document.getElementById('vision') as HTMLElement
            this.campionaInterval = setInterval(async () => {
                const detections = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions().withAgeAndGender()
                setTimeout(() => {
                    self.webcam_streaming = true

                }, 800);
                //console.log(detections);
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
                            clearTimeout(self.timeoutMaxTimeNoPlayButton)
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
        var next_url = this.video.return_link
        if (next_url.indexOf('[ID_USER]')) next_url = next_url.replaceAll('[ID_USER]', this.user_id)
        if (next_url.indexOf('[ID_PROGETTO]')) next_url = next_url.replaceAll('[ID_PROGETTO]', this.id_proj)
        this.next_url = next_url
    },
    methods: {
        getCurrentTime(val : any){
            console.log("getCurrentTime", val);
            
            if(!this.currentTime) this.currentTime =  val
        },
       /* playerResize(val: any) {
            console.log("playerResize");

            this.setTime = val
            this.resizing = true;
            this.$nextTick(() => {
                this.resizing = false;
                this.playVideo = true

            })
        },*/
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

            setTimeout(() => {
                window.location.href = this.next_url;
            }, 1000);

        },
        campiona(ev: any) {
            if (!this.isEnded) {
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
                    id_video: this.video.id,
                    user_id: this.user_id,
                    id_proj: this.id_proj,
                    id_service_video: this.video.id_video,
                    time: ev,
                    detection: campionamento_to_send
                })
                    .then(res => {
                        console.log(res.data);

                    })
                    .catch(error => {
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