<template>
  <div class="grid grid-cols-12 gap-2">
    <div class="col-span-12 border-y-2 border-zinc-600">
      <div class="grid grid-cols-6 gap-4 py-2">
        <div class="col-start-1 col-end-3">
          <a
            href="#"
            class="font-normal text-black text-2x font-sans"
          >
            মতামত
          </a>
        </div>
        <div class="col-end-8 col-span-3 lg:block hidden">
          <a
            href="#"
            class="text-gray-800 text-sm font-medium inline-flex items-center rounded mr-2"
          >
            <span class="pr-2 font-sans"> আরও পড়ুন </span>

            <svg
              version="1.1"
              id="Capa_1"
              xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink"
              x="0px"
              y="0px"
              width="18px"
              height="18px"
              viewBox="0 0 93.934 93.934"
              style="enable-background: new 0 0 93.934 93.934"
              xml:space="preserve"
            >
              <g>
                <path
                  d="M46.967,0C21.029,0,0,21.028,0,46.967c0,25.939,21.029,46.967,46.967,46.967c25.939,0,46.967-21.027,46.967-46.967
                            C93.934,21.027,72.906,0,46.967,0z M55.953,66.295V54.301H18.652V39.633h37.303V27.639l19.326,19.328L55.953,66.295z"
                />
              </g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
              <g></g>
            </svg>
          </a>
        </div>
      </div>
    </div>
    <div v-if="postsLoaded" class="col-span-12">
      <nuxt-link :to="{name: 'slug', params: {slug:decodeURIComponent(postsLoaded[0].slug)}}" class="flex items-center space-x-4">
        <div class="flex-shrink-0">
          <img
            class="w-20 h-20 rounded-full"
            :src="getImageSrc(postsLoaded[0].pictures[0].full)"
            alt="Neil image"
          />
        </div>
        <div class="flex-1 min-w-0">
          <p
            class="font-normal text-black text-base line-clamp-2 font-sans"
            v-if="widget_settings.show_title == 1" v-text="postsLoaded[0].title"
          >
          </p>
<!--          <p class="text-sm text-gray-500 truncate dark:text-gray-400 font-sans">-->
<!--            ড. আনোয়ার খসরু পারভেজ-->
<!--          </p>-->
        </div>
      </nuxt-link>
    </div>
    <div v-else class="col-span-12">
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
  </div>
</template>
<script>
import Vue from 'vue'
import qs from 'qs'
Vue.prototype.qs = qs

export default {
  name: "Decision",
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

