<template>
  <div>
    <div class="slider" v-if="postsLoaded && postCount >= 10">
      <div class="slide-track">
        <div v-for="(post, index) in postsLoaded" :key="index" class="slide">
          <NuxtLink :to="{name:'slug', params:{slug:decodeURIComponent(post.slug)}}" class="flex flex-row items-center md:flex-row md:max-w-xl">
            <div
              class="min-h-45 px-1.5 flex flex-col justify-between leading-normal border-2 border-r-indigo-500"
            >
              <p
                class="font-normal text-sm text-black line-clamp-2 font-sans"
                v-html="post.title"
              >
              </p>
            </div>
          </NuxtLink>
        </div>
      </div>
    </div>
    <div
      class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg text-center dark:bg-blue-200 dark:text-blue-800"
      role="alert"
      v-else
    >
      <span class="font-medium font-sans">Info alert!</span> Marquee can be loaded once your post count reach 10. Currently: {{postCount}}
    </div>
  </div>
</template>

<script>
import {mdiCircle} from "@mdi/js";

export default {
  name: "Marquee",
  props: [
    'title',
    'url',
    'widget_settings',
    'data_limit',
    'taxonomy',
    'data',
    'posts',
  ],
  data() {
    return {
      postsLoaded: null,
      mdiWeb: mdiCircle,
      loading: true,
      loaded: false,
      postCount: 0,
    }
  },
  methods: {
    getPosts() {
      if (this.posts) {
        this.loading = false;
        this.loaded = true;
        this.postCount = this.posts.data.length;
        this.postsLoaded = this.posts.data;
      } else {
        this.$axios
          .get(
            this.url ? this.url : '/frontend/posts', {
              params: {
                per_page: this.data_limit != 'null' ? this.data_limit : 10,
                taxonomy: this.taxonomy
              }
            }
          )
          .then(response => {
            this.postsLoaded = response.data.data;
            /*.reduce(function (res, current, index, array) {
            return res.concat([current, current]);
          }, []);*/
            this.loading = false;
            this.loaded = true;
            this.postCount = response.data.data.length;
          });
      }
    },
    findThumbSrc(post) {
      let img = post.media_sizes && post.media_sizes.file;
      if (img == null) {
        return '/assets/logo-square.svg';
      }
      let thumb = post.media_sizes.sizes && post.media_sizes.sizes.thumbnail && post.media_sizes.sizes.thumbnail.file;
      let urlparts = post.image.split('/');
      urlparts.pop();
      return urlparts.join('/') + '/' + thumb;
    },
    getImageSrc(src) {
      if (/^(?:[a-z]+:)?\/\//i.test(src)) {
        return src;
      }
      return this.$config.apiBaseURL + '/storage' + src;
    }
  },
  mounted() {
    this.getPosts();
  }
}
</script>

<style scoped>
/* Marquee Styles */
.marquee-container {
  height: 50px !important;
}
>>> .marquee-progress {
  top: calc(100% - 10px);
}
</style>
