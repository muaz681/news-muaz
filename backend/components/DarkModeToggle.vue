<template>
  <v-btn icon v-model="darkmode" @click="darkmode = !darkmode" :title="darkmode? 'Light Mode' : 'Dark Mode'">
    <v-icon v-if="darkmode">mdi-weather-night</v-icon>
    <v-icon v-else>mdi-white-balance-sunny</v-icon>
  </v-btn>
</template>

<script>
export default {
  name: "DarkModeToggle",
  data () {
    return {
      darkmode: false
    }
  },
  watch: {
    darkmode (oldval, newval) {
      this.handledarkmode()
    }
  },
  created () {
    if (process.browser) {
      if (localStorage.getItem('DarkMode')) {
        const cookieValue = localStorage.getItem('DarkMode') === 'true'
        this.darkmode = cookieValue
      } else {
        this.handledarkmode()
      }
    }
  },
  methods: {
    handledarkmode () {
      if (process.browser) {
        if (this.darkmode === true) {
          this.$vuetify.theme.dark = true
          localStorage.setItem('DarkMode', true)
        } else if (this.darkmode === false) {
          this.$vuetify.theme.dark = false
          localStorage.setItem('DarkMode', false)
        }
      }
    }
  }
}
</script>

<style scoped>

</style>
