<template>
    <div>
        <vimeo-player   
            ref="player" 
            id="player" 
            :video-url="videoUrl" 
            :video-id="videoId"
            :player-height="height"
            :player-width="width"
            :options="options"
            :loop="loop"
            :controls="controls" 
            :autoplay="autoplay"
            @ready="onReady"
            @play="$emit('play', $event)"
            @playing ="playing($event)"
            @pause ="$emit('pause', $event)"
            @ended ="ended($event)"
            ></vimeo-player>
        <p hidden>{{ refresh }}</p>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive } from 'vue'
import  {vueVimeoPlayer}  from '@/customModules/vue-vimeo-player'

export default defineComponent({
    setup () {
        

        return {}
    },
    name : "vimeoPlayerComponent",
    components: {vimeoPlayer :  vueVimeoPlayer as any },
    props :{
        setTime: {
            type: Number,
            default: 0
        },
        videoId : {
            type : String,
            default : ""
        },
        height : {
            type :  Number,
            default : 500
        },
        width : reactive({
            type : Number,
            default : 500
        }),
        videoUrl : {
            type : String,
            default : ""
        },
        options : {
            type : Object,
            default : {}
        },
        loop : {
            type : Boolean,
            default : false
        },
        controls : {
            type : Boolean,
            default : true
        },
        autoplay : {
            type : Boolean,
            default : false           
        },
        startVideo : {
            type : Boolean,
            default : false           
        }
        },
        data() {
            return {
                refreshing: false,
                refresh: 0,
                listenerCampionamento: undefined as ReturnType<typeof setTimeout> | undefined,
            }
        },
        watch: {
            setTime(val){
                (this.$refs.player as any).setCurrentTime(val)
            },
            startVideo(val){
                if(val) this.play()
            },
            width(val){
                this.refresh++
                this.refreshing = true
                this.$nextTick(()=>{
                    this.refreshing = false

                })
            },
            height(val){
                this.refresh++
            } 
        },
        mounted() {
            var self = this
           // const video = document.getElementById('video') as HTMLVideoElement
           // console.log(this.$refs.player.player());
            
           /* video.addEventListener('playing', function(e){
                setInterval(()=>{
                   // console.log(self.$refs.player.getCurrentTime());
                    
                },1000)
            })*/
        },
        
       async beforeUnmount() {
            clearInterval(this.listenerCampionamento)
            var self = this;
            await (this.$refs.player as any).getCurrentTime().then(async (sec : any)=>{
                    self.$emit('currentTime',sec) 

            })
        },
        methods: {
        /*    resize(val : any){
                console.log("resize", val);
                //this.$emit('resize', (this.$refs.player as any).getCurrentTime())            

                
            },*/
        onReady() {
			//this.playerReady = true
		},
		play () {
			(this.$refs.player as any).play()
		},
		pause () {
			(this.$refs.player as any).pause()
            clearInterval(this.listenerCampionamento)

		},
        ended(e : any ){
            (this.$refs.player as any).restart();
            this.$emit('ended', e)
            this.$emit('campiona', (this.$refs.player as any).getCurrentTime())            
            clearInterval(this.listenerCampionamento)
        },
        playing(e : any ){
            var self= this
            this.$emit('playing', e)
            this.listenerCampionamento= setInterval(()=>{
                (this.$refs.player as any).getCurrentTime().then((seconds : Number) =>{
                self.$emit('campiona', seconds)            
            })
            }, 1000 )
        }
        },

})
</script>

