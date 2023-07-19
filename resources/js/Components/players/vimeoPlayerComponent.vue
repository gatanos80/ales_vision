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

    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import  {vueVimeoPlayer}  from '@/customModules/vue-vimeo-player'

export default defineComponent({
    setup () {
        

        return {}
    },
    name : "vimeoPlayerComponent",
    components: {vimeoPlayer :  vueVimeoPlayer as any },
    props :{
        videoId : {
            type : String,
            default : ""
        },
        height : {
            type :  Number,
            default : 500
        },
        width : {
            type : Number,
            default : 500
        },
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
                listenerCampionamento: undefined as ReturnType<typeof setTimeout> | undefined,
            }
        },
        watch: {
            startVideo(val){
                if(val) this.play()
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
        beforeUnmount() {
            clearInterval(this.listenerCampionamento)
        },
        methods: {
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

<style scoped>

</style>