<template>
    <div>
        <input type="text" v-model="backgroundColour">
        <div v-show="!showChrome"  @click.stop="changeBackgroundColour" :style="{'background-color': backgroundColour, color: foregroundColour}" class="btn">Change</div>
        <Chrome v-show="showChrome" :value="backgroundColour" @input="updateColour" v-click-outside="closeChrome" />
    </div>
</template>
<script>

import { Chrome } from 'vue-color';

export default {

  props: ['initColour'],
  components: {
      Chrome
  },

  data() {
    return {
        backgroundColour: this.initColour,
        showChrome: false,
    }
  },

  created() {
      this.backgroundColour = this.initColour;
  },

  computed: {
    foregroundColour() {
      let bg = '#000000'

      try {
        bg = this.invertColor(this.backgroundColour);
      }

      finally{
        return bg;
      }

    }
  },

  methods: {

    changeBackgroundColour() {
      console.log('changeBackgroundColour');

      this.showChrome = true;
    },

    updateColour(value) {
      this.backgroundColour = value.hex;
      this.$emit('updated', this.backgroundColour);
    },

    invertColor(hex) {
        if (hex.indexOf('#') === 0) {
            hex = hex.slice(1);
        }
        // convert 3-digit hex to 6-digits.
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        if (hex.length !== 6) {
            throw new Error('Invalid HEX color.');
        }
        // invert color components
        var r = (255 - parseInt(hex.slice(0, 2), 16)).toString(16),
            g = (255 - parseInt(hex.slice(2, 4), 16)).toString(16),
            b = (255 - parseInt(hex.slice(4, 6), 16)).toString(16);
        // pad each with zeros and return
        return '#' + this.padZero(r) + this.padZero(g) + this.padZero(b);
    },

    padZero(str, len) {
        len = len || 2;
        var zeros = new Array(len).join('0');
        return (zeros + str).slice(-len);
    },

    closeChrome() {
      console.log('close chrome');

      if (this.showChrome) {

        this.showChrome = false;
      }
    }
  },
}
</script>
