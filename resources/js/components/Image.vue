<template>
    <div class="">
      <div class="flex-row justify-between">
      <img class="logo" :src="logoSrc" alt="">
      <div @click="videoMode = false" v-if="videoMode" class="btn btn-primary video-control">Exit</div>
      <div @click="videoMode = true" v-else class="btn video-control">Video</div>

      </div>
      <video class="video" v-if="videoMode" :src="videoSrc" controls :height="videoHeight" :width="videoWidth"></video>
        <img v-if="!videoMode" class="photo" :src="imgSrc" />
    </div>
</template>

<script>
import _ from 'lodash';

export default {


  data() {
    return {
      timestamp: null,
      videoMode: false
    }
  },

  created() {
    this.updateTimestamp();
    Echo.channel('photos')
    .listen('NewPhotoAdded', (e) => {
        console.log('new photo');
        this.updateTimestamp();
    });
  },

  computed: {

    imgSrc() {
      return `${process.env.MIX_STATIC_BASE}/photos/latest.jpg?ts=${this.timestamp}`
    },

    logoSrc() {
      return `${process.env.MIX_STATIC_BASE}/logo.jpg`
    },

    videoSrc() {
      return `${process.env.MIX_STATIC_BASE}/videos/latest.mov`
    },

    videoHeight() {
      return window.innerHeight;
    },

    videoWidth() {
      return window.innerWidth;
    }
  },

  methods: {
    updateTimestamp() {
      this.timestamp = Math.floor(Date.now() / 1000);
    }
  }
}
</script>

<style>

</style>
