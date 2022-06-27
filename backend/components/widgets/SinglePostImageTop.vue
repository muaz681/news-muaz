<template>
<!-- dark:bg-gray-800 dark:border-gray-700 -->
  <div v-if="postsLoaded" class="bg-transparent">
    <nuxt-link :to="{name: 'slug', params: {slug:decodeURIComponent(postsLoaded[0].slug)}}">
      <img
        class=""
        :src="getImageSrc(postsLoaded[0].pictures[0].full)"
        alt=""
      />
      <div class="pt-3">
        <h5
          class="mb-2 text-xl font-medium tracking-tight text-black hover:text-red-700 line-clamp-2 font-sans"
          v-if="widget_settings.show_title == 1" v-text="postsLoaded[0].title"
        >
        </h5>
        <p
          class="lg:!line-clamp-2 !hidden mb-2 font-normal text-sm text-black font-sans"
          v-if="widget_settings.show_excerpt == 1" v-text="postsLoaded[0].short_description"
        >
        </p>
        <!-- End -->
      </div>
    </nuxt-link>
  </div>
  <div v-else class="bg-transparent dark:bg-gray-800 dark:border-gray-700">
    <div class="border border-blue-300 shadow rounded-md p-4 max-w-sm w-full mx-auto">
      <div class="animate-pulse flex space-x-4">
        <div class="rounded-full bg-slate-200 h-10 w-10"></div>
        <div class="flex-1 space-y-6 py-1">
          <div class="h-2 bg-slate-200 rounded"></div>
          <div class="space-y-3">
            <div class="grid grid-cols-3 gap-4">
              <div class="h-2 bg-slate-200 rounded col-span-2"></div>
              <div class="h-2 bg-slate-200 rounded col-span-1"></div>
            </div>
            <div class="h-2 bg-slate-200 rounded"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!--  -->
</template>

<script>
import Vue from 'vue'
import qs from 'qs'
Vue.prototype.qs = qs

export default {
  name: "SinglePostImageTop",
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
      loading: true,
      loaded: false,
      postCount: 0,
    }
  },
  methods: {
    getPosts() {
      if (this.posts && this.widget_settings.taxonomy == 'null') {
        this.loading = false;
        this.loaded = true;
        this.postCount = this.posts.data.length;
        this.postsLoaded = this.posts.data;
      } else {
        let category_ids, tag_ids;
        if(this.widget_settings.taxonomy === 'category') {
          category_ids = this.widget_settings.data.split(',');
        } else if (this.widget_settings.taxonomy === 'tag') {
          tag_ids = this.widget_settings.data.split(',');
        }
        this.$axios
          .get(
            this.url ? this.url : '/frontend/posts', {
              params: {
                per_page: this.data_limit === 'null' ? 1 : this.data_limit,
                category_ids: category_ids,
                tag_ids: tag_ids,
              },
              paramsSerializer: params => {
                return qs.stringify(params)
              }
            }
          )
          .then(response => {
            this.postsLoaded = response.data.data;
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

