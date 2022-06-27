<template>
    <v-container>
        <v-row justify="center">
          <v-col cols="7">
              <v-row>
                  <v-col v-for="n in 12"
                        :key="n" 
                        cols="4"
                        >
                      <PostRendererTopImage/>
                  </v-col>
              </v-row>
              <!-- <PostRendererTopImage/> -->
          </v-col>
          <v-col cols="2">
              <v-card>
      <v-toolbar
        color="purple"
        dark
      >
        <template>
          <v-tabs
            v-model="tabs"
            centered
          >
            <v-tab
              v-for="n in 2"
              :key="n"
            >
              Item {{ n }}
            </v-tab>
          </v-tabs>
        </template>
      </v-toolbar>
  
      <v-tabs-items v-model="tabs">
        <v-tab-item>
          <v-card flat>
            <v-card-text>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </v-card-text>
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <v-card flat>
            <v-card-title class="text-h5">
              An awesome title
            </v-card-title>
            <v-card-text>
              <p>
                Duis lobortis massa imperdiet quam. Donec vitae orci sed dolor rutrum auctor. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesent congue erat at massa.
              </p>
  
              <p>
                Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus. Pellentesque egestas, neque sit amet convallis pulvinar, justo nulla eleifend augue, ac auctor orci leo non est. Etiam sit amet orci eget eros faucibus tincidunt. Donec sodales sagittis magna.
              </p>
  
              <p class="mb-0">
                Ut leo. Suspendisse potenti. Duis vel nibh at velit scelerisque suscipit. Fusce pharetra convallis urna.
              </p>
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
          </v-col>
        </v-row>
    </v-container>
</template>
<script>
import PostRendererTopImage from "../frontend/PostRendererTopImage.vue";


  export default {
    components: { PostRendererTopImage },
    data () {
      return {
        tabs: null,
        
      }
    },
}
</script>
<style>
    
</style>